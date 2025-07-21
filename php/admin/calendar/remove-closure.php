<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    exit('Method not allowed');
}

if (empty($_GET['id'])) {
    http_response_code(400);
    exit('ID parameter is required');
}

try {
    $stmt = $pdo->prepare("DELETE FROM store_closures WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Closure not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}