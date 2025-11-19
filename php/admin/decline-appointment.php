<?php
require_once __DIR__ . '/../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'] ?? '';
    $decline_reason = $_POST['decline_reason'] ?? '';

    if (!empty($appointment_id)) {
        // First, get user_id and appointment details
        $stmt = $pdo->prepare("
            SELECT a.user_id, a.pet_name, s.name as service_name, a.appointment_date 
            FROM appointments a 
            JOIN services s ON a.service_id = s.id 
            WHERE a.id = ?
        ");
        $stmt->execute([$appointment_id]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appointment) {
            // Update the appointment status and store the reason
            $stmt = $pdo->prepare("
                UPDATE appointments 
                SET status = 'declined', 
                    decline_reason = ?,
                    updated_at = NOW() 
                WHERE id = ?
            ");

            if ($stmt->execute([$decline_reason, $appointment_id])) {
                // Create notification for the user
                $notification_stmt = $pdo->prepare("
                    INSERT INTO notifications (user_id, title, message, created_at) 
                    VALUES (?, 'Appointment Declined', ?, NOW())
                ");

                $notification_message = "Your appointment for " . $appointment['pet_name'] .
                    " (" . $appointment['service_name'] .
                    ") on " . date('M j, Y', strtotime($appointment['appointment_date'])) .
                    " has been declined.";

                if (!empty($decline_reason)) {
                    $notification_message .= " Reason: " . $decline_reason;
                } else {
                    $notification_message .= " Please contact us for more information.";
                }

                $notification_stmt->execute([$appointment['user_id'], $notification_message]);

                $_SESSION['success'] = "Appointment declined successfully.";
            } else {
                $_SESSION['error'] = "Failed to decline appointment.";
            }
        } else {
            $_SESSION['error'] = "Appointment not found.";
        }
    } else {
        $_SESSION['error'] = "Invalid appointment ID.";
    }

    header("Location: ../../admin/appointments.php");
    exit;
}
?>