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

    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');

    // Validate input
    if ($id <= 0 || empty($name)) {
        $_SESSION['error'] = 'Invalid category data';
        redirect(BASE_URL . '/admin/manage-categories.php');
        exit();
    }

    try {
        // Check if category exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_categories WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() == 0) {
            $_SESSION['error'] = 'Category not found';
            redirect(BASE_URL . '/admin/manage-categories.php');
            exit();
        }

        // Check if new name already exists for a different category
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_categories WHERE name = ? AND id != ?");
        $stmt->execute([$name, $id]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Category name already exists';
            redirect(BASE_URL . '/admin/manage-categories.php');
            exit();
        }

        // Update category
        $stmt = $pdo->prepare("UPDATE product_categories SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);

        $_SESSION['success'] = 'Category updated successfully';
        redirect(BASE_URL . '/admin/manage-categories.php');
    } catch (PDOException $e) {
        error_log("Error updating category: " . $e->getMessage());
        $_SESSION['error'] = 'Failed to update category';
        redirect(BASE_URL . '/admin/manage-categories.php');
    }
} else {
    redirect(BASE_URL . '/admin/manage-categories.php');
}
