<?php
require_once('database.php');

$spellID = filter_input(INPUT_POST, 'spellID', FILTER_VALIDATE_INT);

if ($spellID) {
    $query = 'DELETE FROM spells WHERE spellID = :spellID';
    $statement = $db->prepare($query);
    $statement->bindValue(':spellID', $spellID, PDO::PARAM_INT);
    $statement->execute();
    $statement->closeCursor();
}

// Redirect back to main list
header('Location: index.php');
exit();
?>