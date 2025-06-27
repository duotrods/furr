<?php
require_once '../includes/config.php';
requireAuth();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Invalid appointment ID.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

$appointment_id = (int) $_GET['id'];

// Fetch appointment details with status check
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ? AND user_id = ? AND status = 'pending'");
$stmt->execute([$appointment_id, getUserId()]);
$appointment = $stmt->fetch();


$currentDateTime = new DateTime();
$appointmentDateTime = new DateTime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']);

// Prevent editing if appointment is within 24 hours
$minEditTime = (clone $currentDateTime)->modify('+24 hours');
if ($appointmentDateTime < $minEditTime) {
    $_SESSION['error_message'] = 'Appointments cannot be edited within 24 hours of the scheduled time.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

// Set minimum datetime for the form (current time + 1 hour)
$minDate = (clone $currentDateTime)->modify('+1 hour');

if (!$appointment) {
    $_SESSION['error_message'] = 'Appointment not found or cannot be edited (must be in pending status).';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

$csrf_token = generateCSRFToken();
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow mt-8">
    <h2 class="text-2xl font-semibold mb-4">Edit Appointment</