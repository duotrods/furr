<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session management
session_start();

// Base URL
define('BASE_URL', 'http://localhost/furr');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'furcare');

// Email configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'furcaremis@gmail.com');
define('MAIL_PASSWORD', 'hfia mull chxz drpc');
define('MAIL_PORT', 587);
define('MAIL_FROM', 'furcaremis@gmail.com');
define('MAIL_FROM_NAME', 'FurCare Pet Grooming');

// Timezone
date_default_timezone_set('Asia/Manila');

// Include other core files
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

function getOrderById($order_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserById($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>