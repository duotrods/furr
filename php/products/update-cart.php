<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/cart.php');
    exit();
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

// Validate product
$product = getProductById($product_id);
if (!$product) {
    $_SESSION['error_message'] = 'Invalid product.';
    header('Location: ' . BASE_URL . '/public/cart.php');
    exit();
}

// Check stock
if ($quantity <= 0 || $quantity > $product['stock']) {
    $_SESSION['error_message'] = 'Invalid quantity or insufficient stock.';
    header('Location: ' . BASE_URL . '/public/cart.php');
    exit();
}

// Update cart
updateCartQuantity($product_id, $quantity);

$_SESSION['success_message'] = 'Cart updated successfully!';
header('Location: ' . BASE_URL . '/public/cart.php');
exit();
?>