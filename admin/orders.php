<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>

<?php
$status = $_GET['status'] ?? 'all';

// Build the SQL query based on status filter
$sql = "
    SELECT o.id, o.order_date, o.total_amount, o.payment_method,
           o.payment_reference, o.status, u.first_name, u.last_name,
           COUNT(oi.id) AS item_count
    FROM orders o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
";

if ($status != 'all') {
    $sql .= " WHERE o.status = :status";
}

$sql .= " GROUP BY o.id ORDER BY o.order_date DESC";

$stmt = $pdo->prepare($sql);
if ($status != 'all') {
    $stmt->execute(['status' => $status]);
} else {
    $stmt->execute();
}
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get counts for each status
$statusCounts = [];
$countStmt = $pdo->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
while ($row = $countStmt->fetch(PDO::FETCH_ASSOC)) {
    $statusCounts[$row['status']] = $row['count'];
}
$totalCount = array_sum($statusCounts);
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-slate-800 mb-2">Order Management</h1>
                    <p class="text-slate-600">Manage and track all product orders</p>
                </div>
                <div class="flex gap-4">
                    <a href="payment-review.php"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Payment Review
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
                        All Orders
                        <?php if ($totalCount > 0): ?>
                            <span
                                class="ml-2 px-2 py-0.5 rounded-full text-xs <?php echo $status == 'all' ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-700'; ?>">
                                <?php echo $totalCount; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="?status=payment_review"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'payment_review' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-clock mr-2"></i>
                        Payment Review
                        <?php if (isset($statusCounts['payment_review']) && $statusCounts['payment_review'] > 0): ?>
                            <span
                                class="ml-2 px-2 py-0.5 rounded-full text-xs <?php echo $status == 'payment_review' ? 'bg-blue-500 text-white' : 'bg-yellow-200 text-yellow-800'; ?>">
                                <?php echo $statusCounts['payment_review']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="?status=confirmed"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'confirmed' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmed
                        <?php if (isset($statusCounts['confirmed']) && $statusCounts['confirmed'] > 0): ?>
                            <span
                                class="ml-2 px-2 py-0.5 rounded-full text-xs <?php echo $status == 'confirmed' ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-700'; ?>">
                                <?php echo $statusCounts['confirmed']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="?status=cancelled"
                        class="inline-flex items-center px-5 py-2.5 rounded-xl font-medium transition-all duration-200 <?php echo $status == 'cancelled' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                        <i class="fas fa-times-circle mr-2"></i>
                        Cancelled
                        <?php if (isset($statusCounts['cancelled']) && $statusCounts['cancelled'] > 0): ?>
                            <span
                                class="ml-2 px-2 py-0.5 rounded-full text-xs <?php echo $status == 'cancelled' ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-700'; ?>">
                                <?php echo $statusCounts['cancelled']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <?php if (count($orders) === 0): ?>
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No orders found</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            <?php echo $status == 'all' ? 'No orders have been placed yet.' : 'No orders with this status.'; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-gradient-to-r from-slate-700 to-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-2"></i>Order ID
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                    <i class="fas fa-user mr-2"></i>Customer
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-2"></i>Order Date
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                    <i class="fas fa-boxes mr-2"></i>Items
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Amount
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-black uppercase tracking-wider">
                                    <i class="fas fa-credit-card mr-2"></i>Payment
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
                            <?php foreach ($orders as $order): ?>
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                                    <i class="fas fa-shopping-bag text-white text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">
                                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 flex items-center">
                                            <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                            <?php echo date('M j, Y', strtotime($order['order_date'])); ?>
                                        </div>
                                        <div class="text-sm text-slate-500 flex items-center">
                                            <i class="fas fa-clock text-slate-400 mr-2"></i>
                                            <?php echo date('g:i A', strtotime($order['order_date'])); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                            <i class="fas fa-box mr-1"></i>
                                            <?php echo $order['item_count']; ?>
                                            <?php echo $order['item_count'] == 1 ? 'item' : 'items'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-green-600 flex items-center">
                                            <i class="fas fa-peso-sign text-xs mr-1"></i>
                                            <?php echo number_format($order['total_amount'], 2); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                            <?php echo $order['payment_method'] == 'gcash' ? 'bg-blue-100 text-blue-800 border border-blue-200' :
                                                ($order['payment_method'] == 'maya' ? 'bg-green-100 text-green-800 border border-green-200' :
                                                    'bg-gray-100 text-gray-800 border border-gray-200'); ?>">
                                            <?php echo ucfirst($order['payment_method']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm
                                        <?php echo $order['status'] == 'confirmed' ? 'bg-green-100 text-green-800 border border-green-200' :
                                            ($order['status'] == 'payment_review' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                                                ($order['status'] == 'completed' ? 'bg-blue-100 text-blue-800 border border-blue-200' :
                                                    ($order['status'] == 'processing' ? 'bg-purple-100 text-purple-800 border border-purple-200' :
                                                        ($order['status'] == 'shipped' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' :
                                                            'bg-red-100 text-red-800 border border-red-200')))); ?>">
                                            <?php
                                            $statusIcons = [
                                                'confirmed' => 'fas fa-check-circle',
                                                'payment_review' => 'fas fa-clock',
                                                'completed' => 'fas fa-check-double',
                                                'processing' => 'fas fa-spinner',
                                                'shipped' => 'fas fa-truck',
                                                'cancelled' => 'fas fa-times-circle'
                                            ];
                                            ?>
                                            <i
                                                class="<?php echo $statusIcons[$order['status']] ?? 'fas fa-question-circle'; ?> mr-1"></i>
                                            <?php echo ucwords(str_replace('_', ' ', $order['status'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <a href="order-details.php?id=<?php echo $order['id']; ?>"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary Stats -->
        <?php if (count($orders) > 0): ?>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Total Orders</p>
                            <p class="text-2xl font-bold text-slate-800 mt-1"><?php echo count($orders); ?></p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">
                                ₱<?php echo number_format(array_sum(array_column($orders, 'total_amount')), 2); ?>
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-peso-sign text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Total Items</p>
                            <p class="text-2xl font-bold text-slate-800 mt-1">
                                <?php echo array_sum(array_column($orders, 'item_count')); ?>
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-boxes text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>