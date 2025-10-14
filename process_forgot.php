<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php';
csrf_verify();

$usernameRaw = $_POST['username'] ?? '';
$username = trim($usernameRaw);

// Basic username validation (letters, numbers, dot, underscore; 3–32 chars)
if (!preg_match('/^[A-Za-z0-9._]{3,32}$/', $username)) {
    echo "<script>alert('Invalid username'); history.back();</script>"; exit;
}

// Find user by username
$stmt = $conn->prepare('SELECT user_id, email FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();
$user = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$user) {
    echo "<script>alert('We couldn\\'t find your account.'); history.back();</script>"; exit;
}

// Ensure the password_resets table exists
$conn->query("CREATE TABLE IF NOT EXISTS password_resets (
  user_id INT NOT NULL,
  selector CHAR(16) NOT NULL,
  token_hash CHAR(60) NOT NULL,
  expires_at DATETIME NOT NULL,
  PRIMARY KEY (selector),
  INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Create selector + token and store bcrypt hash of token
$selector = bin2hex(random_bytes(8)); // 16 hex chars
$token = bin2hex(random_bytes(16));   // 32 hex chars
$tokenHash = password_hash($token, PASSWORD_BCRYPT);
$expMins = 30;

$ins = $conn->prepare('INSERT INTO password_resets (user_id, selector, token_hash, expires_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE)) ON DUPLICATE KEY UPDATE token_hash=VALUES(token_hash), expires_at=VALUES(expires_at)');
$uid = (int)$user['user_id'];
$ins->bind_param('issi', $uid, $selector, $tokenHash, $expMins);
$ins->execute();
$ins->close();

// Build reset link
$scheme = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https://' : 'http://';
$base = $scheme . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['REQUEST_URI'] ?? '/newtemp/'), '/');
$resetLink = $base . '/reset_password.php?selector=' . urlencode($selector) . '&token=' . urlencode($token);

// Send email to the account’s email
$email = $user['email'];
$subject = 'Password reset link';
$message = "We received a request to reset your password.\n\nOpen this link to reset:\n$resetLink\n\nThis link expires in $expMins minutes.";

// SMTP if configured; fallback to mail()
$mailCfg = @include __DIR__ . '/config/mail.php';
$sent = false;
if (is_array($mailCfg) && !empty($mailCfg['ENABLED'])) {
    require_once __DIR__ . '/lib/smtp.php';
    $err = null;
    $sent = smtp_send(
        $email, $subject, $message,
        $mailCfg['FROM'] ?? 'no-reply@example.com',
        $mailCfg['REPLY_TO'] ?? ($mailCfg['FROM'] ?? 'no-reply@example.com'),
        $mailCfg['HOST'] ?? 'smtp.gmail.com',
        (int)($mailCfg['PORT'] ?? 465),
        $mailCfg['USERNAME'] ?? null,
        $mailCfg['PASSWORD'] ?? null,
        $mailCfg['SMTP_SECURE'] ?? 'ssl',
        30,
        $err
    );
}
if (!$sent) { @mail($email, $subject, $message, 'From: no-reply@example.com'); }

// Mask email like "cri************.com" (first 3 of local + 12 stars + "." + TLD)
function mask_email_simple($email) {
    $parts = explode('@', $email);
    if (count($parts) !== 2) return '********';
    $local = $parts[0];
    $domain = $parts[1];
    $tld = $domain;
    $dotPos = strrpos($domain, '.');
    if ($dotPos !== false) { $tld = substr($domain, $dotPos + 1); }
    $prefix = substr($local, 0, 3);
    return $prefix . str_repeat('*', 12) . '.' . $tld;
}

// Save masked email to session and redirect to confirmation page
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$_SESSION['forgot_masked_email'] = mask_email_simple($email);
header('Location: forgot_sent.php');
exit;
