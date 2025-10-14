<?php
require_once __DIR__ . '/security/bootstrap.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$masked = $_SESSION['forgot_masked_email'] ?? null;
unset($_SESSION['forgot_masked_email']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Check your email</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body class="container" style="max-width:600px;margin-top:40px;">
  <h3>Check your email</h3>
  <?php if ($masked): ?>
    <p>A password reset link has been sent to your email: <strong><?php echo htmlspecialchars($masked, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <p>Please check your inbox and follow the link to set a new password.</p>
  <?php else: ?>
    <p>We couldn't determine the email address. Please try again.</p>
  <?php endif; ?>
  <p><a href="login.php" class="btn btn-default">Back to Login</a></p>
</body>
</html>
