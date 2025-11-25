<?php
require_once(__DIR__ . '/../includes/config.php');
requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$order_id = (int)$_GET['id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.first_name, u.last_name, u.email, u.phone
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['error_message'] = 'Order not found.';
    header('Location: orders.php');
    exit();
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image, p.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Order #<?= str_pad($order_id, 5, '0', STR_PAD_LEFT) ?>
                    </h1>
                    <p class="text-gray-600">Order management and payment verification</p>
                </div>
                <?php
                $back_page = $_GET['from'] ?? 'orders';
                $back_url = $back_page == 'payment-review' ? 'payment-review.php' : 'orders.php';
                $back_text = $back_page == 'payment-review' ? 'Back to Payment Review' : 'Back to Orders';
                ?>
                <a href="<?= $back_url ?>"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <?= $back_text ?>
                </a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-8">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 font-medium"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Order & Customer Information -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Order Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Order Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Order Date</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900"><?= date('F j, Y h:i A', strtotime($order['order_date'])) ?></p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status</label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            <?= $order['status'] === 'payment_review' ? 'bg-purple-100 text-purple-800' : 
                                                ($order['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                                            <span class="w-2 h-2 rounded-full mr-2 
                                                <?= $order['status'] === 'payment_review' ? 'bg-purple-600' : 
                                                    ($order['status'] === 'confirmed' ? 'bg-green-600' : 
                                                    ($order['status'] === 'cancelled' ? 'bg-red-600' : 'bg-yellow-600')) ?>"></span>
                                            <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Payment Method</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900"><?= ucfirst($order['payment_method']) ?></p>
                                </div>
                                
                                <?php if ($order['payment_reference']): ?>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Payment Reference</label>
                                    <p class="mt-1 text-lg font-mono text-gray-900 bg-gray-100 px-3 py-2 rounded-lg"><?= $order['payment_reference'] ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($order['payment_proof']): ?>
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3 block">Payment Proof</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <?php
                                $fileExt = pathinfo($order['payment_proof'], PATHINFO_EXTENSION);
                                if (in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif'])): 
                                ?>
                                    <div class="max-w-md mx-auto">
                                        <img src="<?= BASE_URL . '/' . $order['payment_proof'] ?>" 
                                            alt="Payment Proof" 
                                            class="w-full h-auto rounded-lg shadow-sm border border-gray-200">
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center justify-center p-8 bg-white rounded-lg border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-2">Document File</p>
                                            <a href="<?= BASE_URL . '/' . $order['payment_proof'] ?>" 
                                               target="_blank" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                View Document
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Customer Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Customer Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Full Name</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email Address</label>
                                    <p class="mt-1 text-lg text-gray-900"><?= htmlspecialchars($order['email']) ?></p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Phone Number</label>
                                    <p class="mt-1 text-lg text-gray-900"><?= htmlspecialchars($order['phone']) ?></p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Shipping Address</label>
                                <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900 whitespace-pre-line"><?= htmlspecialchars($order['shipping_address']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items & Actions -->
            <div class="space-y-8">
                <!-- Order Items Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Order Items
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($order_items as $item): ?>
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($item['image'] ?: 'default-product.jpg') ?>" 
                                         alt="<?= htmlspecialchars($item['name']) ?>" 
                                         class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($item['name']) ?></h4>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            Qty: <?= $item['quantity'] ?>
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                            ₱<?= number_format($item['price'], 2) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center text-xl font-bold text-gray-900">
                                <span>Total Amount</span>
                                <span class="text-2xl text-blue-600">₱<?= number_format($order['total_amount'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Verification Actions -->
                <?php if ($order['status'] === 'payment_review'): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-red-50">
                        <h2 class="text-xl font-semibold text-red-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Payment Verification Required
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="../php/admin/orders/confirm-payment.php" method="POST" class="space-y-6">
                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                            
                            <div>
                                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Admin Notes
                                </label>
                                <textarea id="admin_notes" 
                                          name="admin_notes" 
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                          placeholder="Add any notes about this payment verification (optional)"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button type="submit" 
                                        name="action" 
                                        value="confirm" 
                                        class="w-full flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Confirm Payment
                                </button>
                                
                                <button type="submit" 
                                        name="action" 
                                        value="reject" 
                                        class="w-full flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>