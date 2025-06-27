<?php
require_once '../../includes/config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/checkout.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/checkout.php');
    exit();
}

// Sanitize inputs
$first_name = sanitize($_POST['first_name']);
$last_name = sanitize($_POST['last_name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['phone']);
$shipping_address = sanitize($_POST['shipping_address']);
$payment_method = sanitize($_POST['payment_method']);

// Validate inputs
$errors = [];

if (empty($first_name) || empty($last_name)) {
    $errors[] = 'First and last name are required.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email address is required.';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required.';
}

if (empty($shipping_address)) {
    $errors[] = 'Shipping address is required.';
}

if (empty($payment_method)) {
    $errors[] = 'Payment method is required.';
}

// Validate cart
$cartItems = getCartItems();
if (empty($cartItems)) {
    $errors[] = 'Your cart is empty.';
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: ' . BASE_URL . '/public/checkout.php');
    exit();
}

// Check product stock
foreach ($cartItems as $product_id => $quantity) {
    $product = getProductById($product_id);
    if (!$product || $product['stock'] < $quantity) {
        $_SESSION['error_message'] = "Insufficient stock for {$product['name']}.";
        header('Location: ' . BASE_URL . '/public/cart.php');
        exit();
    }
}

// Calculate total
$subtotal = calculateCartTotal();
$shipping_fee = 50.00; // Fixed shipping fee
$total = $subtotal + $shipping_fee;

// Create order
try {
    $pdo->beginTransaction();

    // Insert order
    $stmt = $pdo->prepare("INSERT INTO orders 
                          (user_id, total_amount, status, payment_method, shipping_address, contact_number) 
                          VALUES (?, ?, 'pending', ?, ?, ?)");
    $stmt->execute([getUserId(), $total, $payment_method, $shipping_address, $phone]);
    $order_id = $pdo->lastInsertId();

    // Insert order items and update stock
    foreach ($cartItems as $product_id => $quantity) {
        $product = getProductById($product_id);

        // Insert order item
        $stmt = $pdo->prepare("INSERT INTO order_items 
                              (order_id, product_id, quantity, price) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);

        // Update product stock
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
    }

    $pdo->commit();

    // Instead of clearing cart and sending email here, redirect to payment upload
    $_SESSION['order_id_for_payment'] = $order_id;
    header('Location: ' . BASE_URL . '/public/payment-upload.php?id=' . $order_id);
    exit();

    // // Clear cart
    // unset($_SESSION['cart']);

    // // Send confirmation email
    // $user = getUser();
    // $subject = "Order Confirmation #" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
    // $body = "Hello {$user['first_name']},<br><br>
    //         Thank you for your order! Here are your order details:<br><br>
    //         <strong>Order ID:</strong> #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . "<br>
    //         <strong>Order Date:</strong> " . date('F j, Y') . "<br>
    //         <strong>Total Amount:</strong> ₱" . number_format($total, 2) . "<br>
    //         <strong>Payment Method:</strong> " . ucfirst(str_replace('_', ' ', $payment_method)) . "<br><br>
    //         <strong>Shipping Address:</strong><br>
    //         {$shipping_address}<br><br>
    //         We'll process your order and notify you once it's shipped.<br><br>
    //         Best regards,<br>
    //         The FurCare Team";

    // sendEmail($email, $subject, $body);

    // $_SESSION['success_message'] = 'Order placed successfully! Your order ID is #' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
    // header('Location: ' . BASE_URL . '/public/order-confirmation.php?id=' . $order_id);
    // exit();
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = 'Failed to place order. Please try again.';
    header('Location: ' . BASE_URL . '/public/checkout.php');
    exit();
}
