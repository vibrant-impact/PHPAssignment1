<?php
// Function to delete old image files if they are not placeholders
function deleteOldSpellImages($fileName, $targetDirectory = 'images/') {
    if (empty($fileName)) {
        return;
    }

    // Do NOT delete placeholder images
    if (stripos($fileName, 'placeholder') !== false) {
        return;
    }

    $fileInfo = pathinfo($fileName);
    $baseName = $fileInfo['filename'];
    $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';

    $filesToDelete = [
        $targetDirectory . $fileName,
        $targetDirectory . $baseName . '_100' . $extension,
        $targetDirectory . $baseName . '_400' . $extension
    ];

    foreach ($filesToDelete as $file) {
        if (file_exists($file) && is_file($file)) {
            unlink($file);
        }
    }
}

function processImageUpload($fileInputName, $targetDirectory = 'images/') {
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return 'placeholder.jpg';
    }

    if ($_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Image upload failed with error code: ' . $_FILES[$fileInputName]['error']);
    }

    $tempPath = $_FILES[$fileInputName]['tmp_name'];
    $originalName = basename($_FILES[$fileInputName]['name']);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Invalid file type. Please upload a JPG, PNG, or GIF.');
    }

    $baseName = 'spell_' . time() . '_' . bin2hex(random_bytes(4));
    $storedFileName = $baseName . '.' . $fileExtension;

    $originalTargetPath = $targetDirectory . $storedFileName;
    $target100Path = $targetDirectory . $baseName . '_100.' . $fileExtension;
    $target400Path = $targetDirectory . $baseName . '_400.' . $fileExtension;

    if (!move_uploaded_file($tempPath, $originalTargetPath)) {
        throw new Exception('Could not move uploaded image file.');
    }

    resizeImage($originalTargetPath, $target100Path, 100, 100);
    resizeImage($originalTargetPath, $target400Path, 400, 400);

    return $storedFileName;
}

function resizeImage($sourceFile, $destinationFile, $maxWidth, $maxHeight) {
    list($origWidth, $origHeight, $imageType) = getimagesize($sourceFile);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourceFile);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourceFile);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourceFile);
            break;
        default:
            return false;
    }

    $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
    $newWidth = (int) round($origWidth * $ratio);
    $newHeight = (int) round($origHeight * $ratio);

    $virtualImage = imagecreatetruecolor($newWidth, $newHeight);

    if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
        imagecolortransparent($virtualImage, imagecolorallocatealpha($virtualImage, 0, 0, 0, 127));
        imagealphablending($virtualImage, false);
        imagesavealpha($virtualImage, true);
    }

    imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($virtualImage, $destinationFile, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($virtualImage, $destinationFile);
            break;
        case IMAGETYPE_GIF:
            imagegif($virtualImage, $destinationFile);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($virtualImage);
    return true;
}
?>