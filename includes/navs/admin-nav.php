<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-gray-800 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="/admin/" class="text-2xl font-bold">
                    <span class="text-blue-300">FurCare</span> Admin
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/admin/" class="<?= ($current_page == 'index.php') ? 'text-blue-300' : 'text-white' ?> hover:text-blue-300 transition duration-300">Dashboard</a>
                <a href="/admin/products.php" class="<?= ($current_page == 'products.php') ? 'text-blue-300' : 'text-white' ?> hover:text-blue-300 transition duration-300">Products</a>
                <a href="/admin/users.php" class="<?= ($current_page == 'users.php') ? 'text-blue-300' : 'text-white' ?> hover:text-blue-300 transition duration-300">Users</a>
                <a href="/admin/appointments.php" class="<?= ($current_page == 'appointments.php') ? 'text-blue-300' : 'text-white' ?> hover:text-blue-300 transition duration-300">Appointments</a>
                
                <div class="relative group">
                    <button class="flex items-center space-x-1 focus:outline-none">
                        <span><?= htmlspecialchars(getUser()['first_name'] ?? 'Admin') ?></span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                        <a href="/admin/profile.php" class="block px-4 py-2 hover:bg-blue-100">Profile</a>
                        <a href="/admin/settings.php" class="block px-4 py-2 hover:bg-blue-100">Settings</a>
                        <a href="/logout.php" class="block px-4 py-2 hover:bg-blue-100 text-red-500">Logout</a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="admin-mobile-menu-button" class="text-white focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="admin-mobile-menu" class="hidden md:hidden pb-4">
            <a href="/admin/" class="block py-2 px-4 text-white hover:bg-gray-700 <?= ($current_page == 'index.php') ? 'bg-gray-700' : '' ?>">Dashboard</a>
            <a href="/admin/products.php" class="block py-2 px-4 text-white hover:bg-gray-700 <?= ($current_page == 'products.php') ? 'bg-gray-700' : '' ?>">Products</a>
            <a href="/admin/users.php" class="block py-2 px-4 text-white hover:bg-gray-700 <?= ($current_page == 'users.php') ? 'bg-gray-700' : '' ?>">Users</a>
            <a href="/admin/appointments.php" class="block py-2 px-4 text-white hover:bg-gray-700 <?= ($current_page == 'appointments.php') ? 'bg-gray-700' : '' ?>">Appointments</a>
            <a href="/admin/profile.php" class="block py-2 px-4 text-white hover:bg-gray-700 <?= ($current_page == 'profile.php') ? 'bg-gray-700' : '' ?>">Profile</a>
            <a href="/logout.php" class="block py-2 px-4 text-white hover:bg-gray-700">Logout</a>
        </div>
    </div>
</nav>