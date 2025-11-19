<?php
require_once __DIR__ . '/../includes/config.php';
requireAuth();

$user_id = getUserId();

// Fetch user's appointments
$stmt = $pdo->prepare("
    SELECT a.id, a.pet_name, a.pet_type, a.appointment_date, a.appointment_time, 
           a.status, a.decline_reason, s.name AS service_name
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    WHERE a.user_id = ?
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 tracking-tight">My Appointments</h1>
                    <p class="mt-2 text-lg text-gray-600">Manage your pet care appointments</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border">
                        <span class="text-sm font-medium text-gray-500">Total Appointments</span>
                        <div class="text-2xl font-bold text-blue-600"><?= count($appointments) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            <?= $_SESSION['success_message'];
                            unset($_SESSION['success_message']); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            <?= $_SESSION['error_message'];
                            unset($_SESSION['error_message']); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <?php if (count($appointments) === 0): ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="bg-white rounded-2xl shadow-sm border p-12 max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-4 8h.01M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No appointments yet</h3>
                    <p class="text-gray-600 mb-6">You haven't scheduled any appointments for your pets.</p>
                    <a href="#"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        Schedule Your First Appointment
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Appointments Table -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Your Appointments</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Service & Pet Details
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Appointment
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($appointments as $appointment): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <!-- Service & Pet Details -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    <?= htmlspecialchars($appointment['service_name']) ?>
                                                </p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <?= htmlspecialchars($appointment['pet_name']) ?> •
                                                    <span
                                                        class="capitalize"><?= htmlspecialchars($appointment['pet_type']) ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Appointment Date & Time -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 mb-1">
                                                <?= formatDate($appointment['appointment_date']) ?>
                                            </div>
                                            <div class="text-gray-600 flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <?= formatTime($appointment['appointment_time']) ?>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClasses = [
                                            'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'declined' => 'bg-red-100 text-red-800 border-red-200'
                                        ];
                                        $statusClass = $statusClasses[$appointment['status']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                            <?php if ($appointment['status'] === 'confirmed'): ?>
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            <?php elseif ($appointment['status'] === 'cancelled' || $appointment['status'] === 'declined'): ?>
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            <?php endif; ?>
                                            <?= ucfirst($appointment['status']) ?>
                                        </span>

                                        <!-- Show decline reason if exists -->
                                        <?php if ($appointment['status'] === 'declined' && !empty($appointment['decline_reason'])): ?>
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-start">
                                                    <svg class="w-4 h-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-red-800">Reason for decline:</p>
                                                        <p class="text-sm text-red-700 mt-1">
                                                            <?= htmlspecialchars($appointment['decline_reason']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4">
                                        <?php if ($appointment['status'] === 'pending' || $appointment['status'] === 'confirmed'): ?>
                                            <div class="flex items-center space-x-3">
                                                <!-- Cancel Form -->
                                                <form action="../php/appointments/cancel-appointment.php" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to cancel this appointment?');"
                                                    class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Cancel
                                                    </button>
                                                </form>

                                                <!-- Edit Form (commented out as in original) -->
                                                <!-- <form action="edit-appointment.php" method="GET" onsubmit="return confirm('Are you sure you want to edit this appointment status?');" class="inline">
                                                    <input type="hidden" name="id" value="<?= $appointment['id'] ?>"> 
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </button>
                                                </form> -->
                                            </div>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-400 italic">No actions available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>