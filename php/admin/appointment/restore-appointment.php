<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

if (!isset($_POST['appointment_id'])) {
    header('Location: ../../../admin/appointmenarchive.php');
    exit();
}

$appointment_id = (int) $_POST['appointment_id'];

try {
    $stmt = $pdo->prepare("UPDATE appointments SET is_archived = FALSE, archived_at = NULL WHERE id = ?");
    $stmt->execute([$appointment_id]);

    // $_SESSION['success_message'] = 'Appointment restored successfully.';
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Error restoring appointment.';
    error_log("Restore appointment error: " . $e->getMessage());
}

header('Location: ../../../admin/appointmenarchive.php');
exit();
?>