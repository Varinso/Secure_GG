<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php';
require_login();

$cfg = @include __DIR__ . '/config/security.php';
$max = $cfg['UPLOADS']['MAX_BYTES'] ?? (2 * 1024 * 1024);
$allowedMime = $cfg['UPLOADS']['ALLOWED_MIME'] ?? ['image/png','image/jpeg','application/pdf'];
$allowedExt  = $cfg['UPLOADS']['ALLOWED_EXT'] ?? ['png','jpg','jpeg','pdf'];

csrf_verify();

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo "<script>alert('Upload failed.'); history.back();</script>"; exit;
}

$tmp = $_FILES['file']['tmp_name'];
$name = $_FILES['file']['name'];
$size = (int)$_FILES['file']['size'];

if ($size <= 0 || $size > $max) { echo "<script>alert('Invalid file size.'); history.back();</script>"; exit; }

$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt, true)) { echo "<script>alert('File type not allowed (ext).'); history.back();</script>"; exit; }

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($tmp);
if ($mime === false || !in_array($mime, $allowedMime, true)) { echo "<script>alert('File type not allowed (mime).'); history.back();</script>"; exit; }

$base = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($name, PATHINFO_FILENAME));
$rand = bin2hex(random_bytes(8));
$final = $base . '_' . $rand . '.' . $ext;

$dest = __DIR__ . '/uploads/' . $final;
if (!move_uploaded_file($tmp, $dest)) { echo "<script>alert('Could not save file.'); history.back();</script>"; exit; }

echo "<script>alert('Upload successful.'); window.location.href='upload.php';</script>";
