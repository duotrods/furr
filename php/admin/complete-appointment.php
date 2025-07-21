<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

// Validate appointment ID
if (!isset($_POST['appointment_id']) || !is_numeric($_POST['appointment_id'])) {
    $_SESSION['error_message'] = 'Invalid appointment ID.';
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}

$appointment_id = (int) $_POST['appointment_id'];

try {
    // Begin transaction
    $pdo->beginTransaction();

    // 1. Get appointment details
    $stmt = $pdo->prepare("
        SELECT a.*, u.email, u.first_name AS user_name, s.name AS service_name 
        FROM appointments a
        LEFT JOIN users u ON a.user_id = u.id
        LEFT JOIN services s ON a.service_id = s.id
        WHERE a.id = ?
    ");
    $stmt->execute([$appointment_id]);
    $appointment = $stmt->fetch();

    if (!$appointment) {
        throw new Exception('Appointment not found.');
    }

    // 2. Validate appointment can be completed
    if ($appointment['status'] === 'completed') {
        $_SESSION['error_message'] = 'Appointment is already completed.';
        header('Location: ' . BASE_URL . '/admin/appointments.php');
        exit();
    }

    if ($appointment['status'] === 'declined') {
        $_SESSION['error_message'] = 'Cannot complete a declined appointment.';
        header('Location: ' . BASE_URL . '/admin/appointments.php');
        exit();
    }

    // 3. Update appointment status
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'completed', 
            updated_at = NOW() 
        WHERE id = ?
    ");
    $stmt->execute([$appointment_id]);

    // 4. Record in appointment history
    $stmt = $pdo->prepare("
        INSERT INTO appointment_history 
        (appointment_id, status, changed_by, notes) 
        VALUES (?, 'completed', ?, ?)
    ");
    $stmt->execute([
        $appointment_id,
        $_SESSION['user_id'],
        "Appointment marked as completed by admin"
    ]);

    // 5. Send notification if user exists
    if ($appointment['email']) {
        $subject = "Your FurCare Appointment is Complete";
        $message = "Dear {$appointment['user_name']},\n\n";
        $message .= "Your {$appointment['service_name']} appointment for {$appointment['pet_name']} ";
        $message .= "({$appointment['pet_type']}) on {$appointment['appointment_date']} ";
        $message .= "at {$appointment['appointment_time']} has been completed.\n\n";
        $message .= "Notes: {$appointment['notes']}\n\n";
        $message .= "Thank you for choosing FurCare!\n\n";
        $message .= "Best regards,\nThe FurCare Team";

        sendEmail($appointment['email'], $subject, $message);

        // Create notification
        $notificationTitle = "Your FurCare Appointment is Complete";
        $notificationMessage = "Your {$appointment['service_name']} appointment for {$appointment['pet_name']} " .
            formatDate($appointment['appointment_date']) . " at " .
            formatTime($appointment['appointment_time']) . " has been completed";

        createNotification($appointment['user_id'], $notificationTitle, $notificationMessage);
    }


    // Commit transaction
    $pdo->commit();

    $_SESSION['success_message'] = "Appointment #{$appointment_id} completed successfully.";
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error completing appointment: " . $e->getMessage());
    $_SESSION['error_message'] = 'Error completing appointment: ' . $e->getMessage();
    header('Location: ' . BASE_URL . '/admin/appointments.php');
    exit();
}