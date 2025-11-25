<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <?php if (isLoggedIn() && isAdmin()): ?>
                    <a href="../admin/index.php" class="flex items-center text-2xl font-bold text-gray-800">
                        <!-- Add logo image here -->
                        <img src="../assets/images/logo.png" alt="FurCare Logo" class="h-8 w-8 mr-2">
                        Fur<span class="text-blue-600">Care</span>
                    </a>
                <?php elseif (isLoggedIn()): ?>
                    <a href="../public/index.php" class="flex items-center text-2xl font-bold text-gray-800">
                        <!-- Add logo image here -->
                        <img src="../assets/images/logo.png" alt="FurCare Logo" class="h-8 w-8 mr-2">
                        Fur<span class="text-blue-600">Care</span>
                    </a>
                <?php else: ?>
                    <a href="../public/index.php" class="flex items-center text-2xl font-bold text-gray-800">
                        <!-- Add logo image here -->
                        <img src="../assets/images/logo.png" alt="FurCare Logo" class="h-8 w-8 mr-2">
                        Fur<span class="text-blue-600">Care</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <?php if (isLoggedIn() && isAdmin()): ?>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="../admin/index.php"
                            class="text-sm font-medium <?= ($current_page == 'index.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Home
                        </a>
                        <a href="../admin/appointments.php"
                            class="text-sm font-medium <?= ($current_page == 'appointments.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4 relative">
                            Appointments
                            <?php
                            // Get pending payment count
                            $stmt = $pdo->prepare("
        SELECT COUNT(*) as pending_count 
        FROM appointments
        WHERE status = 'pending'
    ");
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            $pending_count = $result['pending_count'] ?? 0;

                            if ($pending_count > 0): ?>
                                <span
                                    class="absolute -top-1 -right-2 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium transform translate-x-1/2 -translate-y-1/4">
                                    <?= $pending_count ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <a href="../admin/payment-review.php"
                            class="text-sm font-medium <?= ($current_page == 'payment-review.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4 relative">
                            Payments
                            <?php
                            // Get pending payment count
                            $stmt = $pdo->prepare("
        SELECT COUNT(*) as pending_count
        FROM orders
        WHERE status = 'payment_review'
    ");
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            $pending_count = $result['pending_count'] ?? 0;

                            if ($pending_count > 0): ?>
                                <span
                                    class="absolute -top-1 -right-2 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium transform translate-x-1/2 -translate-y-1/4">
                                    <?= $pending_count ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <a href="../admin/orders.php"
                            class="text-sm font-medium <?= ($current_page == 'orders.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Orders
                        </a>
                        <a href="../admin/products.php"
                            class="text-sm font-medium <?= ($current_page == 'products.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Products
                        </a>
                        <a href="../admin/reports.php"
                            class="text-sm font-medium <?= ($current_page == 'reports.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Reports
                        </a>
                        <a href="../admin/manage-registrations.php"
                            class="text-sm font-medium <?= ($current_page == 'manage-registrations.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4 relative">
                            Users
                            <?php
                            // Get pending registrations count
                            $stmt = $pdo->prepare("SELECT COUNT(*) as pending_count FROM users WHERE is_approved = 0");
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            $pending_reg_count = $result['pending_count'] ?? 0;

                            if ($pending_reg_count > 0): ?>
                                <span
                                    class="absolute -top-1 -right-2 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium transform translate-x-1/2 -translate-y-1/4">
                                    <?= $pending_reg_count ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            <span class="text-gray-800 text-md font-medium"><?php echo getUser()['first_name']; ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <a href="../public/profile.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Profile</a>
                            <a href="../admin/payment-review.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Payment-review</a>
                            <a href="../php/auth/logout.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Logout</a>
                        </div>
                    </div>
                <?php elseif (isLoggedIn()): ?>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="../public/index.php"
                            class="text-sm font-medium <?= ($current_page == 'index.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Home
                        </a>
                        <a href="../public/services.php"
                            class="text-sm font-medium <?= ($current_page == 'services.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Services
                        </a>
                        <a href="../public/products.php"
                            class="text-sm font-medium <?= ($current_page == 'products.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Products
                        </a>
                        <a href="<?= BASE_URL ?>/public/notification.php" class="relative">
                            <i class="fas fa-bell"></i>
                            <?php
                            try {
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
                                $stmt->execute([$_SESSION['user_id']]);
                                $unread = $stmt->fetchColumn();

                                if ($unread > 0): ?>
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                        <?= $unread ?>
                                    </span>
                                <?php endif;
                            } catch (PDOException $e) {
                                error_log("Notification count error: " . $e->getMessage());
                            }
                            ?>
                        </a>
                        <!-- Cart -->
                        <a href="../public/cart.php"
                            class="relative text-gray-500 hover:text-gray-700 transition duration-300 group">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8"></path>
                            </svg>
                            <?php if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                                <span
                                    class="absolute -top-1 -right-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium transform translate-x-1/2 -translate-y-1/4">
                                    <?= $_SESSION['cart_count'] ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="relative group">
                        <button class="flex items-center space-x-1 focus:outline-none">
                            <span class="text-md font-medium text-gray-700"><?php echo getUser()['first_name']; ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <a href="../public/profile.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Profile</a>
                            <a href="../public/my-appointments.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">My Appointments</a>
                            <a href="../public/my-orders.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">My Orders</a>
                            <a href="../php/auth/logout.php"
                                class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="../public/index.php"
                            class="text-sm font-medium <?= ($current_page == 'index.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Home
                        </a>
                        <a href="../public/services.php"
                            class="text-sm font-medium <?= ($current_page == 'services.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Services
                        </a>
                        <a href="../public/products.php"
                            class="text-sm font-medium <?= ($current_page == 'products.php') ? 'text-blue-600 border-b-2 border-blue-600 pb-4' : 'text-gray-700' ?> hover:text-blue-600 transition duration-300 py-4">
                            Products
                        </a>
                    </div>
                    <a href="../public/login.php"
                        class="rounded-md border-2 border-blue-600 font-bold py-1.5 px-4 text-blue-600 hover:text-white hover:bg-blue-800 hover:border-blue-800 transition duration-300">Login</a>
                    <a href="../public/register.php"
                        class="bg-blue-600 border-2 border-blue-600 hover:bg-blue-800 hover:border-blue-800 text-white font-bold py-1.5 px-4 rounded transition duration-300">Register</a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-800 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <?php if (isLoggedIn() && isAdmin()): ?>
                <a href="../admin/index.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? 'bg-blue-100' : ''; ?>">Home</a>
                <a href="../admin/appointments.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'appointments.php' ? 'bg-blue-100' : ''; ?>">Appointments</a>
                <a href="../admin/payment-review.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'payment-review.php' ? 'bg-blue-100' : ''; ?>">Payments</a>
                <a href="../admin/orders.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'orders.php' ? 'bg-blue-100' : ''; ?>">Orders</a>
                <a href="../admin/products.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'products.php' ? 'bg-blue-100' : ''; ?>">Products</a>
                <a href="../admin/reports.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'reports.php' ? 'bg-blue-100' : ''; ?>">Reports</a>
                <a href="../admin/manage-registrations.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'manage-registrations.php' ? 'bg-blue-100' : ''; ?>">User Registrations</a>
                <a href="../public/profile.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'profile.php' ? 'bg-blue-100' : ''; ?>">Profile</a>
                <a href="../php/auth/logout.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100">Logout</a>
            <?php elseif (isLoggedIn()): ?>
                <a href="../public/index.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'index.php' ? 'bg-blue-100' : ''; ?>">Home</a>
                <a href="../public/services.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'services.php' ? 'bg-blue-100' : ''; ?>">Services</a>
                <a href="../public/products.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'products.php' ? 'bg-blue-100' : ''; ?>">Products</a>
                <a href="../public/profile.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'profile.php' ? 'bg-blue-100' : ''; ?>">Profile</a>
                <a href="../public/my-appointments.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'my-appointments.php' ? 'bg-blue-100' : ''; ?>">My
                    Appointments</a>
                <a href="../php/auth/logout.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100">Logout</a>
            <?php else: ?>
                <a href="../public/index.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'index.php' ? 'bg-blue-100' : ''; ?>">Home</a>
                <a href="../public/services.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'services.php' ? 'bg-blue-100' : ''; ?>">Services</a>
                <a href="../public/products.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'products.php' ? 'bg-blue-100' : ''; ?>">Products</a>
                <a href="../public/login.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'login.php' ? 'bg-blue-100' : ''; ?>">Login</a>
                <a href="../public/register.php"
                    class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?php echo $current_page == 'register.php' ? 'bg-blue-100' : ''; ?>">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>