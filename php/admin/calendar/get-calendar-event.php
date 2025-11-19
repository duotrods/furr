<?php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

try {
    // Get appointments
    $stmt = $pdo->query("
        SELECT a.*, s.name as service_name, u.first_name, u.last_name 
        FROM appointments a 
        JOIN services s ON a.service_id = s.id 
        JOIN users u ON a.user_id = u.id 
        WHERE a.appointment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        ORDER BY a.appointment_date, a.appointment_time
    ");
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get store closures
    $stmt = $pdo->query("
        SELECT *, 
               CASE 
                 WHEN closure_type = 'partial' THEN CONCAT('Partial: ', TIME_FORMAT(start_time, '%h:%i %p'), ' - ', TIME_FORMAT(end_time, '%h:%i %p'))
                 ELSE 'Store Closed'
               END as title
        FROM store_closures 
        WHERE closure_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    ");
    $closures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];

    // Add appointments to events
    foreach ($appointments as $appointment) {
        $events[] = [
            'id' => $appointment['id'],
            'title' => $appointment['service_name'] . ' - ' . $appointment['first_name'],
            'start' => $appointment['appointment_date'] . 'T' . $appointment['appointment_time'],
            'extendedProps' => [
                'type' => 'appointment',
                'status' => $appointment['status'],
                'customer' => $appointment['first_name'] . ' ' . $appointment['last_name'],
                'pet_name' => $appointment['pet_name'],
                'pet_type' => $appointment['pet_type'],
                'time' => date('g:i A', strtotime($appointment['appointment_time'])),
                'notes' => $appointment['notes']
            ]
        ];
    }

    // Add closures to events
    foreach ($closures as $closure) {
        $events[] = [
            'id' => 'closure_' . $closure['id'],
            'title' => $closure['title'],
            'start' => $closure['closure_date'],
            'allDay' => $closure['closure_type'] === 'full_day',
            'display' => $closure['closure_type'] === 'full_day' ? 'background' : 'auto',
            'backgroundColor' => $closure['closure_type'] === 'full_day' ? '#6b7280' : '#f59e0b',
            'borderColor' => $closure['closure_type'] === 'full_day' ? '#4b5563' : '#d97706',
            'textColor' => '#ffffff',
            'extendedProps' => [
                'type' => 'closure',
                'closure_type' => $closure['closure_type'],
                'start_time' => $closure['start_time'],
                'end_time' => $closure['end_time'],
                'reason' => $closure['reason']
            ]
        ];
    }

    echo json_encode($events);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch events']);
}
?>