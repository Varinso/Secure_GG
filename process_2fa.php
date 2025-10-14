<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/security/otp.php';
require_once __DIR__ . '/db.php'; // provides $conn (mysqli)

csrf_verify();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$code = sanitize_str($_POST['code'] ?? '');
$pending_id = $_SESSION['pending_2fa_user_id'] ?? null;

if (!$pending_id) {
    $_SESSION['flash'] = 'Session expired. Please log in again.';
    header('Location: login.php');
    exit;
}

if (otp_verify_and_consume($conn, (int)$pending_id, $code)) {
    // Success: finalize login
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$pending_id;
    unset($_SESSION['pending_2fa_user_id']);
    $_SESSION['flash'] = 'Login successful.';
    header('Location: profile.php');
    exit;
} else {
    $_SESSION['flash'] = 'Invalid or expired code.';
    header('Location: 2fa.php');
    exit;
}
?>
