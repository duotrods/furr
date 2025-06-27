<?php
require_once __DIR__ . '/../includes/config.php';
requireAdmin();

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT a.id, a.appointment_date, a.appointment_time, a.status, a.notes,
               CONCAT(u.first_name, ' ', u.last_name) as customer,
               s.name as service_name,
               a.pet_name, a.pet_type
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN services s ON a.service_id = s.id
        WHERE a.status IN ('pending', 'confirmed')
        ORDER BY a.appointment_date ASC
    ");
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $events = [];
    foreach ($appointments as $appointment) {
        $events[] = [
            'id' => $appointment['id'],
            'title' => $appointment['service_name'],
            'start' => $appointment['appointment_date'] . 'T' . $appointment['appointment_time'],
            'extendedProps' => [
                'customer' => $appointment['customer'],
                'pet_name' => $appointment['pet_name'],
                'pet_type' => $appointment['pet_type'],
                'time' => date('g:i A', strtotime($appointment['appointment_time'])),
                'status' => ucfirst($appointment['status']),
                'notes' => $appointment['notes']
            ],
            'color' => $appointment['status'] == 'confirmed' ? '#10B981' : 
                      ($appointment['status'] == 'pending' ? '#F59E0B' : '#EF4444')
        ];
    }
    
    echo json_encode($events);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([]);
}