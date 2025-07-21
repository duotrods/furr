<?php
require_once '../../includes/config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/services.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/public/services.php');
    exit();
}

// Sanitize inputs
$service_id = (int) $_POST['service_id'];
$pet_name = sanitize($_POST['pet_name']);
$email = sanitize($_POST['email']);
$contact_number = sanitize($_POST['contact_number']);
$pet_type = sanitize($_POST['pet_type']);
$appointment_date = sanitize($_POST['appointment_date']);
$appointment_time = sanitize($_POST['appointment_time']);
$notes = sanitize($_POST['notes']);

// Validate inputs
$errors = [];

if (empty($service_id)) {
    $errors[] = 'Service is required.';
}

if (empty($pet_name)) {
    $errors[] = 'Pet name is required.';
}

if (empty($email)) {
    $errors[] = 'Email is required.';
}

if (empty($contact_number)) {
    $errors[] = 'Contact number is required.';
}

if (empty($pet_type)) {
    $errors[] = 'Pet type is required.';
}

if (empty($appointment_date)) {
    $errors[] = 'Appointment date is required.';
} elseif (strtotime($appointment_date) < strtotime('today')) {
    $errors[] = 'Appointment date cannot be in the past.';
} else {
    // Only check for closures if date is valid
    $stmt = $pdo->prepare("SELECT 1 FROM store_closures WHERE closure_date = ?");
    $stmt->execute([$appointment_date]);
    if ($stmt->fetch()) {
        $errors[] = 'We are closed on the selected date. Please choose another date.';
    }
}

if (empty($appointment_time)) {
    $errors[] = 'Appointment time is required.';
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: ' . BASE_URL . '/public/book-appointment.php?service_id=' . $service_id);
    exit();
}

// Check if service exists
$service = getServiceById($service_id);
if (!$service) {
    $_SESSION['error_message'] = 'Invalid service selected.';
    header('Location: ' . BASE_URL . '/public/services.php');
    exit();
}

// Check if time slot is still available
$stmt = $pdo->prepare("SELECT id FROM appointments 
                    WHERE appointment_date = ? AND appointment_time = ? AND status IN ('pending', 'confirmed')");
$stmt->execute([$appointment_date, $appointment_time]);
if ($stmt->fetch()) {
    $_SESSION['error_message'] = 'The selected time slot is no longer available. Please choose another time.';
    header('Location: ' . BASE_URL . '/public/book-appointment.php?service_id=' . $service_id);
    exit();
}

// Insert appointment
try {
    $stmt = $pdo->prepare("INSERT INTO appointments 
                        (user_id, service_id, pet_name, email, contact_number, pet_type, appointment_date, appointment_time, notes, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([getUserId(), $service_id, $pet_name, $email, $contact_number, $pet_type, $appointment_date, $appointment_time, $notes]);

    // Get appointment ID
    $appointment_id = $pdo->lastInsertId();

    // Send confirmation email to customer
    $user = getUser();
    $subject = "Appointment Booking Confirmation";
    $body = "Hello {$pet_name},<br><br>
            Your appointment has been received and is pending confirmation.<br><br>
            <strong>Appointment Details:</strong><br>
            Service: {$service['name']}<br>
            Date: " . formatDate($appointment_date) . "<br>
            Time: " . formatTime($appointment_time) . "<br>
            Pet Type: {$pet_type} <br><br>
            We'll send you another email once your appointment is confirmed. You can also check the status in your account.<br><br>
            Best regards,<br>
            The FurCare Team";

    sendEmail($user['email'], $subject, $body);

    // Send notification to admin
    $admin_subject = "New Appointment Request";
    $admin_body = "A new appointment request has been submitted:<br><br>
                <strong>Customer:</strong> {$pet_name}<br>
                <strong>Service:</strong> {$service['name']}<br>
                <strong>Date:</strong> " . formatDate($appointment_date) . "<br>
                <strong>Time:</strong> " . formatTime($appointment_time) . "<br>
                <strong>Pet Type:</strong> {$pet_type}<br><br>
                Please log in to the admin panel to confirm or decline the appointment.";

    // Get admin emails (in a real system, you might have multiple admins)
    $stmt = $pdo->prepare("SELECT email FROM users WHERE is_admin = 1");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($admins as $admin_email) {
        sendEmail($admin_email, $admin_subject, $admin_body);
    }

    $_SESSION['success_message'] = 'Your appointment has been booked successfully! We will notify you once it\'s confirmed.';
    header('Location: ' . BASE_URL . '/public/my-appointments.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Failed to book appointment. Please try again.';
    header('Location: ' . BASE_URL . '/public/book-appointment.php?service_id=' . $service_id);
    exit();
}
?>