<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Invalid request';
        redirect(BASE_URL . '/admin/manage-categories.php');
        exit();
    }

    $name = trim($_POST['name'] ?? '');

    // Validate input
    if (empty($name)) {
        $_SESSION['error'] = 'Category name is required';
        redirect(BASE_URL . '/admin/manage-categories.php');
        exit();
    }

    try {
        // Check if category already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_categories WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Category already exists';
            redirect(BASE_URL . '/admin/manage-categories.php');
            exit();
        }

        // Insert new category
        $stmt = $pdo->prepare("INSERT INTO product_categories (name) VALUES (?)");
        $stmt->execute([$name]);

        $_SESSION['success'] = 'Category added successfully';
        redirect(BASE_URL . '/admin/manage-categories.php');
    } catch (PDOException $e) {
        error_log("Error adding category: " . $e->getMessage());
        $_SESSION['error'] = 'Failed to add category';
        redirect(BASE_URL . '/admin/manage-categories.php');
    }
} else {
    redirect(BASE_URL . '/admin/manage-categories.php');
}
