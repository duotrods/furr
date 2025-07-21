<?php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

if (empty($_GET['date'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Date parameter is required']));
}

try {
    $stmt = $pdo->prepare("SELECT id FROM store_closures WHERE closure_date = ?");
    $stmt->execute([$_GET['date']]);
    $closure = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'isClosure' => $closure !== false,
        'closureId' => $closure ? $closure['id'] : null
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}