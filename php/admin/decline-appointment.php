<?php
require_once '../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}

$appointment_id = (int) $_POST['appointment_id'];

// Get appointment details
$appointment = getAppointmentById($appointment_id);
if (!$appointment) {
    $_SESSION['error_message'] = 'Appointment not found.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}

// Update appointment status
try {
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'declined' WHERE id = ?");
    $stmt->execute([$appointment_id]);

    // Send notification email to customer
    $subject = "Appointment Declined";
    $body = "Hello {$appointment['first_name']},<br><br>
            We regret to inform you that your appointment for {$appointment['pet_name']} has been declined.<br><br>
            <strong>Appointment Details:</strong><br>
            Service: {$appointment['service_name']}<br>
            Date: " . formatDate($appointment['appointment_date']) . "<br>
            Time: " . formatTime($appointment['appointment_time']) . "<br>
            Pet: {$appointment['pet_name']} ({$appointment['pet_type']})<br><br>
            Please contact us if you have any questions or would like to reschedule.<br><br>
            Best regards,<br>
            The FurCare Team";

    sendEmail($appointment['email'], $subject, $body);

    // Create notification
    $notificationTitle = "Appointment Declined";
    $notificationMessage = "Hello {$appointment['first_name']},<br><br>
            We regret to inform you that your appointment for {$appointment['pet_name']} has been declined." .
        formatDate($appointment['appointment_date']) . " at " .
        formatTime($appointment['appointment_time']) . " has been declined!";

    createNotification($appointment['user_id'], $notificationTitle, $notificationMessage);

    $_SESSION['success_message'] = 'Appointment declined successfully.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to decline appointment. Please try again.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}
?>