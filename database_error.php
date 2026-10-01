<?php include('header.php'); ?>
<main>
    <h2>Database Connection Error</h2>
    <p>Could not connect to the spell archive database.</p>
    <p class="error-msg">Error Details: <?php echo htmlspecialchars($errorMessage); ?></p>
</main>
<?php include('footer.php'); ?>