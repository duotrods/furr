<?php require_once '../includes/config.php'; ?>
<?php requireAuth(); ?>
<?php require_once '../includes/header.php'; ?>

<?php
$user = getUser();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    
    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->execute([$first_name, $last_name, $email, $phone, $address, getUserId()]);
    
    $_SESSION['success_message'] = 'Profile updated successfully!';
    header('Location: profile.php');
    exit();
}
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Account Settings</h1>
            <p class="text-gray-600">Manage your profile information and view your appointments</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                        <h2 class="text-2xl font-semibold text-white">Profile Information</h2>
                        <p class="text-blue-100 mt-1">Update your personal details</p>
                    </div>

                    <!-- Messages -->
                    <div class="px-8 pt-6">
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Form -->
                    <form action="profile.php" method="POST" class="px-8 pb-8">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label for="first_name" class="block text-sm font-semibold text-gray-700">First Name</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       value="<?php echo $user['first_name']; ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-gray-400">
                            </div>
                            <div class="space-y-2">
                                <label for="last_name" class="block text-sm font-semibold text-gray-700">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       value="<?php echo $user['last_name']; ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-gray-400">
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo $user['email']; ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-gray-400">
                        </div>
                        
                        <div class="space-y-2 mb-6">
                            <label for="phone" class="block text-sm font-semibold text-gray-700">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo $user['phone']; ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-gray-400">
                        </div>
                        
                        <div class="space-y-2 mb-8">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Address</label>
                            <textarea id="address" name="address" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-gray-400 resize-none"><?php echo $user['address']; ?></textarea>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 pt-4 border-t border-gray-200">
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg">
                                Update Profile
                            </button>
                            
                            <a href="change-password.php" class="text-blue-600 hover:text-blue-800 font-semibold text-center py-2 px-4 rounded-lg hover:bg-blue-50 transition duration-200">
                                Change Password →
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Info Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">
                                <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">
                            <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                        </h3>
                        <p class="text-gray-600"><?php echo $user['email']; ?></p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h4>
                    <?php $appointments = getUserAppointments(getUserId()); ?>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Appointments</span>
                            <span class="font-semibold text-gray-900"><?php echo count($appointments); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-semibold text-yellow-600">
                                <?php echo count(array_filter($appointments, function($a) { return $a['status'] == 'pending'; })); ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Confirmed</span>
                            <span class="font-semibold text-green-600">
                                <?php echo count(array_filter($appointments, function($a) { return $a['status'] == 'confirmed'; })); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Section -->
        <div class="mt-12">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Section Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-8 py-6">
                    <h2 class="text-2xl font-semibold text-white">My Appointments</h2>
                    <p class="text-gray-300 mt-1">View and manage your upcoming and past appointments</p>
                </div>

                <div class="p-8">
                    <?php if (empty($appointments)): ?>
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h6m-6 4h6m-6 4h6M3 15a2 2 0 002-2V9a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No appointments yet</h3>
                            <p class="text-gray-600">Your appointment history will appear here once you book your first service.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Service</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($appointments as $appointment): ?>
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900"><?php echo $appointment['service_name']; ?></div>
                                            <div class="text-sm text-gray-600 font-medium">₱<?php echo number_format($appointment['service_price'], 2); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo formatDate($appointment['appointment_date']); ?></div>
                                            <div class="text-sm text-gray-600"><?php echo formatTime($appointment['appointment_time']); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                <?php echo $appointment['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                      ($appointment['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                      ($appointment['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                                      'bg-red-100 text-red-800')); ?>">
                                                <span class="w-2 h-2 rounded-full mr-2 
                                                    <?php echo $appointment['status'] == 'confirmed' ? 'bg-green-400' : 
                                                          ($appointment['status'] == 'pending' ? 'bg-yellow-400' : 
                                                          ($appointment['status'] == 'completed' ? 'bg-blue-400' : 
                                                          'bg-red-400')); ?>"></span>
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($appointment['status'] == 'pending' || $appointment['status'] == 'confirmed'): ?>
                                                <form action="../php/appointments/cancel-appointment.php" method="POST" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition duration-200">
                                                        Cancel
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-sm">No actions</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>