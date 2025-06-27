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

$token = $_POST['token'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate inputs
if (empty($token) || empty($password) || empty($confirm_password)) {
    $_SESSION['error_message'] = 'All fields are required.';
    header('Location: ' . BASE_URL . "/public/reset-password.php?token=$token");
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['error_message'] = 'Passwords do not match.';
    header('Location: ' . BASE_URL . "/public/reset-password.php?token=$token");
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['error_message'] = 'Password must be at least 8 characters long.';
    header('Location: ' . BASE_URL . "/public/reset-password.php?token=$token");
    exit();
}

// Check if token is valid
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error_message'] = 'Invalid or expired password reset link.';
    header('Location: ' . BASE_URL . '/public/forgot-password.php');
    exit();
}

// Update password
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
$stmt->execute([$password_hash, $user['id']]);

// Send confirmation email
$subject = "Password Updated";
$body = "Hello {$user['first_name']},<br><br>
        Your password has been successfully updated. If you did not make this change, please contact us immediately.<br><br>
        Best regards,<br>
        The FurCare Team";

sendEmail($user['email'], $subject, $body);

$_SESSION['success_message'] = 'Your password has been reset successfully. You can now login with your new password.';
header('Location: ' . BASE_URL . '/public/login.php');
exit();
?>