<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold text-gray-800">
                    <span class="text-blue-600">Fur</span>Care
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="<?= ($current_page == 'index.php') ? 'text-blue-600' : 'text-gray-800' ?> hover:text-blue-600 transition duration-300">Home</a>
                <a href="/services.php" class="<?= ($current_page == 'services.php') ? 'text-blue-600' : 'text-gray-800' ?> hover:text-blue-600 transition duration-300">Services</a>
                <a href="/products.php" class="<?= ($current_page == 'products.php') ? 'text-blue-600' : 'text-gray-800' ?> hover:text-blue-600 transition duration-300">Products</a>
                
                <?php if (isLoggedIn()): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-1 focus:outline-none">
                            <span class="text-gray-800"><?= htmlspecialchars(getUser()['first_name'] ?? 'User') ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <a href="/profile.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Profile</a>
                            <a href="/my-appointments.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">My Appointments</a>
                            <?php if (isAdmin()): ?>
                                <a href="/admin/" class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Admin Panel</a>
                            <?php endif; ?>
                            <a href="/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-600 hover:text-white">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login.php" class="text-gray-800 hover:text-blue-600 transition duration-300">Login</a>
                    <a href="/register.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">Register</a>
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
            <a href="/" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'index.php') ? 'bg-blue-100' : '' ?>">Home</a>
            <a href="/services.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'services.php') ? 'bg-blue-100' : '' ?>">Services</a>
            <a href="/products.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'products.php') ? 'bg-blue-100' : '' ?>">Products</a>
            
            <?php if (isLoggedIn()): ?>
                <a href="/profile.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'profile.php') ? 'bg-blue-100' : '' ?>">Profile</a>
                <a href="/my-appointments.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'my-appointments.php') ? 'bg-blue-100' : '' ?>">My Appointments</a>
                <?php if (isAdmin()): ?>
                    <a href="/admin/" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= (strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? 'bg-blue-100' : '' ?>">Admin Panel</a>
                <?php endif; ?>
                <a href="/logout.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100">Logout</a>
            <?php else: ?>
                <a href="/login.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'login.php') ? 'bg-blue-100' : '' ?>">Login</a>
                <a href="/register.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-100 <?= ($current_page == 'register.php') ? 'bg-blue-100' : '' ?>">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>