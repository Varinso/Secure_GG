<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body class="container" style="max-width:600px;margin-top:40px;">
  <h3>Forgot Password</h3>
  <p>Please enter your <strong>username</strong>. We will send a reset link to the email on your account.</p>

  <form method="POST" action="process_forgot.php" class="form">
    <?php echo csrf_input(); ?>
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <button class="btn btn-primary">Send reset link</button>
  </form>

  <p style="margin-top:10px;"><a href="login.php" class="btn btn-default">Back to Login</a></p>
</body>
</html>
