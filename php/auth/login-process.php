<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

// Sanitize inputs
$email = sanitize($_POST['email']);
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

// Validate inputs
if (empty($email) || empty($password)) {
    $_SESSION['error_message'] = 'Email and password are required.';
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error_message'] = 'Invalid email or password.';
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

// Check if account is verified
if (!$user['is_verified']) {
    $_SESSION['error_message'] = 'Please verify your email address before logging in.';
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

// Check if account is approved by admin
if (!$user['is_approved']) {
    $_SESSION['error_message'] = 'Your account is pending admin approval. You will receive an email once your account is approved.';
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
}

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['is_admin'] = $user['is_admin'];

// Set remember me cookie if selected
if ($remember) {
    $token = bin2hex(random_bytes(32));
    $expiry = time() + 60 * 60 * 24 * 30; // 30 days
    
    $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, remember_token_expires = ? WHERE id = ?");
    $stmt->execute([$token, date('Y-m-d H:i:s', $expiry), $user['id']]);
    
    setcookie('remember_token', $token, $expiry, '/');
}

// Redirect to intended page or home
// $redirect_url = $_SESSION['redirect_url'] ?? BASE_URL . '/public/index.php';
// unset($_SESSION['redirect_url']);
// header('Location: ' . $redirect_url);
// exit();

// Redirect logic - MODIFIED SECTION
if ($user['is_admin']) {
    $redirect_url = $_SESSION['redirect_url'] ?? BASE_URL . '/admin/index.php';
} else {
    $redirect_url = $_SESSION['redirect_url'] ?? BASE_URL . '/public/index.php';
}

unset($_SESSION['redirect_url']);
header('Location: ' . $redirect_url);
exit();
?>