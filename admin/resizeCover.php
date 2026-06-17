<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/upload-limits.php';

$image = basename($_GET['image'] ?? '');
$updateId = isset($_GET['update']) && is_numeric($_GET['update']) ? (int) $_GET['update'] : null;

$redirectOnError = function () use ($updateId) {
    if ($updateId !== null) {
        header('LOCATION:updateProduct.php?id=' . $updateId . '&errorImg=9');
    } else {
        header('LOCATION:addProduct.php?errorImg=9');
    }
    exit();
};

if ($image === '') {
    $redirectOnError();
}

$path = '../images/' . $image;
if (!is_file($path)) {
    $redirectOnError();
}

$info = @getimagesize($path);
if ($info === false) {
    $redirectOnError();
}

$source = false;
$imageType = $info[2];

switch ($imageType) {
    case IMAGETYPE_JPEG:
        $source = @imagecreatefromjpeg($path);
        break;
    case IMAGETYPE_PNG:
        $source = @imagecreatefrompng($path);
        break;
    default:
        $redirectOnError();
}

if ($source === false) {
    $redirectOnError();
}

$nouvelleLargeur = 300;
$reduction = ($nouvelleLargeur * 100) / $info[0];
$nouvelleHauteur = (int) round(($info[1] * $reduction) / 100);

$destination = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
if ($destination === false) {
    imagedestroy($source);
    $redirectOnError();
}

if ($imageType === IMAGETYPE_PNG) {
    imagealphablending($destination, false);
    imagesavealpha($destination, true);
}

imagecopyresampled(
    $destination,
    $source,
    0,
    0,
    0,
    0,
    $nouvelleLargeur,
    $nouvelleHauteur,
    $info[0],
    $info[1]
);

$miniPath = '../images/mini_' . $image;
$saved = false;

if ($imageType === IMAGETYPE_JPEG) {
    $saved = imagejpeg($destination, $miniPath, 80);
} else {
    $saved = imagepng($destination, $miniPath);
}

imagedestroy($source);
imagedestroy($destination);

if (!$saved) {
    $redirectOnError();
}

if ($updateId !== null) {
    header('LOCATION:products.php?update=' . $updateId);
} else {
    header('LOCATION:products.php?add=success');
}

exit();
