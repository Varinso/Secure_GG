<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/security/throttle.php';
require_once __DIR__ . '/security/otp.php';
require_once __DIR__ . '/db.php'; // $conn (mysqli)
require_once __DIR__ . '/security/password.php';


csrf_verify();
require_once __DIR__ . '/security/captcha.php';
if (captcha_enabled_for('login') && !captcha_verify()) { echo "<script>alert('CAPTCHA failed.'); window.history.back();</script>"; exit; }


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$identifierRaw = $_POST['email_or_username'] ?? '';
$passwordRaw   = $_POST['password'] ?? '';
$identifier = sanitize_str($identifierRaw, 100);
$password   = (string)$passwordRaw; // keep full password length

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// Throttling: deny if too many failed attempts in last 15 minutes
if (!throttle_can_attempt($conn, $identifier, $ip, 5, 15)) {
    echo "<script>alert('Too many failed logins. Please try again later.'); window.location.href='login.php';</script>";
    exit();
}

// Prepared statement to find user by username or email
$stmt = $conn->prepare("SELECT user_id, username, email, password, password_salt, COALESCE(twofa_enabled, 1) AS twofa_enabled FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows === 1) {
    $user = $res->fetch_assoc();
    $stmt->close();

    // Verify password (already stored with bcrypt per your DB dump)
    if (isset($user['password_salt']) && !empty($user['password_salt'])) {
        $ok = pwd_verify($password, $user['password_salt'], $user['password']);
    } else {
        // Fallback to PHP's password_verify for existing hashes without salt
        $ok = password_verify($password, $user['password']);
    }
    if ($ok) {

        // Record success attempt
        throttle_record($conn, $identifier, $ip, true);

        if ((int)$user['twofa_enabled'] === 1) {
            // Step 1 passed: create OTP and redirect to 2FA page
            $code = otp_generate_and_store($conn, (int)$user['user_id']);

            // DEMO: show code on next page (in production, send via email/SMS)
            $_SESSION['twofa_demo_code'] = $code;
            $_SESSION['pending_2fa_user_id'] = (int)$user['user_id'];

            header("Location: 2fa.php");
            exit();
        } else {
            // No 2FA: finalize login
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['user_id'];
            header("Location: profile.php");
            exit();
        }
    } else {
        // Wrong password
        throttle_record($conn, $identifier, $ip, false);
        echo "<script>alert('Invalid credentials.'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    if ($stmt) { $stmt->close(); }
    throttle_record($conn, $identifier, $ip, false);
    echo "<script>alert('Invalid credentials.'); window.location.href='login.php';</script>";
    exit();
}
?>
