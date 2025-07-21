<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Log the raw input for debugging
file_put_contents('closure_debug.log', print_r(file_get_contents('php://input'), true), FILE_APPEND);

// Get and decode JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit;
}

// Validate required fields
if (empty($data['date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Date is required']);
    exit;
}

try {
    // Log database connection status
    file_put_contents('closure_debug.log', "\nDB Connection: " . ($pdo ? "OK" : "FAILED"), FILE_APPEND);

    $stmt = $pdo->prepare("INSERT INTO store_closures (closure_date, reason) VALUES (?, ?)");
    $result = $stmt->execute([$data['date'], $data['reason'] ?? null]);

    // Log the execution result
    file_put_contents('closure_debug.log', "\nInsert Result: " . ($result ? "Success" : "Failed"), FILE_APPEND);
    file_put_contents('closure_debug.log', "\nLast Insert ID: " . $pdo->lastInsertId(), FILE_APPEND);
    file_put_contents('closure_debug.log', "\nRow Count: " . $stmt->rowCount(), FILE_APPEND);

    if ($result) {
        echo json_encode([
            'success' => true,
            'id' => $pdo->lastInsertId(),
            'date' => $data['date'],
            'reason' => $data['reason'] ?? null
        ]);
    } else {
        throw new Exception('Failed to insert into database');
    }
} catch (PDOException $e) {
    file_put_contents('closure_debug.log', "\nPDO Error: " . $e->getMessage(), FILE_APPEND);
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    file_put_contents('closure_debug.log', "\nError: " . $e->getMessage(), FILE_APPEND);
    http_response_code(500);
    echo json_encode([
        'error' => 'Operation failed',
        'message' => $e->getMessage()
    ]);
}