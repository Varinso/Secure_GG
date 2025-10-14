# Security Add-ons (First 6 Measures)

This package contains drop-in files for your project to implement:

1. Input Validation & Sanitization
2. Prepared Statements (kept / improved)
3. CSRF Tokens
4. Session Management hardening
5. Authentication Throttling
6. Two-Factor Authentication (2FA, OTP-based)

## What's Included

- `security/bootstrap.php` – starts a hardened session, auto-loads CSRF + validation helpers.
- `security/csrf.php` – CSRF token helpers (`csrf_input()`, `csrf_verify()`).
- `security/validation.php` – input validators and output escaping (`e()`).
- `security/throttle.php` – simple login throttling using MySQL table `login_attempts`.
- `security/otp.php` – 2FA via one-time 6‑digit codes stored server-side.
- `login.php` – patched to include CSRF field.
- `signup.php` – patched to include CSRF field.
- `process_login.php` – updated to enforce CSRF, throttling, and 2FA step.
- `process_signup.php` – updated to enforce CSRF and stronger validation.
- `process_2fa.php`, `2fa.php` – 2FA verification step.
- `SECURITY_MIGRATIONS.sql` – run-once SQL changes.

Your existing `db.php` is copied here unchanged.

## Step-by-step (beginner friendly)

1. **Backup your project** (copy your current folder).
2. **Unzip** this package into your project root (same folder that contains `login.php`, `signup.php`, etc.).
   - It will create a new `security/` folder and **replace** your `login.php`, `signup.php`, and the two `process_*` files.
3. **Create the new tables** (and column) in MySQL:
   - Open phpMyAdmin → select your database **GG** → go to **SQL** tab.
   - Paste the content of `SECURITY_MIGRATIONS.sql` and **Run**.
4. **Check file permissions**:
   - If you use ID card uploads on signup, ensure there's a writable `uploads/` folder in the root (`chmod 755` is fine).
5. **Local HTTPS (recommended):**
   - If you don't have HTTPS locally, it's fine for testing. Cookies are still set to `secure` when HTTPS is detected.
6. **Test the flow**:
   - Go to `signup.php` → create a test user.
   - Go to `login.php` → sign in.
   - After correct password, you'll be redirected to **2FA** page. For demo, the 6‑digit code is shown in a blue box. Enter it.
   - On success, you are redirected to `profile.php`.
7. **What changed in the code?**
   - **CSRF:** Every POST form now has a hidden CSRF token; server checks it.
   - **Validation:** Server sanitizes strings, enforces email/username/password rules.
   - **Prepared Statements:** All sensitive DB operations (login/signup) use prepared statements.
   - **Session:** Cookies are HttpOnly + SameSite=Strict, session IDs regenerate automatically.
   - **Throttling:** More than 5 failed logins in 15 minutes (same user/IP) blocks further attempts temporarily.
   - **2FA:** After password, users must enter a 6‑digit code (valid for 5 minutes). In production, send via email/SMS.

## Apply to other forms (optional but recommended)
- Add at the **top** of any PHP page that processes POST data:
  ```php
  <?php require_once __DIR__ . '/security/bootstrap.php'; ?>
  ```
- Add `<?php echo csrf_input(); ?>` **inside every `<form method="POST">`**.
- When reading inputs, wrap with `sanitize_str(...)` and validate using the helpers.

## Rollback
- Restore your backup, or just delete the `security/` folder and restore the four replaced files.
