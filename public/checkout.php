<?php require_once '../includes/config.php'; ?>
<?php requireAuth(); ?>
<?php require_once '../includes/header.php'; ?>

<?php
$cartItems = getCartItems();
if (empty($cartItems)) {
    $_SESSION['error_message'] = 'Your cart is empty.';
    header('Location: products.php');
    exit();
}

$user = getUser();
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Secure Checkout</h1>
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
                    <span class="font-semibold">Checkout</span>
                </div>
                <div class="w-8 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                    <span>Confirmation</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Shipping Information Form -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m-6-8h.01M9 12h.01M9 16h.01">
                                </path>
                            </svg>
                            Order Information
                        </h2>
                        <p class="text-blue-100 mt-1">Please provide your details.</p>
                    </div>

                    <div class="p-8">
                        <form id="checkout-form" action="../php/products/checkout-process.php" method="POST"
                            class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                            <!-- Name Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="first_name" class="block text-sm font-semibold text-gray-700">First
                                        Name</label>
                                    <input type="text" id="first_name" name="first_name" required
                                        value="<?php echo $user['first_name']; ?>"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                                <div class="space-y-2">
                                    <label for="last_name" class="block text-sm font-semibold text-gray-700">Last
                                        Name</label>
                                    <input type="text" id="last_name" name="last_name" required
                                        value="<?php echo $user['last_name']; ?>"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700">Email
                                        Address</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="email" id="email" name="email" required
                                            value="<?php echo $user['email']; ?>"
                                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 focus:bg-white">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700">Phone
                                        Number</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="tel" id="phone" name="phone" required maxlength="11"
                                            pattern="09[0-9]{9}" title="Please enter an 11-digit phone number starting with 09"
                                            value="<?php echo $user['phone']; ?>"
                                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 focus:bg-white">
                                    </div>
                                    <p id="phone-error-checkout" class="text-xs text-red-600 font-medium hidden">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Letters are not allowed! Please enter numbers only.
                                    </p>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="space-y-2">
                                <label for="shipping_address"
                                    class="block text-sm font-semibold text-gray-700">Address</label>
                                <textarea id="shipping_address" name="shipping_address" rows="4" required
                                    placeholder="Enter your complete address including street, city, state, and postal code"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"><?php echo $user['address']; ?></textarea>
                            </div>

                            <!-- Payment Method -->
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700">Payment Method</label>
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="payment_method" value="gcash"
                                            class="w-5 h-5 text-blue-600 border-2 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                            checked>
                                        <div class="ml-4 flex items-center">
                                            <div class="bg-blue-600 rounded-lg p-2 mr-3">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M2 10h20v2H2v-2zm0 4h20v2H2v-2zm0-8h20v2H2V6z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-900">GCash</span>
                                                <p class="text-sm text-gray-600">Fast and secure digital payment</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span>Proceed to Payment</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Order Summary
                        </h2>
                        <p class="text-gray-300 mt-1">Review your items</p>
                    </div>

                    <div class="p-8">
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <?php foreach ($cartItems as $product_id => $quantity):
                                $product = getProductById($product_id);
                                if (!$product)
                                    continue;
                                ?>
                                <div
                                    class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
                                    <div class="flex-shrink-0">
                                        <img src="../assets/uploads/<?php echo $product['image'] ?: 'default-product.svg'; ?>"
                                            alt="<?php echo $product['name']; ?>"
                                            class="w-20 h-20 object-cover rounded-lg border-2 border-white shadow-sm"
                                            onerror="this.onerror=null; this.src='../assets/uploads/default-product.svg';">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 truncate"><?php echo $product['name']; ?>
                                        </h3>
                                        <div class="flex items-center mt-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Qty: <?php echo $quantity; ?>
                                            </span>
                                        </div>
                                        <p class="text-lg font-bold text-gray-900 mt-2">
                                            ₱<?php echo number_format($product['price'] * $quantity, 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Total Section -->
                        <div class="mt-8 pt-6 border-t-2 border-gray-200">
                            <div
                                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-gray-900">Total Amount</span>
                                    <span
                                        class="text-2xl font-bold text-green-600">₱<?php echo number_format(calculateCartTotal(), 2); ?></span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Including all applicable taxes</p>
                            </div>
                        </div>

                        <!-- Security Badge -->
                        <div
                            class="mt-6 flex items-center justify-center space-x-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Secure SSL encrypted checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Phone number validation - prevent letters
document.getElementById('phone').addEventListener('input', function(e) {
    const errorMsg = document.getElementById('phone-error-checkout');
    const value = e.target.value;

    // Check if input contains non-numeric characters
    if (/[^0-9]/.test(value)) {
        // Remove non-numeric characters
        e.target.value = value.replace(/[^0-9]/g, '');

        // Show error message
        errorMsg.classList.remove('hidden');
        e.target.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        e.target.classList.remove('border-gray-200', 'focus:ring-blue-500', 'focus:ring-blue-200');

        // Hide error after 3 seconds
        setTimeout(function() {
            errorMsg.classList.add('hidden');
            e.target.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            e.target.classList.add('border-gray-200', 'focus:ring-blue-500', 'focus:ring-blue-200');
        }, 3000);
    }
});

</script>

<?php require_once '../includes/footer.php'; ?>