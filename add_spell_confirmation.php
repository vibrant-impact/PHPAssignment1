<?php include('header.php'); ?>

<main>
    <h2>Grimoire Updated Successfully</h2>
    <p><strong><?php echo htmlspecialchars($spellName); ?></strong> (Level <?php echo htmlspecialchars($spellLevel); ?>) has been cataloged into the library archive.</p>
    <p><a href="index.php" class="btn">View All Spells</a> | <a href="add_spell_form.php" class="btn-secondary">Add Another Spell</a></p>
</main>

<?php include('footer.php'); ?>