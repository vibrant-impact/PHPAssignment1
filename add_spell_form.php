<?php
require_once('database.php');

$querySchools = 'SELECT * FROM schools ORDER BY schoolName ASC';
$statement = $db->prepare($querySchools);
$statement->execute();
$schools = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

include('header.php');
?>

<main>
    <h2>Add New Spell to Archive</h2>
    
    <form action="add_spell.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="spellName">Spell Name:</label>
            <input type="text" id="spellName" name="spellName" required maxlength="100">
        </div>

        <div class="form-group">
            <label for="schoolID">Arcane School:</label>
            <select id="schoolID" name="schoolID" required>
                <option value="">-- Select School --</option>
                <?php foreach ($schools as $school) : ?>
                    <option value="<?php echo htmlspecialchars($school['schoolID']); ?>">
                        <?php echo htmlspecialchars($school['schoolName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="spellLevel">Spell Level (0–9):</label>
            <input type="number" id="spellLevel" name="spellLevel" min="0" max="9" required>
        </div>

        <div class="form-group">
            <label for="castingTime">Casting Time:</label>
            <input type="text" id="castingTime" name="castingTime" placeholder="e.g. 1 Action" required maxlength="50">
        </div>

        <div class="form-group">
            <label for="suppliesNeeded">Supplies Needed:</label>
            <input type="text" id="suppliesNeeded" name="suppliesNeeded" required maxlength="255">
        </div>

        <div class="form-group">
            <label for="isAvailable">Status:</label>
            <select id="isAvailable" name="isAvailable">
                <option value="1">Available (In Vault)</option>
                <option value="0">Checked Out (On Loan)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="spellImage">Spell Glyph / Illustration:</label>
            <input type="file" id="spellImage" name="spellImage" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Add Spell</button>
            <a href="index.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</main>

<?php include('footer.php'); ?>