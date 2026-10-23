<?php
include('header.php');
require_once('database.php');

$loggedInUserID = $_SESSION['userID'];

// Query only spells belonging to the logged-in user
$querySpells = 'SELECT spells.*, schools.schoolName 
                FROM spells 
                INNER JOIN schools ON spells.schoolID = schools.schoolID 
                WHERE spells.userID = :userID 
                ORDER BY spells.spellLevel ASC, spells.spellName ASC';
$statement = $db->prepare($querySpells);
$statement->bindValue(':userID', $loggedInUserID, PDO::PARAM_INT);
$statement->execute();
$spells = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>

<main>
    <div class="table-header-action">
        <h2>My Grimoire Collection (<?php echo count($spells); ?> Spells)</h2>
        <a href="add_spell_form.php" class="btn">Inscribe New Spell</a>
    </div>

    <?php if (empty($spells)) : ?>
        <p class="empty-notice">Your grimoire is empty. Click <strong>Inscribe New Spell</strong> to add your first enchantment!</p>
    <?php else : ?>
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
                    $thumbName = $fileInfo['filename'] . '_100.' . ($fileInfo['extension'] ?? 'jpg');
                    $thumbPath = 'images/' . $thumbName;
                    if (!file_exists($thumbPath)) {
                        $thumbPath = 'images/placeholder_100.jpg';
                    }
                ?>
                    <tr>
                        <td>
                            <a href="spell_details.php?spellID=<?php echo htmlspecialchars($spell['spellID']); ?>">
                                <img src="<?php echo htmlspecialchars($thumbPath); ?>" 
                                     alt="<?php echo htmlspecialchars($spell['spellName']); ?>" 
                                     class="table-thumb">
                            </a>
                        </td>
                        <td>
                            <a href="spell_details.php?spellID=<?php echo htmlspecialchars($spell['spellID']); ?>" class="spell-link">
                                <strong><?php echo htmlspecialchars($spell['spellName']); ?></strong>
                            </a>
                        </td>
                        <td><span class="school-pill"><?php echo htmlspecialchars($spell['schoolName']); ?></span></td>
                        <td>Level <?php echo htmlspecialchars($spell['spellLevel']); ?></td>
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
                            <form action="delete_spell.php" method="post" style="display:inline;" onsubmit="return confirm('Banish this spell permanently?');">
                                <input type="hidden" name="spellID" value="<?php echo htmlspecialchars($spell['spellID']); ?>">
                                <button type="submit" class="btn-action delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include('footer.php'); ?>