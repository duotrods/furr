<?php
require_once __DIR__ . '/../includes/config.php';
requireAdmin();

// Delete appointments older than 15 days
$pdo->exec("
    DELETE ah FROM appointment_history ah
    INNER JOIN appointments a ON ah.appointment_id = a.id
    WHERE a.is_archived = TRUE 
    AND a.archived_at < DATE_SUB(NOW(), INTERVAL 15 DAY)
");

$pdo->exec("
    DELETE FROM appointments 
    WHERE is_archived = TRUE 
    AND archived_at < DATE_SUB(NOW(), INTERVAL 15 DAY)
");

// Get archived appointments with proper joins
$stmt = $pdo->prepare("
    SELECT a.*, 
           u.first_name, u.last_name,
           s.name AS service_name,
           s.price AS service_price
    FROM appointments a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN services s ON a.service_id = s.id
    WHERE a.is_archived = TRUE 
    ORDER BY a.archived_at DESC
");
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-slate-800 mb-2">Archived Appointments</h1>
                    <p class="text-slate-600">Manage archived appointments (automatically deleted after 15 days)</p>
                </div>
                <div>
                    <a href="appointments.php"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-700 to-slate-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Customer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-spa mr-2"></i>Service
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-paw mr-2"></i>Pet
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Date & Time
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-clock mr-2"></i>Archived
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <?php foreach ($appointments as $appointment):
                            $archivedDate = new DateTime($appointment['archived_at'] ?? 'now');
                            $deleteDate = (clone $archivedDate)->add(new DateInterval('P15D'));
                            $daysLeft = $deleteDate->diff(new DateTime())->days;
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-slate-900">
                                                <?= htmlspecialchars(($appointment['first_name'] ?? '') . ' ' . ($appointment['last_name'] ?? '')) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">
                                        <?= htmlspecialchars($appointment['service_name'] ?? 'Unknown Service') ?>
                                    </div>
                                    <div class="text-sm text-green-600 font-semibold flex items-center">
                                        <i class="fas fa-peso-sign text-xs mr-1"></i>
                                        <?= number_format($appointment['service_price'] ?? 0, 2) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
                                                <i class="fas fa-paw text-white text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm text-slate-500">
                                                <?= htmlspecialchars($appointment['pet_type'] ?? 'Unknown') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900 flex items-center">
                                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                        <?= formatDate($appointment['appointment_date'] ?? '') ?>
                                    </div>
                                    <div class="text-sm text-slate-500 flex items-center">
                                        <i class="fas fa-clock text-slate-400 mr-2"></i>
                                        <?= formatTime($appointment['appointment_time'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm
                                    <?= $appointment['status'] == 'confirmed' ? 'bg-green-100 text-green-800 border border-green-200' :
                                        ($appointment['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                                            ($appointment['status'] == 'completed' ? 'bg-blue-100 text-blue-800 border border-blue-200' :
                                                'bg-red-100 text-red-800 border border-red-200')); ?>">
                                        <?php
                                        $statusIcons = [
                                            'confirmed' => 'fas fa-check-circle',
                                            'pending' => 'fas fa-clock',
                                            'completed' => 'fas fa-check-double',
                                            'declined' => 'fas fa-times-circle'
                                        ];
                                        ?>
                                        <i
                                            class="<?= $statusIcons[$appointment['status']] ?? 'fas fa-question-circle' ?> mr-1"></i>
                                        <?= ucfirst($appointment['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-slate-500">
                                        <?= formatDateTime($appointment['archived_at'] ?? '') ?>
                                        <div class="text-xs <?= $daysLeft <= 3 ? 'text-red-500' : 'text-slate-400' ?>">
                                            Auto-delete in <?= $daysLeft ?> days
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <form action="../php/admin/appointment/restore-appointment.php" method="POST"
                                            class="inline">
                                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 border border-green-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                                <i class="fas fa-undo mr-1"></i>
                                                Restore
                                            </button>
                                        </form>
                                        <form action="../php/admin/appointment/delete-appointment.php" method="POST"
                                            class="inline">
                                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 border border-red-300 rounded-lg transition-all duration-200 hover:shadow-md"
                                                onclick="return confirm('Are you sure you want to permanently delete this appointment?');">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>