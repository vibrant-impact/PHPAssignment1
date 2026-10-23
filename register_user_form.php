<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('header.php');

$registerError = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>

<main class="auth-wrapper">
    <div class="auth-card">
        <h2>Arcane Registration</h2>
        <p class="auth-subtitle">Create your personal grimoire account</p>

        <?php if (!empty($registerError)) : ?>
            <p class="error-msg alert-box"><?php echo htmlspecialchars($registerError); ?></p>
        <?php endif; ?>

        <form action="register_user.php" method="post" class="auth-form">
            <div class="form-group">
                <label for="username">Mage Username:</label>
                <input type="text" id="username" name="username" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="email">Arcane Council Email:</label>
                <input type="email" id="email" name="email" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required minlength="6">
            </div>

            <div class="form-actions-stacked">
                <button type="submit" class="btn full-width">Complete Registration</button>
                <p class="auth-switch">Already registered? <a href="login_form.php" class="spell-link">Login here</a></p>
            </div>
        </form>
    </div>
</main>

<?php include('footer.php'); ?>