<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>

<!-- Hero Section -->
<div style="background: linear-gradient(135deg, #12B3EB 0%, #5460F9 100%);" class="py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-6xl py-4 mx-auto">
            <h1 class="text-5xl font-bold text-white mb-4">FurCare Premium Pet Products</h1>
            <p class="text-xl text-white" style="opacity: 0.9;">Discover our carefully curated collection of high-quality products for your beloved pets</p>
        </div>
    </div>
</div>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Category Navigation -->
        <div class="mb-12">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Products</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto rounded-full"></div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter by Category</h2>
                <div class="flex flex-wrap gap-3">
                    <a href="?category=all"
                        class="inline-flex items-center px-6 py-3 rounded-full text-sm font-medium transition-all duration-200 <?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'bg-blue-600 text-white shadow-lg transform scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:transform hover:scale-105'; ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        All Products
                    </a>
                    <?php foreach (getProductCategories() as $category): ?>
                        <a href="?category=<?php echo $category['id']; ?>"
                            class="inline-flex items-center px-6 py-3 rounded-full text-sm font-medium transition-all duration-200 <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'bg-blue-600 text-white shadow-lg transform scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:transform hover:scale-105'; ?>">
                            <?php echo $category['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <?php
        $products = [];
        if (isset($_GET['category']) && $_GET['category'] != 'all') {
            $products = getProductsByCategory($_GET['category']);
        } else {
            $products = getAllProducts();
        }
        ?>

        <?php if (empty($products)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-200">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Products Found</h3>
                <p class="text-gray-600 text-lg">We couldn't find any products in this category. Try browsing other categories.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($products as $product): ?>
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                        <!-- Product Image -->
                        <div class="relative overflow-hidden">
                            <img src="../assets/uploads/<?php echo $product['image'] ?: 'default-product.jpg'; ?>"
                                alt="<?php echo $product['name']; ?>"
                                class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300">

                            <!-- Stock Badge -->
                            <?php if ($product['stock'] <= 0): ?>
                                <div class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Out of Stock
                                </div>
                            <?php else: ?>
                                <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    In Stock
                                </div>
                            <?php endif; ?>

                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <!-- Product Details -->
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <h3 class="font-bold text-xl text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                    <?php echo $product['name']; ?>
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                    <?php echo $product['description']; ?>
                                </p>
                            </div>

                            <!-- Price and Action -->
                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-3xl font-bold text-blue-600">
                                        ₱<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        Stock: <?php echo $product['stock']; ?>
                                    </span>
                                </div>

                                <?php if ($product['stock'] > 0): ?>
                                    <form action="../php/products/add-to-cart.php" method="POST" class="space-y-3">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                                        <div class="flex items-center space-x-3">
                                            <label class="text-sm font-medium text-gray-700">Qty:</label>
                                            <div class="relative">
                                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>"
                                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                            </div>
                                        </div>

                                        <button type="submit"
                                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13l2.5 2.5"></path>
                                            </svg>
                                            <span>Add to Cart</span>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button disabled
                                        class="w-full bg-gray-400 text-white font-semibold py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center space-x-2 opacity-75">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Out of Stock</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>