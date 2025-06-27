<?php
require_once __DIR__ . '/../includes/config.php';
requireAdmin();

header('Content-Type: application/json');

if (!isset($_GET['date']) || !strtotime($_GET['date'])) {
    echo json_encode([]);
    exit();
}

$date = date('Y-m-d', strtotime($_GET['date']));

try {
    $stmt = $pdo->prepare("
        SELECT a.*, 
               u.first_name, u.last_name, u.phone,
               s.name as service_name, s.price as service_price
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN services s ON a.service_id = s.id
        WHERE DATE(a.appointment_date) = :date
        ORDER BY a.appointment_time ASC
    ");
    $stmt->execute([':date' => $date]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($appointments);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([]);
}