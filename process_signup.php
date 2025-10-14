<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php'; // $conn (mysqli)
require_once __DIR__ . '/security/password.php';


csrf_verify();
require_once __DIR__ . '/security/captcha.php';
if (captcha_enabled_for('signup') && !captcha_verify()) { echo "<script>alert('CAPTCHA failed.'); window.history.back();</script>"; exit; }


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signup.php");
    exit();
}

$username = sanitize_str($_POST['username'] ?? '', 50);
$email    = sanitize_str($_POST['email'] ?? '', 100);
$password = (string)($_POST['password'] ?? '');
$confirm  = (string)($_POST['confirm_password'] ?? '');
$phone    = sanitize_str($_POST['phone_number'] ?? '', 15);
$address  = sanitize_str($_POST['address'] ?? '', 250);

$errors = [];
if (!validate_username($username)) $errors[] = "Username must be 3-30 letters/digits/_.";
if (!validate_email($email)) $errors[] = "Invalid email.";
if ($password !== $confirm) $errors[] = "Passwords do not match.";
if (!validate_password_strength($password)) $errors[] = "Weak password: min 8 chars with upper, lower, digit.";

if ($errors) {
    $msg = implode(' ', $errors);
    echo "<script>alert(" . json_encode($msg) . "); window.history.back();</script>";
    exit();
}

// Generate per-user salt and PBKDF2 hash
$salt = pwd_generate_salt(16);
$hashed_password = pwd_hash_password($password, $salt);

// Ensure users table has password_salt column (if not, try to add)
@mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS password_salt VARCHAR(64) DEFAULT NULL");


// Handle file upload 'id_card_photo' securely if present
$id_path = null;
if (!empty($_FILES['id_card_photo']['name'])) {
    $allowed = ['image/jpeg','image/png','image/webp'];
    $f = $_FILES['id_card_photo'];
    if ($f['error'] === UPLOAD_ERR_OK && in_array(mime_content_type($f['tmp_name']), $allowed, true) && $f['size'] <= 2*1024*1024) {
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $safeName = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
        $destDir = __DIR__ . '/uploads';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        $dest = $destDir . '/' . $safeName;
        if (move_uploaded_file($f['tmp_name'], $dest)) {
            $id_path = 'uploads/' . $safeName;
        }
    }
}

// Insert user
$stmt = $conn->prepare("INSERT INTO users (username, email, password, password_salt, phone_number, address, id_card_photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $username, $email, $hashed_password, $salt, $phone, $address, $id_path);
if ($stmt->execute()) {
    echo "<script>alert('Sign-up successful. Please log in.'); window.location.href='login.php';</script>";
    exit();
} else {
    echo "<script>alert('Database error.'); window.history.back();</script>";
}
$stmt->close();
$conn->close();
?>
