-- Add is_approved field to users table for admin verification
ALTER TABLE users
ADD COLUMN is_approved TINYINT(1) NOT NULL DEFAULT 0 AFTER is_verified;

-- Optional: Set existing users as approved (so they're not locked out)
UPDATE users SET is_approved = 1 WHERE is_verified = 1;
