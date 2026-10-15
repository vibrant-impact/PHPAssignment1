<?php
require_once('database.php');

$spellID = filter_input(INPUT_GET, 'spellID', FILTER_VALIDATE_INT);
if (!$spellID) {
    $spellID = filter_input(INPUT_POST, 'spellID', FILTER_VALIDATE_INT);
}

if (!$spellID) {
    $errorMessage = 'Valid Spell ID is required to view details.';
    include('database_error.php');
    exit();
}

$query = 'SELECT spells.*, schools.schoolName, schools.schoolDescription 
          FROM spells 
          INNER JOIN schools ON spells.schoolID = schools.schoolID 
          WHERE spells.spellID = :spellID';

$statement = $db->prepare($query);
$statement->bindValue(':spellID', $spellID, PDO::PARAM_INT);
$statement->execute();
$spell = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if (!$spell) {
    $errorMessage = 'Spell not found in the archives.';
    include('database_error.php');
    exit();
}

// Check 400px image existence
$fileInfo = pathinfo($spell['imageFile']);
$detailImageName = $fileInfo['filename'] . '_400.' . ($fileInfo['extension'] ?? 'jpg');
$detailImagePath = 'images/' . $detailImageName;

if (!file_exists($detailImagePath)) {
    $detailImagePath = 'images/placeholder_400.jpg';
}

include('header.php');
?>

<main class="details-wrapper">
    <div class="breadcrumb-bar">
        <a href="index.php" class="back-link">&larr; Back to Catalog</a>
    </div>

    <article class="spell-card">
        <div class="spell-media">
            <div class="sigil-glow-ring">
                <img src="<?php echo htmlspecialchars($detailImagePath); ?>" 
                     alt="<?php echo htmlspecialchars($spell['spellName']); ?>" 
                     class="details-sigil-img">
            </div>
        </div>

        <div class="spell-info">
            <div class="spell-title-group">
                <h2><?php echo htmlspecialchars($spell['spellName']); ?></h2>
                <div class="badge-row">
                    <span class="school-pill"><?php echo htmlspecialchars($spell['schoolName']); ?></span>
                    <?php if ($spell['isAvailable']) : ?>
                        <span class="status-badge vault">Available in Vault</span>
                    <?php else : ?>
                        <span class="status-badge loaned">Checked Out</span>
                    <?php endif; ?>
                </div>
            </div>

            <p class="school-desc"><?php echo htmlspecialchars($spell['schoolDescription']); ?></p>

            <div class="spell-meta-grid">
                <div class="meta-row">
                    <span class="meta-label">Spell Level</span>
                    <span class="meta-value">Level <?php echo htmlspecialchars($spell['spellLevel']); ?></span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Casting Time</span>
                    <span class="meta-value"><?php echo htmlspecialchars($spell['castingTime']); ?></span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Required Supplies</span>
                    <span class="meta-value"><?php echo htmlspecialchars($spell['suppliesNeeded']); ?></span>
                </div>
            </div>

            <div class="details-actions">
                <form action="update_spell_form.php" method="post">
                    <input type="hidden" name="spellID" value="<?php echo htmlspecialchars($spell['spellID']); ?>">
                    <button type="submit" class="btn edit-btn">Edit Spell</button>
                </form>
                <form action="delete_spell.php" method="post" onsubmit="return confirm('Banish this spell from the archives permanently?');">
                    <input type="hidden" name="spellID" value="<?php echo htmlspecialchars($spell['spellID']); ?>">
                    <button type="submit" class="btn-action delete-btn">Delete Spell</button>
                </form>
                <a href="index.php" class="btn-secondary">Return to Catalog</a>
            </div>
        </div>
    </article>
</main>

<?php include('footer.php'); ?>