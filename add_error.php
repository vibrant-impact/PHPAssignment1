<?php include('header.php'); ?>

<main>
    <h2>Archive Entry Error</h2>
    <p class="error-msg"><?php echo htmlspecialchars($errorMessage); ?></p>
    <p><a href="add_spell_form.php" class="btn">Return to Add Spell Form</a></p>
</main>

<?php include('footer.php'); ?>