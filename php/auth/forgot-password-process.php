<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/forgot-password.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/forgot-password.php');
    exit();
}

$email = sanitize($_POST['email']);

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // Generate reset token
    $reset_token = bin2hex(random_bytes(32));
    $reset_token_expires = date('Y-m-d H:i:s', time() + 60 * 60); // 1 hour expiration
    
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
    $stmt->execute([$reset_token, $reset_token_expires, $user['id']]);
    
    // Send reset email
    $reset_url = BASE_URL . "/public/reset-password.php?token=$reset_token";
    $subject = "Password Reset Request";
    $body = "Hello {$user['first_name']},<br><br>
            You have requested to reset your password. Please click the link below to reset your password:<br><br>
            <a href='$reset_url'>$reset_url</a><br><br>
            This link will expire in 1 hour. If you did not request a password reset, please ignore this email.<br><br>
            Best regards,<br>
            The FurCare Team";
    
    if (sendEmail($email, $subject, $body)) {
        $_SESSION['success_message'] = 'Password reset link has been sent to your email.';
    } else {
        $_SESSION['error_message'] = 'Failed to send reset email. Please try again.';
    }
} else {
    $_SESSION['error_message'] = 'No account found with that email address.';
}

header('Location: ' . BASE_URL . '/public/forgot-password.php');
exit();
?>