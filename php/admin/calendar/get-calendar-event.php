<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

header('Content-Type: application/json');

try {
    // Get appointments (your existing query)
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

    // Get store closures (new query)
    $stmt = $pdo->query("
        SELECT id, closure_date as date, reason 
        FROM store_closures 
        WHERE closure_date >= CURDATE()
    ");
    $closures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];

    // Add appointments (your existing format)
    foreach ($appointments as $appointment) {
        $events[] = [
            'id' => 'appt_' . $appointment['id'],
            'title' => $appointment['service_name'],
            'start' => $appointment['appointment_date'] . 'T' . $appointment['appointment_time'],
            'extendedProps' => [
                'type' => 'appointment',
                'id' => $appointment['id'],
                'customer' => $appointment['customer'],
                'pet_name' => $appointment['pet_name'],
                'pet_type' => $appointment['pet_type'],
                'time' => date('g:i A', strtotime($appointment['appointment_time'])),
                'status' => $appointment['status'], // Keep as lowercase for consistency
                'notes' => $appointment['notes']
            ],
            'color' => $appointment['status'] == 'confirmed' ? '#10B981' :
                ($appointment['status'] == 'pending' ? '#F59E0B' : '#EF4444'),
            'textColor' => '#ffffff'
        ];
    }

    // Add store closures (new format)
    foreach ($closures as $closure) {
        $events[] = [
            'id' => 'closure_' . $closure['id'],
            'title' => 'STORE CLOSED',
            'start' => $closure['date'],
            'allDay' => true,
            'color' => '#6b7280',
            'textColor' => '#ffffff',
            'extendedProps' => [
                'type' => 'closure',
                'reason' => $closure['reason']
            ]
        ];
    }

    echo json_encode($events);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([]);
}