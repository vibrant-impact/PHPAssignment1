<?php
require_once('database.php');
require_once('image_util.php');

$spellName = filter_input(INPUT_POST, 'spellName', FILTER_SANITIZE_SPECIAL_CHARS);
$schoolID = filter_input(INPUT_POST, 'schoolID', FILTER_VALIDATE_INT);
$spellLevel = filter_input(INPUT_POST, 'spellLevel', FILTER_VALIDATE_INT);
$castingTime = filter_input(INPUT_POST, 'castingTime', FILTER_SANITIZE_SPECIAL_CHARS);
$suppliesNeeded = filter_input(INPUT_POST, 'suppliesNeeded', FILTER_SANITIZE_SPECIAL_CHARS);
$isAvailable = filter_input(INPUT_POST, 'isAvailable', FILTER_VALIDATE_INT);

if (empty($spellName) || !$schoolID || $spellLevel === false || $spellLevel === null || empty($castingTime) || empty($suppliesNeeded) || $isAvailable === null) {
    $errorMessage = 'Invalid spell data. Ensure all fields are filled properly.';
    include('add_error.php');
    exit();
}

try {
    $imageFile = processImageUpload('spellImage');

    $query = 'INSERT INTO spells (schoolID, spellName, spellLevel, castingTime, suppliesNeeded, isAvailable, imageFile)
              VALUES (:schoolID, :spellName, :spellLevel, :castingTime, :suppliesNeeded, :isAvailable, :imageFile)';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':schoolID', $schoolID, PDO::PARAM_INT);
    $statement->bindValue(':spellName', $spellName);
    $statement->bindValue(':spellLevel', $spellLevel, PDO::PARAM_INT);
    $statement->bindValue(':castingTime', $castingTime);
    $statement->bindValue(':suppliesNeeded', $suppliesNeeded);
    $statement->bindValue(':isAvailable', $isAvailable, PDO::PARAM_INT);
    $statement->bindValue(':imageFile', $imageFile);

    $statement->execute();
    $statement->closeCursor();

    include('add_spell_confirmation.php');
} catch (Exception $e) {
    $errorMessage = 'Error saving spell: ' . $e->getMessage();
    include('add_error.php');
    exit();
}
?>