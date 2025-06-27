<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/register.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/register.php');
    exit();
}

// Sanitize inputs
$first_name = sanitize($_POST['first_name']);
$last_name = sanitize($_POST['last_name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['phone']);
$address = sanitize($_POST['address']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate inputs
$errors = [];

if (empty($first_name)) {
    $errors[] = 'First name is required.';
}

if (empty($last_name)) {
    $errors[] = 'Last name is required.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email address is required.';
}

if (empty($password)) {
    $errors[] = 'Password is required.';
} elseif (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters long.';
} elseif ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
}

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $errors[] = 'Email address is already registered.';
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: ' . BASE_URL . '/public/register.php');
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Generate verification code
$verification_code = bin2hex(random_bytes(16));

// Insert user into database
try {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, address, password, verification_code, is_verified) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$first_name, $last_name, $email, $phone, $address, $password_hash, $verification_code]);
    
    // Send verification email
    $verification_url = BASE_URL . "/public/verify-email.php?code=$verification_code";
    $subject = "Verify Your Email Address";
    $body = "Hello $first_name,<br><br>
            Thank you for registering with FurCare Pet Grooming. Please click the link below to verify your email address:<br><br>
            <a href='$verification_url'>$verification_url</a><br><br>
            If you did not create an account, no further action is required.<br><br>
            Best regards,<br>
            The FurCare Team";
    
    if (sendEmail($email, $subject, $body)) {
        $_SESSION['success_message'] = 'Registration successful! Please check your email to verify your account.';
    } else {
        $_SESSION['error_message'] = 'Registration successful, but we couldn\'t send the verification email. Please contact support.';
    }
    
    header('Location: ' . BASE_URL . '/public/login.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Registration failed. Please try again.';
    header('Location: ' . BASE_URL . '/public/register.php');
    exit();
}
?>