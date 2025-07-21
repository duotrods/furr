<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>

<?php
$status = $_GET['status'] ?? 'all';
$appointments = $status == 'all' ? archivedAppointments() : archivedAppointments($status);
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-slate-800 mb-2">Appointment Management</h1>
                    <p class="text-slate-600">Manage and track all pet care appointments</p>
                </div>
                <div class="padding-6 flex gap-4">
                    <a href="calendar.php"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        View Calendar
                    </a>
                    <a href="appointmenarchive.php"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-trash mr-2"></i>
                        Trash
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Status Filter Tabs -->
            <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-5 border-b border-slate-200">
                <div class="flex flex-wrap gap-3">
                    <a href="?status=all"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'all' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-list mr-2"></i>
                        All Appointments
                    </a>
                    <a href="?status=pending"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'pending' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-clock mr-2"></i>
                        Pending
                    </a>
                    <a href="?status=confirmed"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'confirmed' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmed
                    </a>
                    <a href="?status=completed"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'completed' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-check-double mr-2"></i>
                        Completed
                    </a>
                    <a href="?status=declined"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'declined' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-times-circle mr-2"></i>
                        Declined
                    </a>
                    <a href="?status=cancelled"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'cancelled' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-times-circle mr-2"></i>
                        Cancelled
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-700 to-slate-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Customer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                <i class="fas fa-spa mr-2"></i>Service
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                <i class="fas fa-paw mr-2"></i>Pet
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Date & Time
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <?php foreach ($appointments as $appointment): ?>
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
                                                <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">
                                        <?php echo $appointment['service_name']; ?>
                                    </div>
                                    <div class="text-sm text-green-600 font-semibold flex items-center">
                                        <i class="fas fa-peso-sign text-xs mr-1"></i>
                                        <?php echo number_format($appointment['service_price'], 2); ?>
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
                                            <div class="text-sm text-slate-500"><?php echo $appointment['pet_type']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900 flex items-center">
                                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                        <?php echo formatDate($appointment['appointment_date']); ?>
                                    </div>
                                    <div class="text-sm text-slate-500 flex items-center">
                                        <i class="fas fa-clock text-slate-400 mr-2"></i>
                                        <?php echo formatTime($appointment['appointment_time']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm
                                    <?php echo $appointment['status'] == 'confirmed' ? 'bg-green-100 text-green-800 border border-green-200' :
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
                                            class="<?php echo $statusIcons[$appointment['status']] ?? 'fas fa-question-circle'; ?> mr-1"></i>
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <?php if ($appointment['status'] == 'pending'): ?>
                                            <form action="../php/admin/confirm-appointment.php" method="POST" class="inline">
                                                <input type="hidden" name="appointment_id"
                                                    value="<?php echo $appointment['id']; ?>">
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 border border-green-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Confirm
                                                </button>
                                            </form>
                                            <form action="../php/admin/decline-appointment.php" method="POST" class="inline">
                                                <input type="hidden" name="appointment_id"
                                                    value="<?php echo $appointment['id']; ?>">
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 border border-red-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Decline
                                                </button>
                                            </form>
                                        <?php elseif ($appointment['status'] == 'confirmed'): ?>
                                            <form action="../php/admin/complete-appointment.php" method="POST" class="inline">
                                                <input type="hidden" name="appointment_id"
                                                    value="<?php echo $appointment['id']; ?>">
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 border border-blue-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Complete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <a href="view-appointment.php?id=<?php echo $appointment['id']; ?>"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                            <i class="fas fa-eye mr-1"></i>
                                            View
                                        </a>
                                        <a href="../php/admin/appointment/archive-appointment.php?id=<?php echo $appointment['id']; ?>"
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition-all duration-200 hover:shadow-md"
                                            onclick="return confirm('Are you sure you want to archive this appointment?');">
                                            <i class="fas fa-archive mr-1"></i>
                                            Archive
                                        </a>
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