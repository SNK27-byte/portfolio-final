<?php

const UPLOAD_MAX_BYTES = 10 * 1024 * 1024;

function uploadMaxSizeLabel(): string
{
    return '10 Mo';
}

function formatBytes(int $bytes): string
{
    if ($bytes >= 1024 * 1024) {
        return number_format($bytes / (1024 * 1024), 2, ',', ' ') . ' Mo';
    }

    return number_format($bytes / 1024, 0, ',', ' ') . ' Ko';
}

function detectUploadedMimeType(string $tmpPath, string $fallback): string
{
    if (!is_uploaded_file($tmpPath)) {
        return $fallback;
    }

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $detected = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
            if (is_string($detected) && $detected !== '') {
                return $detected;
            }
        }
    }

    $imageInfo = @getimagesize($tmpPath);
    if ($imageInfo !== false && isset($imageInfo['mime'])) {
        return $imageInfo['mime'];
    }

    return $fallback;
}

function uploadErrorMessage(int $code): string
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            return "L'image dépasse la limite du serveur PHP (upload_max_filesize). Augmente cette valeur dans php.ini ou compresse l'image.";
        case UPLOAD_ERR_FORM_SIZE:
            return "Le transfert de l'image a été refusé avant l'envoi au serveur. Recharge la page et réessaie.";
        case UPLOAD_ERR_PARTIAL:
            return "L'image n'a été transférée que partiellement. Réessaie.";
        case UPLOAD_ERR_NO_FILE:
            return "Aucune image n'a été envoyée.";
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
        case UPLOAD_ERR_EXTENSION:
            return "Le serveur n'a pas pu enregistrer l'image temporairement (code {$code}).";
        case 5:
            return "Format non accepté. Utilise un fichier JPG ou PNG.";
        case 6:
            return "Le type de fichier n'est pas reconnu comme une image JPG ou PNG valide.";
        case 7:
            return 'L\'image est trop volumineuse (maximum ' . uploadMaxSizeLabel() . ').';
        case 8:
            return "L'image n'a pas pu être enregistrée sur le serveur.";
        case 9:
            return "L'image n'a pas pu être redimensionnée.";
        default:
            return "Une erreur est survenue au niveau de l'image (code {$code}).";
    }
}
