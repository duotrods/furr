<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<?php
$report_type = $_GET['type'] ?? 'appointments';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$category = $_GET['category'] ?? 'all';
?>

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Business Reports</h1>
            <p class="text-lg text-gray-600">Comprehensive analytics and insights for your business</p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Filter Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <form method="GET" action="reports.php" class="space-y-4">
                    <input type="hidden" name="type" value="<?php echo $report_type; ?>">

                    <?php if ($report_type == 'appointments'): ?>
                        <!-- Appointments Layout: 4 columns -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-blue-100 font-medium mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-blue-100 font-medium mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-blue-50 transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    Filter
                                </button>
                            </div>
                            <div class="flex items-end">
                                <a href="../php/admin/export_pdf.php?type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Sales Layout: All in one row -->
                        <div class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[200px]">
                                <label for="start_date" class="block text-blue-100 font-medium mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label for="end_date" class="block text-blue-100 font-medium mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label for="category" class="block text-blue-100 font-medium mb-2">Category</label>
                                <select id="category" name="category"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                                    <option value="all" <?php echo $category == 'All Sales' ? 'selected' : ''; ?>>All Sales
                                    </option>
                                    <option value="1" <?php echo $category == 'Pet Food' ? 'selected' : ''; ?>>Pet Food
                                    </option>
                                    <option value="2" <?php echo $category == 'Pet Accessories' ? 'selected' : ''; ?>>Pet
                                        Accessories
                                    </option>
                                    <option value="3" <?php echo $category == 'Pet Milk' ? 'selected' : ''; ?>>Pet Milk
                                    </option>
                                    <option value="4" <?php echo $category == 'Pet Shampoo' ? 'selected' : ''; ?>>Pet Shampoo
                                    </option>
                                    <option value="5" <?php echo $category == 'Pet Treats' ? 'selected' : ''; ?>>Pet Treats
                                    </option>
                                    <option value="6" <?php echo $category == 'Pet Apparels' ? 'selected' : ''; ?>>Pet
                                        Apparels</option>
                                </select>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="submit"
                                    class="bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-blue-50 transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    Filter
                                </button>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="../php/admin/export_pdf.php?type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?><?php echo ($report_type == 'sales' && $category != 'all') ? '&category=' . $category : ''; ?>"
                                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Report Type Tabs -->
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex space-x-8 px-8 py-4">
                    <a href="reports.php?type=appointments"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?php echo $report_type == 'appointments' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'; ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Appointments
                    </a>
                    <a href="reports.php?type=sales"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?php echo $report_type == 'sales' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'; ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Product Sales
                    </a>
                </nav>
            </div>

            <!-- Report Content -->
            <div class="p-8">
                <?php if ($report_type == 'appointments'): ?>
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Appointment Report</h2>
                        <p class="text-gray-600">Detailed view from <?php echo formatDate($start_date); ?> to
                            <?php echo formatDate($end_date); ?>
                        </p>
                    </div>

                    <?php
                    $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price,
u.first_name, u.last_name, u.email, u.phone
FROM appointments a
JOIN services s ON a.service_id = s.id
JOIN users u ON a.user_id = u.id
WHERE a.status = 'completed'
AND a.appointment_date BETWEEN ? AND ?
ORDER BY a.appointment_date DESC, a.appointment_time DESC");
                    $stmt->execute([$start_date, $end_date]);
                    $appointments = $stmt->fetchAll();
                    ?>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Customer</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Service</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Date & Time</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php foreach ($appointments as $appointment): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?>
                                                </div>
                                                <div class="text-sm text-gray-500"><?php echo $appointment['email']; ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $appointment['service_name']; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo formatDate($appointment['appointment_date']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo formatTime($appointment['appointment_time']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
<?php echo $appointment['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : ($appointment['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($appointment['status'] == 'completed' ? 'bg-blue-100 text-blue-800' :
                    'bg-red-100 text-red-800')); ?>">
                                                    <?php echo ucfirst($appointment['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    ₱<?php echo number_format($appointment['service_price'], 2); ?></div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total
                                            Revenue:</td>
                                        <td class="px-6 py-4">
                                            <div class="text-lg font-bold text-green-600">
                                                ₱<?php echo number_format(array_sum(array_column($appointments, 'service_price')), 2); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                <?php elseif ($report_type == 'sales'): ?>
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Product Sales Report</h2>
                        <p class="text-gray-600">Sales overview from <?php echo formatDate($start_date); ?> to
                            <?php echo formatDate($end_date); ?>
                        </p>
                        <?php
                        // Map category values to readable labels
                        $categoryLabels = [
                            'all' => 'All Sales',
                            '1' => 'Pet Food',
                            '2' => 'Pet Accessories',
                            '3' => 'Pet Milk',
                            '4' => 'Pet Shampoo',
                            '5' => 'Pet Treats',
                            '6' => 'Pet Apparels'
                        ];
                        ?>
                        <?php if ($category != 'all'): ?>

                            <p class="text-gray-600">Category:
                                <?php echo htmlspecialchars($categoryLabels[$category] ?? 'Unknown'); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php
                    if ($category != 'all') {
                        // When filtering by category, show individual products from that category
                        $sql = "SELECT o.id as order_id, o.order_date,
                                p.name as product_name,
                                oi.quantity,
                                oi.price,
                                (oi.quantity * oi.price) as total_amount
                                FROM orders o
                                JOIN order_items oi ON o.id = oi.order_id
                                JOIN products p ON oi.product_id = p.id
                                WHERE o.status = 'confirmed'
                                AND o.order_date BETWEEN ? AND ?
                                AND p.category_id = ?
                                ORDER BY o.order_date DESC, o.id, p.name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$start_date, $end_date, $category]);
                    } else {
                        // When showing all categories, show individual products
                        $sql = "SELECT o.id as order_id, o.order_date,
                                p.name as product_name,
                                oi.quantity,
                                oi.price,
                                (oi.quantity * oi.price) as total_amount
                                FROM orders o
                                JOIN order_items oi ON o.id = oi.order_id
                                JOIN products p ON oi.product_id = p.id
                                WHERE o.status = 'confirmed'
                                AND o.order_date BETWEEN ? AND ?
                                ORDER BY o.order_date DESC, o.id, p.name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$start_date, $end_date]);
                    }

                    $sales_data = $stmt->fetchAll();
                    ?>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Order ID</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Product Name</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Quantity</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Unit Price</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php foreach ($sales_data as $sale): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    #<?php echo str_pad($sale['order_id'], 5, '0', STR_PAD_LEFT); ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo formatDate($sale['order_date']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($sale['product_name']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $sale['quantity']; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    ₱<?php echo number_format($sale['price'], 2); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    ₱<?php echo number_format($sale['total_amount'], 2); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total
                                            Sales:</td>
                                        <td class="px-6 py-4">
                                            <div class="text-lg font-bold text-green-600">
                                                ₱<?php echo number_format(array_sum(array_column($sales_data, 'total_amount')), 2); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>