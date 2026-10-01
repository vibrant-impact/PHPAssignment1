<?php
require_once('database.php');

$querySpells = 'SELECT spells.*, schools.schoolName 
                FROM spells 
                INNER JOIN schools ON spells.schoolID = schools.schoolID 
                ORDER BY spells.spellLevel ASC, spells.spellName ASC';
$statement = $db->prepare($querySpells);
$statement->execute();
$spells = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

include('header.php');
?>

<main>
    <div class="table-header-action">
        <h2>Cataloged Grimoires & Scrolls</h2>
        <a href="add_spell_form.php" class="btn">Add New Spell</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sigil</th>
                <th>Spell Name</th>
                <th>School</th>
                <th>Level</th>
                <th>Casting Time</th>
                <th>Supplies</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($spells as $spell) : 
                $fileInfo = pathinfo($spell['imageFile']);
                $thumbName = $fileInfo['filename'] . '_100.' . $fileInfo['extension'];
                $thumbPath = 'images/' . $thumbName;
                if (!file_exists($thumbPath)) {
                    $thumbPath = 'images/placeholder_100.jpg';
                }
            ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($thumbPath); ?>" 
                             alt="<?php echo htmlspecialchars($spell['spellName']); ?>" 
                             class="table-thumb">
                    </td>
                    <td><strong><?php echo htmlspecialchars($spell['spellName']); ?></strong></td>
                    <td><span class="school-pill"><?php echo htmlspecialchars($spell['schoolName']); ?></span></td>
                    <td><?php echo htmlspecialchars($spell['spellLevel']); ?></td>
                    <td><?php echo htmlspecialchars($spell['castingTime']); ?></td>
                    <td><?php echo htmlspecialchars($spell['suppliesNeeded']); ?></td>
                    <td>
                        <?php if ($spell['isAvailable']) : ?>
                            <span class="status-badge vault">In Vault</span>
                        <?php else : ?>
                            <span class="status-badge loaned">On Loan</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-cell">
                        <form action="update_spell_form.php" method="post" style="display:inline;">
                            <input type="hidden" name="spellID" value="<?php echo htmlspecialchars($spell['spellID']); ?>">
                            <button type="submit" class="btn-action edit">Edit</button>
                        </form>
                        <form action="delete_spell.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to banish this spell?');">
                            <input type="hidden" name="spellID" value="<?php echo htmlspecialchars($spell['spellID']); ?>">
                            <button type="submit" class="btn-action delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include('footer.php'); ?>