<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/security/password.php';
csrf_verify();
$selector = $_POST['selector'] ?? '';
$token = $_POST['token'] ?? '';
$pass1 = $_POST['password'] ?? '';
$pass2 = $_POST['password2'] ?? '';
if ($pass1 !== $pass2 || strlen($pass1) < 8) {
    echo "<script>alert('Password validation failed.'); history.back();</script>";
    exit;
}
$st = $conn->prepare('SELECT user_id, token_hash, expires_at FROM password_resets WHERE selector = ?');
$st->bind_param('s', $selector);
$st->execute();
$res = $st->get_result();
$row = $res ? $res->fetch_assoc() : null;
$st->close();
if (!$row || strtotime($row['expires_at']) < time()) {
    echo "<script>alert('Reset link is invalid or expired.'); window.location.href='login.php';</script>";
    exit;
}
if (!password_verify($token, $row['token_hash'])) {
    echo "<script>alert('Invalid token.'); window.location.href='login.php';</script>";
    exit;
}
$salt = pwd_generate_salt(16);
$hash = pwd_hash_password($pass1, $salt);
$uid = (int)$row['user_id'];
@mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS password_salt VARCHAR(64) DEFAULT NULL");
$upd = $conn->prepare('UPDATE users SET password = ?, password_salt = ? WHERE user_id = ?');
$upd->bind_param('ssi', $hash, $salt, $uid);
$upd->execute();
$upd->close();
$del = $conn->prepare('DELETE FROM password_resets WHERE selector = ?');
$del->bind_param('s', $selector);
$del->execute();
$del->close();
echo "<script>alert('Password updated. Please log in.'); window.location.href='login.php';</script>";
