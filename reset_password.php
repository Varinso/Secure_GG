<?php
require_once __DIR__ . '/security/bootstrap.php';
require_once __DIR__ . '/db.php';
$selector = $_GET['selector'] ?? '';
$token = $_GET['token'] ?? ''; ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body class="container" style="max-width:600px;margin-top:40px;">
    <h3>Reset Password</h3>
    <form method="POST" action="process_reset.php" class="form">
        <?php echo csrf_input(); ?>
        <input type="hidden" name="selector" value="<?php echo e($selector); ?>">
        <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <div class="form-group"><label>New password</label><input type="password" name="password" class="form-control" required minlength="8"></div>
        <div class="form-group"><label>Confirm password</label><input type="password" name="password2" class="form-control" required minlength="8"></div>
        <button class="btn btn-primary">Set new password</button>
    </form>
</body>

</html>