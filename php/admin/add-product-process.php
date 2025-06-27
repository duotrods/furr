<?php
require_once '../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/add-product.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/admin/add-product.php');
    exit();
}

// Sanitize inputs
$name = sanitize($_POST['name']);
$category_id = (int)$_POST['category_id'];
$description = sanitize($_POST['description']);
$price = (float)$_POST['price'];
$stock = (int)$_POST['stock'];

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
    header('Location: ' . BASE_URL . '/admin/add-product.php');
    exit();
}

// Handle image upload
$image = null;
if (!empty($_FILES['image']['name'])) {
    $image = uploadProductImage($_FILES['image']);
    if (!$image) {
        $_SESSION['error_message'] = 'Failed to upload product image.';
        header('Location: ' . BASE_URL . '/admin/add-product.php');
        exit();
    }
}

// Insert product
try {
    $stmt = $pdo->prepare("INSERT INTO products 
                          (category_id, name, description, price, stock, image) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $description, $price, $stock, $image]);
    
    $_SESSION['success_message'] = 'Product added successfully!';
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit();
} catch (PDOException $e) {
    // Delete uploaded image if insertion failed
    if ($image) {
        @unlink("../../assets/uploads/$image");
    }
    
    $_SESSION['error_message'] = 'Failed to add product. Please try again.';
    header('Location: ' . BASE_URL . '/admin/add-product.php');
    exit();
}
?>