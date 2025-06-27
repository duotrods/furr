<?php
require_once '../../includes/config.php';
requireAuth();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/products.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/products.php');
    exit();
}

$order_id = $_POST['order_id'];
$reference_number = sanitize($_POST['reference_number']);

// Validate order
$order = getOrderById($order_id);
if (!$order || $order['user_id'] != getUserId() || $order['status'] != 'pending') {
    $_SESSION['error_message'] = 'Invalid order.';
    header('Location: ' . BASE_URL . '/public/products.php');
    exit();
}

// Handle file upload
$uploadDir = '../../assets/payments/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        $_SESSION['error_message'] = 'Could not create upload directory.';
        header("Location: " . BASE_URL . "/public/payment-upload.php?id=" . $order_id);
        exit();
    }
}

$fileName = 'payment_' . $order_id . '_' . time() . '_' . basename($_FILES['payment_proof']['name']);
$targetFilePath = $uploadDir . $fileName;

// Validate file
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
$fileType = $_FILES['payment_proof']['type'];
if (!in_array($fileType, $allowedTypes)) {
    $_SESSION['error_message'] = 'Only JPG, PNG, GIF, and PDF files are allowed.';
    header("Location: " . BASE_URL . "/public/payment-upload.php?id=" . $order_id);
    exit();
}

if ($_FILES['payment_proof']['size'] > 5 * 1024 * 1024) { // 5MB max
    $_SESSION['error_message'] = 'File size must be less than 5MB.';
    header("Location: " . BASE_URL . "/public/payment-upload.php?id=" . $order_id);
    exit();
}

// Upload file
if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetFilePath)) {
    $_SESSION['error_message'] = 'File upload failed. Please try again.';
    header("Location: " . BASE_URL . "/public/payment-upload.php?id=" . $order_id);
    exit();
}

// Store relative path in database
$relativePath = 'assets/payments/' . $fileName;

// Update order
try {
   $stmt = $pdo->prepare("UPDATE orders SET 
                      status = 'payment_review',
                      payment_reference = ?,
                      payment_proof = ?
                      WHERE id = ?");
                      
    if (!$stmt->execute([$reference_number, $relativePath, $order_id])) {
        throw new Exception("Database update failed");
    }
    
    // Clear the session variable
    unset($_SESSION['order_id_for_payment']);
    
    // Send notification email to admin
    $subject = "New Payment Received for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
    $body = "A new payment has been submitted for order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . ".<br><br>
            Amount: ₱" . number_format($order['total_amount'], 2) . "<br>
            Reference Number: " . $reference_number . "<br><br>
            Please review the payment proof in the admin panel.";
    
    // sendEmail(ADMIN_EMAIL, $subject, $body);
    
    // Redirect to success page
    $_SESSION['success_message'] = 'Payment submitted successfully! We will review your payment and update your order status soon.';
    header('Location: ' . BASE_URL . '/public/order-details.php?id=' . $order_id);
    exit();
    
} catch (Exception $e) {
    // Delete the uploaded file if database update failed
    if (file_exists($targetFilePath)) {
        unlink($targetFilePath);
    }
    
    $_SESSION['error_message'] = 'An error occurred while processing your payment. Please try again.';
    header("Location: " . BASE_URL . "/public/payment-upload.php?id=" . $order_id);
    exit();
}