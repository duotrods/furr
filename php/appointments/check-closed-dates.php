<?php
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT closure_date FROM store_closures WHERE closure_date >= CURDATE()");
    $closedDates = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    echo json_encode($closedDates);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([]);
}