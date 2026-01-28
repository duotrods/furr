<?php
require_once '../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}

$product_id = (int) $_POST['product_id'];
$name = sanitize($_POST['name']);
$category_id = (int) $_POST['category_id'];
$description = sanitize($_POST['description']);
$price = (float) $_POST['price'];
$stock = (int) $_POST['stock'];

// Validate inputs
$errors = [];

if (empty($name)) {
    $errors[] = 'Product name is required.';
}

if (empty($category_id)) {
    $errors[] = 'Category is required.';
}

if (empty($price) || $price <= 0) {
    $errors[] = 'Valid price is required.';
}

if ($stock < 0) {
    $errors[] = 'Stock quantity cannot be negative.';
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: ' . BASE_URL . '/admin/edit-product.php?id=' . $product_id);
    exit();
}

// Get current product data
$product = getProductById($product_id);
if (!$product) {
    $_SESSION['error_message'] = 'Product not found.';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
}

// Handle image upload if provided
$image = $product['image'];
if (!empty($_FILES['image']['name'])) {
    $upload_error = null;
    $new_image = uploadProductImage($_FILES['image'], $upload_error);
    if ($new_image) {
        // Delete old image if it exists
        if ($image) {
            @unlink("../../assets/uploads/$image");
        }
        $image = $new_image;
    }
}

// Update product
try {
    $stmt = $pdo->prepare("UPDATE products 
                          SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, image = ?
                          WHERE id = ?");
    $stmt->execute([$category_id, $name, $description, $price, $stock, $image, $product_id]);

    $_SESSION['success_message'] = 'Product updated successfully!';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to update product. Please try again.';
    header('Location: ' . BASE_URL . '/admin/edit-product.php?id=' . $product_id);
    exit();
}
?>