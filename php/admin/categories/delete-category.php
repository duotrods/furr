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

    // Validate input
    if ($id <= 0) {
        $_SESSION['error'] = 'Invalid category ID';
        redirect(BASE_URL . '/admin/manage-categories.php');
        exit();
    }

    try {
        // Check if category has products
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Cannot delete category with existing products';
            redirect(BASE_URL . '/admin/manage-categories.php');
            exit();
        }

        // Delete category
        $stmt = $pdo->prepare("DELETE FROM product_categories WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success'] = 'Category deleted successfully';
        redirect(BASE_URL . '/admin/manage-categories.php');
    } catch (PDOException $e) {
        error_log("Error deleting category: " . $e->getMessage());
        $_SESSION['error'] = 'Failed to delete category';
        redirect(BASE_URL . '/admin/manage-categories.php');
    }
} else {
    redirect(BASE_URL . '/admin/manage-categories.php');
}
