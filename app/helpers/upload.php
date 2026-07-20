<?php
/**
 * File Upload Helper — whitelist extensions, randomize filenames.
 */

define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml']);
define('ALLOWED_PDF_TYPES',   ['application/pdf']);
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10 MB

/**
 * Upload an image file.
 * @param  array  $file     $_FILES['field']
 * @param  string $subdir   Sub-directory under uploads/ (e.g. 'projects')
 * @return array  ['success'=>bool, 'path'=>string|null, 'error'=>string|null]
 */
function uploadImage(array $file, string $subdir = 'misc'): array {
    return uploadFile($file, $subdir, ALLOWED_IMAGE_TYPES);
}

function uploadPdf(array $file, string $subdir = 'brochures'): array {
    return uploadFile($file, ALLOWED_PDF_TYPES, $subdir);
}

function uploadFile(array $file, string $subdir, array $allowedMimes): array {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'path' => null, 'error' => uploadError($file['error'])];
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'path' => null, 'error' => 'File too large (max 10 MB).'];
    }

    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $allowedMimes, true)) {
        return ['success' => false, 'path' => null, 'error' => "File type '$mime' not allowed."];
    }

    // Build destination
    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext  = strtolower($ext);
    $name = bin2hex(random_bytes(12)) . '.' . $ext;
    $dir  = UPLOAD_PATH . rtrim($subdir, '/') . '/';

    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        return ['success' => false, 'path' => null, 'error' => 'Could not create upload directory.'];
    }

    $dest = $dir . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => false, 'path' => null, 'error' => 'Failed to move uploaded file.'];
    }

    return ['success' => true, 'path' => $subdir . '/' . $name, 'error' => null];
}

function deleteUpload(?string $path): void {
    if (!$path) return;
    $full = UPLOAD_PATH . $path;
    if (file_exists($full)) {
        unlink($full);
    }
}

function uploadError(int $code): string {
    return match ($code) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File exceeds size limit.',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE => 'No file uploaded.',
        default            => 'Unknown upload error.',
    };
}
