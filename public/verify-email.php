<?php
require_once '../includes/config.php';

// Check if verification code exists in URL
if (!isset($_GET['code']) || empty($_GET['code'])) {
    $_SESSION['error_message'] = 'Invalid verification link.';
    header('Location: ' . BASE_URL . '/public/register.php');
    exit();
}

$verification_code = sanitize($_GET['code']);

try {
    // Check if verification code exists in database
    $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_code = ? AND is_verified = 0");
    $stmt->execute([$verification_code]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Mark user as verified
        $update_stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_code = NULL WHERE id = ?");
        $update_stmt->execute([$user['id']]);
        
        $_SESSION['success_message'] = 'Email verification successful! You can now log in.';
        header('Location: ' . BASE_URL . '/public/login.php');
    } else {
        $_SESSION['error_message'] = 'Invalid or expired verification link.';
        header('Location: ' . BASE_URL . '/public/register.php');
    }
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Verification failed. Please try again.';
    header('Location: ' . BASE_URL . '/public/register.php');
    exit();
}
?>

