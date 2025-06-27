<?php
require_once '../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}

$appointment_id = (int)$_POST['appointment_id'];

// Get appointment details
$appointment = getAppointmentById($appointment_id);
if (!$appointment) {
    $_SESSION['error_message'] = 'Appointment not found.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}

// Update appointment status
try {
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ?");
    $stmt->execute([$appointment_id]);
    
    // Send confirmation email to customer
    $subject = "Appointment Confirmed";
    $body = "Hello {$appointment['first_name']},<br><br>
            Your appointment for {$appointment['pet_name']} has been confirmed!<br><br>
            <strong>Appointment Details:</strong><br>
            Service: {$appointment['service_name']}<br>
            Date: " . formatDate($appointment['appointment_date']) . "<br>
            Time: " . formatTime($appointment['appointment_time']) . "<br>
            Pet: {$appointment['pet_name']} ({$appointment['pet_type']})<br><br>
            Please arrive 10 minutes before your scheduled time. If you need to cancel or reschedule, please contact us at least 24 hours in advance.<br><br>
            Best regards,<br>
            The FurCare Team";
    
    sendEmail($appointment['email'], $subject, $body);
    
    $_SESSION['success_message'] = 'Appointment confirmed successfully.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to confirm appointment. Please try again.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}
?>