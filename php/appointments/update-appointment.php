<?php
require_once '../../includes/config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

$appointment_id = (int)$_POST['appointment_id'];
$pet_name = sanitize($_POST['pet_name']);
$pet_type = sanitize($_POST['pet_type']);
$appointment_date = sanitize($_POST['appointment_date']);
$appointment_time = sanitize($_POST['appointment_time']);
$notes = sanitize($_POST['notes']);

// Validate inputs
$errors = [];

if (empty($pet_name)) {
    $errors[] = 'Pet name is required.';
}

if (empty($pet_type)) {
    $errors[] = 'Pet type is required.';
}

if (empty($appointment_date)) {
    $errors[] = 'Appointment date is required.';
} elseif (strtotime($appointment_date) < strtotime('today')) {
    $errors[] = 'Appointment date cannot be in the past.';
}

if (empty($appointment_time)) {
    $errors[] = 'Appointment time is required.';
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: ' . BASE_URL . '/public/edit-appointment.php?id=' . $appointment_id);
    exit();
}

// Check if appointment belongs to user
$stmt = $pdo->prepare("SELECT user_id FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();

if (!$appointment || $appointment['user_id'] != getUserId()) {
    $_SESSION['error_message'] = 'Invalid appointment.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
}

// Check if time slot is available
$stmt = $pdo->prepare("SELECT id FROM appointments 
                      WHERE appointment_date = ? AND appointment_time = ? AND status IN ('pending', 'confirmed') AND id != ?");
$stmt->execute([$appointment_date, $appointment_time, $appointment_id]);
if ($stmt->fetch()) {
    $_SESSION['error_message'] = 'The selected time slot is no longer available. Please choose another time.';
    header('Location: ' . BASE_URL . '/public/edit-appointment.php?id=' . $appointment_id);
    exit();
}

// Update appointment
try {
    $stmt = $pdo->prepare("UPDATE appointments 
                          SET pet_name = ?, pet_type = ?, appointment_date = ?, appointment_time = ?, notes = ?, status = 'pending'
                          WHERE id = ?");
    $stmt->execute([$pet_name, $pet_type, $appointment_date, $appointment_time, $notes, $appointment_id]);
    
    $_SESSION['success_message'] = 'Appointment updated successfully! It will need to be confirmed again.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to update appointment. Please try again.';
    header('Location: ' . BASE_URL . '/public/edit-appointment.php?id=' . $appointment_id);
    exit();
}
?>