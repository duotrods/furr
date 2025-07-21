<?php
require_once '../includes/config.php';
requireAuth();
require_once '../includes/header.php';

// Validate order ID
if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$order_id = (int) $_GET['id'];

// Get order details
try {
    $stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, u.email 
                          FROM orders o
                          JOIN users u ON o.user_id = u.id
                          WHERE o.id = ? AND o.user_id = ?");
    $stmt->execute([$order_id, getUserId()]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception('Order not found.');
    }

    // Get order items
    $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image 
                          FROM order_items oi
                          JOIN products p ON oi.product_id = p.id
                          WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Database error occurred.';
    header('Location: products.php');
    exit();
}
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="container mx-auto px-4 py-12 max-w-7xl">
        <!-- Success Message -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-emerald-700 font-medium">
                        <?php echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-slate-800 mb-2">Order Details</h1>
                    <div class="flex items-center space-x-3">
                        <span
                            class="text-2xl font-mono text-slate-600">#<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></span>
                        <?php
                        $status = $order['status'] ?? 'pending';
                        $statusConfig = [
                            'pending' => ['bg-yellow-100', 'text-yellow-800', 'border-yellow-200'],
                            'payment_review' => ['bg-blue-100', 'text-blue-800', 'border-blue-200'],
                            'confirmed' => ['bg-emerald-100', 'text-emerald-800', 'border-emerald-200'],
                            'shipped' => ['bg-purple-100', 'text-purple-800', 'border-purple-200'],
                            'completed' => ['bg-green-100', 'text-green-800', 'border-green-200'],
                            'cancelled' => ['bg-red-100', 'text-red-800', 'border-red-200']
                        ];
                        $statusClass = $statusConfig[$status] ?? ['bg-gray-100', 'text-gray-800', 'border-gray-200'];
                        ?>
                        <span
                            class="px-4 py-2 rounded-full text-sm font-semibold border <?php echo implode(' ', $statusClass); ?>">
                            <?php echo ucwords(str_replace('_', ' ', $status)); ?>
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-500 mb-1">Order Date</p>
                    <p class="text-lg font-semibold text-slate-700">
                        <?php echo date('M j, Y', strtotime($order['order_date'])); ?></p>
                    <p class="text-sm text-slate-500"><?php echo date('h:i A', strtotime($order['order_date'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <!-- Order Items Section -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-8 py-6 border-b border-slate-200">
                        <h2 class="text-2xl font-bold text-slate-800 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Order Items
                        </h2>
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <?php foreach ($order_items as $index => $item): ?>
                                <div
                                    class="flex items-center space-x-6 p-6 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <img src="../assets/uploads/<?php echo $item['image'] ?: 'default-product.jpg'; ?>"
                                            alt="<?php echo $item['name']; ?>"
                                            class="w-20 h-20 object-cover rounded-xl shadow-md border border-slate-200">
                                    </div>

                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold text-slate-800 mb-2"><?php echo $item['name']; ?>
                                        </h3>
                                        <div class="flex items-center space-x-4 text-sm text-slate-600">
                                            <span class="bg-white px-3 py-1 rounded-full border">Qty:
                                                <?php echo $item['quantity']; ?></span>
                                            <span
                                                class="bg-white px-3 py-1 rounded-full border">₱<?php echo number_format($item['price'], 2); ?>
                                                each</span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-slate-800">
                                            ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Order Total -->
                        <div class="mt-8 pt-6 border-t-2 border-slate-200">
                            <div class="flex justify-between items-center p-6 bg-slate-800 text-white rounded-xl">
                                <span class="text-xl font-semibold">Total Amount</span>
                                <span
                                    class="text-3xl font-bold">₱<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Payment Details
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Method</span>
                            <span class="font-semibold text-slate-800 bg-slate-100 px-3 py-1 rounded-full">
                                <?php echo ucfirst($order['payment_method']); ?>
                            </span>
                        </div>

                        <?php if ($order['payment_reference']): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Reference</span>
                                <span class="font-mono text-sm text-slate-800 bg-slate-100 px-3 py-1 rounded">
                                    <?php echo $order['payment_reference']; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($order['payment_proof']): ?>
                            <div class="pt-4 border-t border-slate-200">
                                <a href="<?php echo BASE_URL . '/' . $order['payment_proof']; ?>" target="_blank"
                                    class="flex items-center justify-center w-full p-3 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors duration-200 border border-blue-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Payment Receipt
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A6 6 0 0112 14a6 6 0 016.879 3.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buyer Information
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-500 uppercase tracking-wide">Full Name</label>
                            <p class="text-slate-800 font-semibold mt-1">
                                <?php echo $order['first_name'] . ' ' . $order['last_name']; ?></p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-500 uppercase tracking-wide">Email</label>
                            <p class="text-slate-800 mt-1"><?php echo $order['email']; ?></p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-500 uppercase tracking-wide">Contact</label>
                            <p class="text-slate-800 font-mono mt-1"><?php echo $order['contact_number']; ?></p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-500 uppercase tracking-wide">Address</label>
                            <div class="mt-2 p-4 bg-slate-50 rounded-lg border">
                                <p class="text-slate-800 leading-relaxed">
                                    <?php echo nl2br($order['shipping_address']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <a href="products.php"
                        class="flex items-center justify-center w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>