<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Current script filename
$currentPage = basename($_SERVER['PHP_SELF']);

// Whitelist of public pages accessible without login
$publicPages = [
    'login_form.php',
    'login.php',
    'register_user_form.php',
    'register_user.php',
    'register_confirmation.php',
    'database_error.php'
];

// If not logged in and not on a public page, redirect to login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    if (!in_array($currentPage, $publicPages)) {
        header('Location: login_form.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcane Archive | Spell Lending Library</title>
    <link rel="stylesheet" href="css/spell.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="header-inner">
            <h1>Arcane Archive & Lending Registry</h1>
            <nav class="user-nav">
                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) : ?>
                    <span class="user-welcome">Archmage: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                    <a href="index.php" class="nav-link">My Grimoire</a>
                    <a href="logout.php" class="btn-action delete">Logout</a>
                <?php else : ?>
                    <a href="login_form.php" class="nav-link">Login</a>
                    <a href="register_user_form.php" class="nav-link">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>