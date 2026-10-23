<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('database.php');

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = 'Please enter both username and password.';
    header('Location: login_form.php');
    exit();
}

// Fetch user by username
$query = 'SELECT * FROM users WHERE username = :username LIMIT 1';
$statement = $db->prepare($query);
$statement->bindValue(':username', $username);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if (!$user) {
    $_SESSION['login_error'] = 'Invalid username or password.';
    header('Location: login_form.php');
    exit();
}

$now = new DateTime();

// Check if currently locked out
if ($user['lockout_time'] !== null) {
    $lockoutExpiry = new DateTime($user['lockout_time']);
    if ($now < $lockoutExpiry) {
        $interval = $now->diff($lockoutExpiry);
        $minutesLeft = $interval->i + ($interval->s > 0 ? 1 : 0);
        $_SESSION['login_error'] = "Account locked due to 3 failed attempts. Try again in $minutesLeft minute(s).";
        header('Location: login_form.php');
        exit();
    } else {
        // Lockout expired: reset failed attempts
        $resetQuery = 'UPDATE users SET failed_attempts = 0, lockout_time = NULL WHERE userID = :userID';
        $stmtReset = $db->prepare($resetQuery);
        $stmtReset->bindValue(':userID', $user['userID'], PDO::PARAM_INT);
        $stmtReset->execute();
        $stmtReset->closeCursor();
        $user['failed_attempts'] = 0;
    }
}

// Verify password
if (password_verify($password, $user['password'])) {
    // Reset failed counter upon successful login
    $resetQuery = 'UPDATE users SET failed_attempts = 0, lockout_time = NULL WHERE userID = :userID';
    $stmtReset = $db->prepare($resetQuery);
    $stmtReset->bindValue(':userID', $user['userID'], PDO::PARAM_INT);
    $stmtReset->execute();
    $stmtReset->closeCursor();

    // Set Session State
    $_SESSION['is_logged_in'] = true;
    $_SESSION['userID'] = (int)$user['userID'];
    $_SESSION['username'] = $user['username'];

    header('Location: login_confirmation.php');
    exit();
} else {
    // Increment failed attempts
    $newAttempts = $user['failed_attempts'] + 1;

    if ($newAttempts >= 3) {
        // Lock for 5 minutes
        $lockoutUntil = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');
        $lockQuery = 'UPDATE users SET failed_attempts = :attempts, lockout_time = :lockout WHERE userID = :userID';
        $stmtLock = $db->prepare($lockQuery);
        $stmtLock->bindValue(':attempts', $newAttempts, PDO::PARAM_INT);
        $stmtLock->bindValue(':lockout', $lockoutUntil);
        $stmtLock->bindValue(':userID', $user['userID'], PDO::PARAM_INT);
        $stmtLock->execute();
        $stmtLock->closeCursor();

        $_SESSION['login_error'] = 'Account locked for 5 minutes after 3 failed login attempts.';
    } else {
        $remaining = 3 - $newAttempts;
        $failQuery = 'UPDATE users SET failed_attempts = :attempts WHERE userID = :userID';
        $stmtFail = $db->prepare($failQuery);
        $stmtFail->bindValue(':attempts', $newAttempts, PDO::PARAM_INT);
        $stmtFail->bindValue(':userID', $user['userID'], PDO::PARAM_INT);
        $stmtFail->execute();
        $stmtFail->closeCursor();

        $_SESSION['login_error'] = "Invalid login credentials. $remaining attempt(s) remaining before temporary lockout.";
    }

    header('Location: login_form.php');
    exit();
}
?>