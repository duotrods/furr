<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Invalid appointment ID.';
    header('Location: appointments.php');
    exit();
}

$appointment_id = (int) $_GET['id'];

// First, get the appointment to know which service we're dealing with
$stmt = $pdo->prepare("
    SELECT a.*, u.first_name, u.last_name, u.email, u.phone, 
           s.name AS service_name, s.price AS service_price, s.size AS service_size 
    FROM appointments a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN services s ON a.service_id = s.id
    WHERE a.id = ?
");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    $_SESSION['error_message'] = 'Appointment not found.';
    header('Location: appointments.php');
    exit();
}

// Get available sizes and prices for this service
$service_stmt = $pdo->prepare("
    SELECT DISTINCT size, price 
    FROM services 
    WHERE name = ? AND size IS NOT NULL AND size != ''
    ORDER BY 
        CASE 
            WHEN size = 'Small' THEN 1
            WHEN size = 'Medium' THEN 2
            WHEN size = 'Large' THEN 3
            ELSE 4
        END
");
$service_stmt->execute([$appointment['service_name']]);
$size_prices = $service_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for updating pet size
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pet_size'])) {
    $new_pet_size = $_POST['pet_size'] ?? '';

    if (empty($new_pet_size)) {
        $_SESSION['error_message'] = 'Please select a valid pet size.';
    } else {
        try {
            $pdo->beginTransaction();

            // Find the service that matches the service name and the selected size
            $service_stmt = $pdo->prepare("
                SELECT id, price 
                FROM services 
                WHERE name = ? AND size = ?
                LIMIT 1
            ");
            $service_stmt->execute([$appointment['service_name'], $new_pet_size]);
            $new_service = $service_stmt->fetch();

            if ($new_service) {
                // Update appointment with new pet size AND service_id
                $update_stmt = $pdo->prepare("
                    UPDATE appointments 
                    SET pet_size = ?, service_id = ? 
                    WHERE id = ?
                ");

                $result = $update_stmt->execute([$new_pet_size, $new_service['id'], $appointment_id]);

                $pdo->commit();

                $_SESSION['success_message'] = 'Pet size and price updated successfully!';
                
                // Fix: Use absolute path from document root
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $appointment_id);
                exit();
            } else {
                throw new Exception("No service found for '{$appointment['service_name']}' with size '$new_pet_size'");
            }

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error_message'] = 'Error updating pet size: ' . $e->getMessage();
        }
    }
    
    // Reload the appointment data after update attempt
    $stmt->execute([$appointment_id]);
    $appointment = $stmt->fetch();
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Success/Error Messages -->
    <div class="container mx-auto px-4 py-4">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="appointments.php"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Appointments
                    </a>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600 font-medium">Live View</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            Appointment Details
                        </h1>
                        <div class="flex items-center space-x-3">
                            <span class="text-lg text-gray-600">ID: #<?php echo $appointment_id; ?></span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                <?php 
                                echo $appointment['status'] == 'confirmed' ? 'bg-green-100 text-green-800 border border-green-200' :
                                    ($appointment['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                                        ($appointment['status'] == 'completed' ? 'bg-blue-100 text-blue-800 border border-blue-200' :
                                            'bg-red-100 text-red-800 border border-red-200')); 
                                ?>">
                                <div class="w-2 h-2 rounded-full mr-2 
                                    <?php 
                                    echo $appointment['status'] == 'confirmed' ? 'bg-green-500' :
                                        ($appointment['status'] == 'pending' ? 'bg-yellow-500' :
                                            ($appointment['status'] == 'completed' ? 'bg-blue-500' : 'bg-red-500')); 
                                    ?>">
                                </div>
                                <?php echo ucfirst($appointment['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-2 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3a1 1 0 012-2h4a1 1 0 012 2v4h3a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z">
                            </path>
                        </svg>
                        <span class="text-sm">Appointment Record</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Customer & Pet Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-white">Customer Information</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Full Name</p>
                                    <p class="text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Email</p>
                                    <p class="text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($appointment['email']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg md:col-span-2">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Phone Number</p>
                                    <p class="text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($appointment['phone']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pet Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-white">Pet Information</h2>
                            <?php if (!empty($size_prices)): ?>
                            <button onclick="openEditModal()" 
                                    class="ml-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit Pet Size
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Pet Name</p>
                                    <p class="text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($appointment['pet_name']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Pet Type</p>
                                    <p class="text-gray-900 font-semibold">
                                        <?php echo htmlspecialchars($appointment['pet_type']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Pet Size</p>
                                    <p class="text-gray-900 font-semibold" id="current-pet-size">
                                        <?php echo isset($appointment['pet_size']) && !empty($appointment['pet_size']) ? htmlspecialchars($appointment['pet_size']) : 'Not set'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-white">Admin Notes</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4 min-h-[100px]">
                            <?php if (!empty($appointment['notes'])): ?>
                                <p class="text-gray-800 leading-relaxed whitespace-pre-line">
                                    <?php echo nl2br(htmlspecialchars($appointment['notes'])); ?>
                                </p>
                            <?php else: ?>
                                <p class="text-gray-500 italic">No notes available for this appointment.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Service & Schedule Info -->
            <div class="space-y-6">
                <!-- Service Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-white">Service Details</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                                <p class="text-sm text-emerald-600 font-medium mb-1">Service Name</p>
                                <p class="text-emerald-900 font-semibold text-lg">
                                    <?php echo htmlspecialchars($appointment['service_name']); ?>
                                </p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-sm text-green-600 font-medium mb-1">Service Price</p>
                                <p class="text-green-900 font-bold text-2xl" id="current-service-price">
                                    ₱<?php echo number_format($appointment['service_price'], 2); ?>
                                </p>
                            </div>
                            <?php if (!empty($appointment['service_size'])): ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-600 font-medium mb-1">Service Size</p>
                                <p class="text-blue-900 font-semibold">
                                    <?php echo htmlspecialchars($appointment['service_size']); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a1 1 0 012-2h4a1 1 0 012 2v4h3a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-white">Schedule</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-3 bg-violet-50 rounded-lg border border-violet-200">
                                <div class="w-8 h-8 bg-violet-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3a1 1 0 012-2h4a1 1 0 012 2v4h3a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-violet-600 font-medium">Appointment Date</p>
                                    <p class="text-violet-900 font-semibold">
                                        <?php echo formatDate($appointment['appointment_date']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-indigo-600 font-medium">Appointment Time</p>
                                    <p class="text-indigo-900 font-semibold">
                                        <?php echo formatTime($appointment['appointment_time']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Pet Size Modal -->
<?php if (!empty($size_prices)): ?>
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="bg-blue-600 text-white p-4 rounded-t-md -mt-3 -mx-5 mb-4">
                <h3 class="text-lg font-semibold">Edit Pet Size</h3>
            </div>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $appointment_id; ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="pet_size">
                        Pet Size
                    </label>
                    <select name="pet_size" id="pet_size" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Pet Size</option>
                        <?php foreach ($size_prices as $size_price): ?>
                            <option value="<?php echo htmlspecialchars($size_price['size']); ?>" 
                                    <?php echo ($appointment['pet_size'] == $size_price['size']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($size_price['size']); ?> - ₱<?php echo number_format($size_price['price'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Price will be automatically updated based on pet size</p>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="button" 
                            onclick="closeEditModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            name="update_pet_size"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function openEditModal() {
    console.log('Opening modal...');
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    console.log('Closing modal...');
    document.getElementById('editModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
}

// Add event listener for form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted!');
            console.log('Pet size:', document.getElementById('pet_size').value);
        });
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>