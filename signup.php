<?php require_once __DIR__ . '/security/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="form-container">
        <h2>REGISTER</h2>
        <form action="process_signup.php" method="POST" enctype="multipart/form-data">\n        <?php echo csrf_input(); ?>
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <!-- Retype Password -->
            <div class="form-group">
                <label for="retype-password">Retype Password</label>
                <input type="password" id="retype-password" name="confirm_password" class="form-control" placeholder="Retype Password" required>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Phone Number" required>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" placeholder="Your Address" rows="3" required></textarea>
            </div>

            <!-- ID Card Photo -->
            <div class="form-group">
                <label for="id-card-photo">Upload ID Card Photo</label>
                <input type="file" id="id-card-photo" name="id_card_photo" class="form-control" accept="image/*" required>
            </div>

            <!-- Terms and Policies -->
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="terms" required> Accept the terms and policies
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button><?php
    require_once __DIR__ . '/security/captcha.php';
    $__show_captcha = captcha_enabled_for('signup');
    $__captcha_q = $__show_captcha ? captcha_generate() : '';
    ?>
    <?php if ($__show_captcha): ?>
    <div class="form-group"><label><?php echo $__captcha_q; ?></label><input type="number" name="captcha_answer" class="form-control" required></div>
    <?php endif; ?>

</form>

        <!-- Redirect to Login -->
        <div class="text-center" style="margin-top: 10px;">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>
</body>

</html>