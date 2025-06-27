<?php require_once '../includes/config.php'; ?>
<?php requireAuth(); ?>
<?php require_once  '../includes/header.php'; ?>

<?php
requireAuth();

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$order_id = $_GET['id'];
$order = getOrderById($order_id);

// Only check if order exists and belongs to user
if (!$order || $order['user_id'] != getUserId()) {
    header('Location: products.php');
    exit();
}

if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']); // Clear the cart items
    $_SESSION['cart_count'] = 0; // Reset cart count
}
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                <p class="text-gray-600 text-lg">Secure and fast payment processing</p>

            </div>

            <div class="flex justify-center items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                    <span>Cart</span>
                </div>
                <div class="w-8 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                    <span >Checkout</span>
                </div>
                <div class="w-8 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                    <span class="font-semibold">Confirmation</span>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Order Summary Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-600 font-medium">Order ID</span>
                                <span class="font-bold text-gray-900">#<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-600 font-medium">Payment Method</span>
                                <div class="flex items-center">
                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBmaWxsPSIjMDA5MkZGIi8+Cjwvc3ZnPgo=" alt="GCash" class="w-5 h-5 mr-2">
                                    <span class="font-semibold text-blue-600">GCash</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600 font-medium">Total Amount</span>
                                <span class="text-2xl font-bold text-blue-600">₱<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-800 mb-1">Payment Verification</p>
                                    <p class="text-xs text-blue-700">Your payment will be verified within 24 hours after submission.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                        <!-- Payment Instructions -->
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Payment Instructions</h2>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8">
                                <!-- Step-by-step instructions -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Follow these steps:</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                            <p class="text-gray-700 leading-relaxed">Open your GCash app on your mobile device</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                            <p class="text-gray-700 leading-relaxed">Navigate to "Send Money" option</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                                            <div>
                                                <p class="text-gray-700 leading-relaxed mb-1">Enter recipient number:</p>
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 font-mono text-lg font-bold text-blue-600">
                                                    09519444911
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</div>
                                            <div>
                                                <p class="text-gray-700 leading-relaxed mb-1">Enter exact amount:</p>
                                                <div class="bg-green-50 border border-green-200 rounded-lg px-3 py-2 text-lg font-bold text-green-600">
                                                    ₱<?php echo number_format($order['total_amount'], 2); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</div>
                                            <div>
                                                <p class="text-gray-700 leading-relaxed mb-1">Add note with Order ID:</p>
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 font-mono text-lg font-bold text-yellow-700">
                                                    #<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</div>
                                            <p class="text-gray-700 leading-relaxed">Complete the transaction and take a screenshot</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code Section -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Or scan QR code:</h3>
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-dashed border-blue-200 rounded-2xl p-6 text-center">
                                        <div class="bg-white rounded-xl p-4 shadow-sm inline-block">
                                            <img src="../assets/images/qrcode/gcash1.jpg" alt="GCash QR Code" class="mx-auto w-40 h-40 object-contain">
                                        </div>
                                        <p class="mt-4 text-sm font-semibold text-gray-700">Scan with GCash app</p>
                                        <p class="text-xs text-gray-500 mt-1">Order: #<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Proof Upload Form -->
                        <div class="border-t border-gray-200 pt-8">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Submit Payment Proof</h2>
                            </div>

                            <form action="../php/products/process-payment.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

                                <div>
                                    <label for="payment_proof" class="block text-sm font-semibold text-gray-700 mb-3">Upload Payment Screenshot</label>
                                    <div class="relative">
                                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required
                                            class="w-full px-4 py-16 border-2 border-dashed border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <div class="absolute top-0 left-0 w-full h-full pointer-events-none flex items-center justify-center" id="upload-placeholder">
                                            <div class="text-center text-gray-500">
                                                <svg class="mx-auto w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="text-sm">Click to upload or drag and drop</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Upload a clear screenshot of your GCash transaction receipt (JPG, PNG, or PDF)
                                    </p>
                                </div>

                                <div>
                                    <label for="reference_number" class="block text-sm font-semibold text-gray-700 mb-3">GCash Reference Number</label>
                                    <div class="relative">
                                        <input type="text" id="reference_number" name="reference_number" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 pl-12"
                                            placeholder="Enter reference number from your receipt">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Submit Payment Verification
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced file upload experience
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        const placeholder = document.getElementById('upload-placeholder');
        if (e.target.files.length > 0) {
            placeholder.style.display = 'none';
        } else {
            placeholder.style.display = 'flex';
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>