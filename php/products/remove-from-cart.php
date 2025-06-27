<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/cart.php');
    exit();
}

$product_id = (int)$_POST['product_id'];

// Remove from cart
removeFromCart($product_id);

$_SESSION['success_message'] = 'Item removed from cart.';
header('Location: ' . BASE_URL . '/public/cart.php');
exit();
?>