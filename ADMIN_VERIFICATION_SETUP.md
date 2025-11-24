# Admin Verification for User Registration

This document explains the admin verification system that has been implemented to prevent dummy account registrations.

## Overview

The system now requires admin approval for all new user registrations. The flow is:

1. User registers → Email verification → Admin approval → User can log in

## Database Changes Required

**IMPORTANT:** You need to run the SQL migration to add the `is_approved` field to the users table.

### Step 1: Run the Migration

Execute the following SQL script on your database:

```sql
-- Add is_approved field to users table for admin verification
ALTER TABLE users
ADD COLUMN is_approved TINYINT(1) NOT NULL DEFAULT 0 AFTER is_verified;

-- Optional: Set existing users as approved (so they're not locked out)
UPDATE users SET is_approved = 1 WHERE is_verified = 1;
```

You can either:
- Run this SQL directly in phpMyAdmin or your database management tool
- Execute the migration file: `/database/migrations/add_is_approved_field.sql`

### Step 2: Verify the Change

After running the migration, verify that the `is_approved` column exists:

```sql
DESCRIBE users;
```

You should see a new column `is_approved` of type `TINYINT(1)`.

## Features Implemented

### 1. Registration Process (`/php/auth/register-process.php`)
- New users are created with `is_approved = 0`
- Success message informs users about admin approval requirement

### 2. Login Process (`/php/auth/login-process.php`)
- Users must have `is_approved = 1` to log in
- Users with pending approval receive a clear error message

### 3. Admin Management Page (`/admin/manage-registrations.php`)
- View all pending registrations
- View recently approved users
- Dashboard with counts of pending and approved users
- Email verification status for each user

### 4. Approve/Reject Functionality (`/php/admin/process-registration.php`)
- **Approve**: Sets `is_approved = 1` and sends approval email to user
- **Reject**: Deletes the user account and sends rejection email

### 5. Email Notifications
- **Approval Email**: Sent when admin approves a registration
- **Rejection Email**: Sent when admin rejects a registration (optional)

### 6. Navigation
- New "Users" menu item in admin navigation
- Red badge showing count of pending registrations
- Available in both desktop and mobile navigation

## How to Use

### For Admins:

1. Log in to the admin panel
2. Click on "Users" in the navigation menu
3. Review pending registrations in the table
4. Check if email is verified (green badge = verified)
5. Click "Approve" to allow the user to log in, or "Reject" to delete the account
6. User will receive an email notification of your decision

### For Users:

1. Register on the website
2. Verify email address via the link sent
3. Wait for admin approval
4. Receive email notification when approved
5. Log in to the website

## Security Considerations

1. **Email Verification Still Required**: Users must verify their email before admin can approve them
2. **Admin Authentication**: Only logged-in admins can access the management page
3. **CSRF Protection**: All approve/reject actions are protected with CSRF tokens
4. **Audit Trail**: All registrations show registration date and user information

## Troubleshooting

### Issue: Existing users can't log in
**Solution**: Make sure you ran the second part of the migration:
```sql
UPDATE users SET is_approved = 1 WHERE is_verified = 1;
```

### Issue: "Users" link not showing in navigation
**Solution**: Clear your browser cache and refresh the page

### Issue: Emails not being sent
**Solution**: Check your SMTP configuration in `/includes/config.php`

### Issue: SQL error when accessing manage-registrations.php
**Solution**: The `is_approved` field hasn't been added to the database. Run the migration.

## Files Modified/Created

### Modified Files:
- `/php/auth/register-process.php` - Added is_approved field to registration
- `/php/auth/login-process.php` - Added approval check to login
- `/includes/nav.php` - Added Users navigation link

### Created Files:
- `/database/migrations/add_is_approved_field.sql` - Database migration
- `/admin/manage-registrations.php` - Admin management interface
- `/php/admin/process-registration.php` - Approve/reject processing
- `/ADMIN_VERIFICATION_SETUP.md` - This documentation

## Future Enhancements

Consider adding:
- Email notification to admin when new user registers
- Ability to suspend/unsuspend approved users
- Bulk approve/reject functionality
- User registration analytics and reporting
- Custom rejection reason field
