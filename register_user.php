<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('database.php');

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if (empty($username) || !$email || empty($password)) {
    $_SESSION['register_error'] = 'All fields are required and email must be valid.';
    header('Location: register_user_form.php');
    exit();
}

if ($password !== $confirmPassword) {
    $_SESSION['register_error'] = 'Passwords do not match.';
    header('Location: register_user_form.php');
    exit();
}

if (strlen($password) < 6) {
    $_SESSION['register_error'] = 'Password must be at least 6 characters long.';
    header('Location: register_user_form.php');
    exit();
}

// Check for existing username or email
$checkQuery = 'SELECT userID FROM users WHERE username = :username OR email = :email LIMIT 1';
$stmtCheck = $db->prepare($checkQuery);
$stmtCheck->bindValue(':username', $username);
$stmtCheck->bindValue(':email', $email);
$stmtCheck->execute();
$existingUser = $stmtCheck->fetch(PDO::FETCH_ASSOC);
$stmtCheck->closeCursor();

if ($existingUser) {
    $_SESSION['register_error'] = 'Username or email is already registered.';
    header('Location: register_user_form.php');
    exit();
}

// Hash password securely with bcrypt
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$insertQuery = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
$stmt = $db->prepare($insertQuery);
$stmt->bindValue(':username', $username);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':password', $hashedPassword);
$stmt->execute();
$newUserID = $db->lastInsertId();
$stmt->closeCursor();

// Automatically log user in upon registration
$_SESSION['is_logged_in'] = true;
$_SESSION['userID'] = (int)$newUserID;
$_SESSION['username'] = $username;

header('Location: register_confirmation.php');
exit();
?>