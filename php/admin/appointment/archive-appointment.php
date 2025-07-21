<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: ../../../admin/appointments.php');
    exit();
}

$appointment_id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("UPDATE appointments SET is_archived = TRUE, archived_at = NOW() WHERE id = ?");
    $stmt->execute([$appointment_id]);

    // $_SESSION['success_message'] = 'Appointment archived successfully.';
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error archiving appointment.';
    error_log("Archive appointment error: " . $e->getMessage());
}

header('Location: ../../../admin/appointments.php');
exit();
?>