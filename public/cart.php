<?php require_once '../includes/config.php';
// Add this cart count calculation
if (!isset($_SESSION['cart_count'])) {
    $_SESSION['cart_count'] = 0;
}
if (!isset($_SESSION['cart_count']) || !isset($_SESSION['cart'])) {
    $_SESSION['cart_count'] = 0;
}
$cart_items = getCartItems();
$_SESSION['cart_count'] = !empty($cart_items) ? array_sum($cart_items) : 0;
require_once '../includes/header.php';  ?>



<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
                <p class="text-gray-600 text-lg">Secure and fast payment processing</p>

            </div>

            <div class="flex justify-center items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                    <span class="font-semibold">Cart</span>
                </div>
                <div class="w-8 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                    <span>Checkout</span>
                </div>
                <div class="w-8 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                    <span>Confirmation</span>
                </div>
            </div>
        </div>

        <?php if (empty(getCartItems())): ?>
            <!-- Empty Cart State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-3xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-3">Your cart is empty</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Looks like you haven't added any items to your cart yet. Start shopping to fill it up!</p>
                <a href="products.php" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Cart Items -->
                <div class="xl:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Desktop Header -->
                        <div class="hidden lg:grid grid-cols-12 bg-gray-50 px-8 py-4 border-b border-gray-200">
                            <div class="col-span-5">
                                <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Product</span>
                            </div>
                            <div class="col-span-2 text-center">
                                <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Price</span>
                            </div>
                            <div class="col-span-2 text-center">
                                <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Quantity</span>
                            </div>
                            <div class="col-span-2 text-center">
                                <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Total</span>
                            </div>
                            <div class="col-span-1 text-center">
                                <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Remove</span>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="divide-y divide-gray-200">
                            <?php foreach (getCartItems() as $product_id => $quantity):
                                $product = getProductById($product_id);
                                if (!$product) continue;
                            ?>
                                <div class="grid grid-cols-1 lg:grid-cols-12 p-6 lg:p-8 items-center hover:bg-gray-50 transition duration-200">
                                    <!-- Product Info -->
                                    <div class="col-span-1 lg:col-span-5 flex items-center mb-4 lg:mb-0">
                                        <div class="w-20 h-20 lg:w-16 lg:h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 mr-4">
                                            <img src="../assets/uploads/<?php echo $product['image'] ?: 'default-product.svg'; ?>"
                                                alt="<?php echo $product['name']; ?>"
                                                class="w-full h-full object-cover"
                                                onerror="this.onerror=null; this.src='../assets/uploads/default-product.svg';">
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-lg mb-1"><?php echo $product['name']; ?></h3>
                                            <p class="text-gray-500 text-sm"><?php echo getProductCategoryName($product['category_id']); ?></p>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-span-1 lg:col-span-2 text-left lg:text-center mb-4 lg:mb-0">
                                        <div class="lg:hidden">
                                            <span class="text-sm font-medium text-gray-500">Price: </span>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900">₱<?php echo number_format($product['price'], 2); ?></span>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-span-1 lg:col-span-2 text-left lg:text-center mb-4 lg:mb-0">
                                        <div class="lg:hidden mb-2">
                                            <span class="text-sm font-medium text-gray-500">Quantity:</span>
                                        </div>
                                        <form action="../php/products/update-cart.php" method="POST" class="flex items-center justify-start lg:justify-center">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                <input type="number" name="quantity" value="<?php echo $quantity; ?>"
                                                    min="1" max="<?php echo $product['stock']; ?>"
                                                    class="w-16 px-3 py-2 text-center border-0 focus:ring-0 focus:outline-none">
                                                <button type="submit" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 border-l border-gray-300 transition duration-200">
                                                    <i class="fas fa-sync-alt text-gray-600 text-sm"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Total -->
                                    <div class="col-span-1 lg:col-span-2 text-left lg:text-center mb-4 lg:mb-0">
                                        <div class="lg:hidden">
                                            <span class="text-sm font-medium text-gray-500">Total: </span>
                                        </div>
                                        <span class="text-lg font-bold text-gray-900">₱<?php echo number_format($product['price'] * $quantity, 2); ?></span>
                                    </div>

                                    <!-- Remove -->
                                    <div class="col-span-1 lg:col-span-1 text-left lg:text-center">
                                        <form action="../php/products/remove-from-cart.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="inline-flex items-center justify-center w-10 h-10 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg transition duration-200">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-6">
                        <a href="products.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 sticky top-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-700">Subtotal</span>
                                <span class="font-semibold text-gray-900">₱<?php echo number_format(calculateCartTotal(), 2); ?></span>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-gray-900">₱<?php echo number_format(calculateCartTotal(), 2); ?></span>
                                </div>
                            </div>
                        </div>

                        <a href="checkout.php" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl text-center transition duration-300 shadow-sm hover:shadow-md">
                            Proceed to Checkout
                        </a>

                        <!-- Security Badge -->
                        <div class="mt-6 text-center">
                            <div class="flex items-center justify-center text-sm text-gray-500">
                                <i class="fas fa-lock mr-2"></i>
                                Secure checkout guaranteed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>