<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $closureId = $input['id'] ?? null;

    if (empty($closureId)) {
        throw new Exception('Closure ID is required');
    }

    $stmt = $pdo->prepare("DELETE FROM store_closures WHERE id = ?");
    $result = $stmt->execute([$closureId]);

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Store closure removed successfully']);
    } else {
        throw new Exception('Closure not found or already removed');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>