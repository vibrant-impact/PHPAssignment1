<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect straight to catalog
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header('Location: index.php');
    exit();
}

include('header.php');

$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<main class="auth-wrapper">
    <div class="auth-card">
        <h2>Arcane Archive Sign-In</h2>
        <p class="auth-subtitle">Present your credentials to unlock your grimoire</p>

        <?php if (!empty($loginError)) : ?>
            <p class="error-msg alert-box"><?php echo htmlspecialchars($loginError); ?></p>
        <?php endif; ?>

        <form action="login.php" method="post" class="auth-form">
            <div class="form-group">
                <label for="username">Mage Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-actions-stacked">
                <button type="submit" class="btn full-width">Enter Archive</button>
                <p class="auth-switch">Need an account? <a href="register_user_form.php" class="spell-link">Register here</a></p>
            </div>
        </form>
    </div>
</main>

<?php include('footer.php'); ?>