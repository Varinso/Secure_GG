<?php
require_once __DIR__ . '/security/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body class="container" style="max-width:600px;margin-top:40px;">
    <h2>Enter the 6‑digit code</h2>
    <?php if (!empty($_SESSION['twofa_demo_code'])): ?>
        
        <?php unset($_SESSION['twofa_demo_code']); ?>
    <?php endif; ?>
    <form action="process_2fa.php" method="POST">
        <?php echo csrf_input(); ?>
        <div class="form-group">
            <label for="code">6‑digit code</label>
            <input class="form-control" id="code" name="code" pattern="\d{6}" maxlength="6" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
    </form>
</body>
</html>
