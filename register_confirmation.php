<?php
include('header.php');
?>
<main class="auth-wrapper">
    <div class="auth-card confirmation-card">
        <h2>Initiation Complete</h2>
        <p>Welcome to the Arcane Archive, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.</p>
        <p>Your grimoire has been created and you are now authenticated.</p>
        <div class="confirm-actions">
            <a href="index.php" class="btn">Access My Grimoire</a>
            <a href="add_spell_form.php" class="btn-secondary">Inscribe First Spell</a>
        </div>
    </div>
</main>
<?php include('footer.php'); ?>