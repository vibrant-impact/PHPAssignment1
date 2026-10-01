<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database.php');

$spellID = filter_input(INPUT_POST, 'spellID', FILTER_VALIDATE_INT);
if (!$spellID) {
    $spellID = filter_input(INPUT_GET, 'spellID', FILTER_VALIDATE_INT);
}

if (!$spellID) {
    $errorMessage = 'Missing or invalid spell ID.';
    include('update_error.php');
    exit();
}

// Fetch spell
$querySpell = 'SELECT * FROM spells WHERE spellID = :spellID';
$stmtSpell = $db->prepare($querySpell);
$stmtSpell->bindValue(':spellID', $spellID, PDO::PARAM_INT);
$stmtSpell->execute();
$spell = $stmtSpell->fetch(PDO::FETCH_ASSOC);
$stmtSpell->closeCursor();

if (!$spell) {
    $errorMessage = 'Spell record not found.';
    include('update_error.php');
    exit();
}

// Fetch schools for dropdown
$querySchools = 'SELECT * FROM schools ORDER BY schoolName ASC';
$stmtSchools = $db->prepare($querySchools);
$stmtSchools->execute();
$schools = $stmtSchools->fetchAll(PDO::FETCH_ASSOC);
$stmtSchools->closeCursor();

include('header.php');
?>

<main>
    <h2>Update Spell Grimoire</h2>

    <form action="update_spell.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="spellID" value="<?php echo htmlspecialchars($spell['spellID']); ?>">
        <input type="hidden" name="currentImage" value="<?php echo htmlspecialchars($spell['imageFile']); ?>">

        <div class="form-group">
            <label for="spellName">Spell Name:</label>
            <input type="text" id="spellName" name="spellName" value="<?php echo htmlspecialchars($spell['spellName']); ?>" required maxlength="100">
        </div>

        <div class="form-group">
            <label for="schoolID">Arcane School:</label>
            <select id="schoolID" name="schoolID" required>
                <?php foreach ($schools as $school) : ?>
                    <option value="<?php echo htmlspecialchars($school['schoolID']); ?>" <?php if ($school['schoolID'] == $spell['schoolID']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($school['schoolName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="spellLevel">Spell Level (0–9):</label>
            <input type="number" id="spellLevel" name="spellLevel" min="0" max="9" value="<?php echo htmlspecialchars($spell['spellLevel']); ?>" required>
        </div>

        <div class="form-group">
            <label for="castingTime">Casting Time:</label>
            <input type="text" id="castingTime" name="castingTime" value="<?php echo htmlspecialchars($spell['castingTime']); ?>" required maxlength="50">
        </div>

        <div class="form-group">
            <label for="suppliesNeeded">Supplies Needed:</label>
            <input type="text" id="suppliesNeeded" name="suppliesNeeded" value="<?php echo htmlspecialchars($spell['suppliesNeeded']); ?>" required maxlength="255">
        </div>

        <div class="form-group">
            <label for="isAvailable">Status:</label>
            <select id="isAvailable" name="isAvailable">
                <option value="1" <?php if ($spell['isAvailable'] == 1) echo 'selected'; ?>>Available (In Vault)</option>
                <option value="0" <?php if ($spell['isAvailable'] == 0) echo 'selected'; ?>>Checked Out (On Loan)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Current Sigil:</label>
            <?php 
                $fileInfo = pathinfo($spell['imageFile']);
                $thumb = 'images/' . $fileInfo['filename'] . '_100.' . $fileInfo['extension'];
                if (!file_exists($thumb)) $thumb = 'images/placeholder_100.jpg';
            ?>
            <img src="<?php echo htmlspecialchars($thumb); ?>" alt="Current image" class="table-thumb">
        </div>

        <div class="form-group">
            <label for="spellImage">Replace Sigil (Optional):</label>
            <input type="file" id="spellImage" name="spellImage" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Save Changes</button>
            <a href="index.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</main>

<?php include('footer.php'); ?>