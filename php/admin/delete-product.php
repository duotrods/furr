<?php
require_once '../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}

$product_id = (int)$_POST['product_id'];

// Get product data
$product = getProductById($product_id);
if (!$product) {
    $_SESSION['error_message'] = 'Product not found.';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}

// Check if product has orders
$stmt = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE product_id = ?");
$stmt->execute([$product_id]);
$order_count = $stmt->fetchColumn();

if ($order_count > 0) {
    $_SESSION['error_message'] = 'Cannot delete product with existing orders.';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}

// Delete product
try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    // Delete product image if it exists
    if ($product['image']) {
        @unlink("../../assets/uploads/{$product['image']}");
    }
    
    $_SESSION['success_message'] = 'Product deleted successfully.';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to delete product. Please try again.';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}
?>