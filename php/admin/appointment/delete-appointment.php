<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

if (!isset($_POST['appointment_id'])) {
    header('Location: ../../../admin/appointmenarchive.php');
    exit();
}

$appointment_id = (int) $_POST['appointment_id'];

try {
    // Start transaction
    $pdo->beginTransaction();

    // First delete dependent records
    $stmt = $pdo->prepare("DELETE FROM appointment_history WHERE appointment_id = ?");
    $stmt->execute([$appointment_id]);

    // Then delete the appointment
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->execute([$appointment_id]);

    // Commit transaction
    $pdo->commit();

    // $_SESSION['success_message'] = 'Appointment permanently deleted.';
} catch (PDOException $e) {
    // Roll back transaction if something failed
    $pdo->rollBack();
    $_SESSION['error_message'] = 'Error deleting appointment.';
    error_log("Delete appointment error: " . $e->getMessage());
}

header('Location: ../../../admin/appointmenarchive.php');
exit();
?>