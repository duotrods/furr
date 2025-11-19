<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Log all received data for debugging
error_log("=== CLOSURE FORM DEBUG ===");
error_log("POST data: " . print_r($_POST, true));
error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Invalid request method");
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit();
}

// Get and validate form data
$closureDate = $_POST['closureDate'] ?? '';
$closureType = $_POST['closureType'] ?? 'full_day';
$closureReason = $_POST['closureReason'] ?? '';
$startTime = $_POST['startTime'] ?? null;
$endTime = $_POST['endTime'] ?? null;

error_log("Parsed data:");
error_log("closureDate: " . $closureDate);
error_log("closureType: " . $closureType);
error_log("closureReason: " . $closureReason);
error_log("startTime: " . $startTime);
error_log("endTime: " . $endTime);

// Validate required fields
if (empty($closureDate)) {
    error_log("Date validation failed - empty");
    http_response_code(400);
    echo json_encode(['error' => 'Date is required']);
    exit();
}

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $closureDate)) {
    error_log("Date validation failed - invalid format: " . $closureDate);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format']);
    exit();
}

try {
    // Check if closure already exists
    $stmt = $pdo->prepare("SELECT id FROM store_closures WHERE closure_date = ?");
    $stmt->execute([$closureDate]);
    if ($stmt->fetch()) {
        error_log("Closure already exists for date: " . $closureDate);
        http_response_code(400);
        echo json_encode(['error' => 'A closure already exists for this date']);
        exit();
    }

    // Insert the closure
    if ($closureType === 'full_day') {
        $stmt = $pdo->prepare("
            INSERT INTO store_closures (closure_date, closure_type, reason, created_at) 
            VALUES (?, 'full_day', ?, NOW())
        ");
        $result = $stmt->execute([$closureDate, $closureReason]);
    } else {
        // Validate partial closure times
        if (empty($startTime) || empty($endTime)) {
            error_log("Partial closure validation failed - missing times");
            http_response_code(400);
            echo json_encode(['error' => 'Start time and end time are required for partial closures']);
            exit();
        }

        $stmt = $pdo->prepare("
            INSERT INTO store_closures (closure_date, closure_type, start_time, end_time, reason, created_at) 
            VALUES (?, 'partial', ?, ?, ?, NOW())
        ");
        $result = $stmt->execute([$closureDate, $startTime, $endTime, $closureReason]);
    }

    if ($result && $stmt->rowCount() > 0) {
        error_log("Closure added successfully");
        echo json_encode([
            'success' => true,
            'message' => 'Store closure added successfully',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        error_log("Database insertion failed");
        throw new Exception('Failed to save closure to database');
    }

} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>