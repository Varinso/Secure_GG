-- Run these SQL statements once (phpMyAdmin or MySQL CLI)

-- 1) Login attempts for throttling
CREATE TABLE IF NOT EXISTS login_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  ip VARCHAR(45) NOT NULL,
  attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  success TINYINT(1) NOT NULL DEFAULT 0,
  INDEX idx_user_time (username, attempted_at),
  INDEX idx_ip_time (ip, attempted_at)
) ENGINE=InnoDB;

-- 2) 2FA flag on users
ALTER TABLE users ADD COLUMN IF NOT EXISTS twofa_enabled TINYINT(1) NOT NULL DEFAULT 1;

-- 3) 2FA code storage
CREATE TABLE IF NOT EXISTS user_2fa_codes (
  user_id INT NOT NULL PRIMARY KEY,
  code_hash VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  attempts TINYINT NOT NULL DEFAULT 0,
  CONSTRAINT fk_user2fa_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
