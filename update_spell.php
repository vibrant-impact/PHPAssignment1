<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database.php');
require_once('image_util.php');

$spellID = filter_input(INPUT_POST, 'spellID', FILTER_VALIDATE_INT);
$schoolID = filter_input(INPUT_POST, 'schoolID', FILTER_VALIDATE_INT);
$spellName = filter_input(INPUT_POST, 'spellName', FILTER_SANITIZE_SPECIAL_CHARS);
$spellLevel = filter_input(INPUT_POST, 'spellLevel', FILTER_VALIDATE_INT);
$castingTime = filter_input(INPUT_POST, 'castingTime', FILTER_SANITIZE_SPECIAL_CHARS);
$suppliesNeeded = filter_input(INPUT_POST, 'suppliesNeeded', FILTER_SANITIZE_SPECIAL_CHARS);
$isAvailable = filter_input(INPUT_POST, 'isAvailable', FILTER_VALIDATE_INT);
$currentImage = filter_input(INPUT_POST, 'currentImage', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$spellID || empty($spellName) || !$schoolID || $spellLevel === false || $spellLevel === null || empty($castingTime) || empty($suppliesNeeded) || $isAvailable === null) {
    $errorMessage = 'Invalid spell data. Ensure all fields are valid.';
    include('update_error.php');
    exit();
}

try {
    // If a new image was chosen, upload it; otherwise keep current image
    if (isset($_FILES['spellImage']) && $_FILES['spellImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        $imageFile = processImageUpload('spellImage');
    } else {
        $imageFile = $currentImage ?: 'placeholder.jpg';
    }

    $query = 'UPDATE spells 
              SET schoolID = :schoolID,
                  spellName = :spellName,
                  spellLevel = :spellLevel,
                  castingTime = :castingTime,
                  suppliesNeeded = :suppliesNeeded,
                  isAvailable = :isAvailable,
                  imageFile = :imageFile
              WHERE spellID = :spellID';

    $statement = $db->prepare($query);
    $statement->bindValue(':schoolID', $schoolID, PDO::PARAM_INT);
    $statement->bindValue(':spellName', $spellName);
    $statement->bindValue(':spellLevel', $spellLevel, PDO::PARAM_INT);
    $statement->bindValue(':castingTime', $castingTime);
    $statement->bindValue(':suppliesNeeded', $suppliesNeeded);
    $statement->bindValue(':isAvailable', $isAvailable, PDO::PARAM_INT);
    $statement->bindValue(':imageFile', $imageFile);
    $statement->bindValue(':spellID', $spellID, PDO::PARAM_INT);

    $statement->execute();
    $statement->closeCursor();

    include('update_spell_confirmation.php');
} catch (Exception $e) {
    $errorMessage = 'Update failed: ' . $e->getMessage();
    include('update_error.php');
    exit();
}
?>