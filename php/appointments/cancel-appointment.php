<?php
require_once '../../includes/config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

$appointment_id = (int)$_POST['appointment_id'];

// Check if appointment belongs to user
$stmt = $pdo->prepare("SELECT user_id FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();

if (!$appointment || $appointment['user_id'] != getUserId()) {
    $_SESSION['error_message'] = 'Invalid appointment.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

// Cancel appointment
try {
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
    $stmt->execute([$appointment_id]);
    
    $_SESSION['success_message'] = 'Appointment cancelled successfully.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to cancel appointment. Please try again.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}
?>