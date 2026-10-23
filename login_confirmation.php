<?php
include('header.php');
?>
<main class="auth-wrapper">
    <div class="auth-card confirmation-card">
        <h2>Authentication Granted</h2>
        <p>Welcome back, Mage <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.</p>
        <p>Your session is verified and your grimoire is unlocked.</p>
        <div class="confirm-actions">
            <a href="index.php" class="btn">View My Spells</a>
        </div>
    </div>
</main>
<?php include('footer.php'); ?>