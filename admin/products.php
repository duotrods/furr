<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php
$category_id = $_GET['category_id'] ?? null;
$products = $category_id ? getProductsByCategory($category_id) : getAllProducts();
$categories = getProductCategories();
?>

<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .category-filter {
        transition: all 0.2s ease;
    }

    .category-filter:hover {
        transform: translateY(-1px);
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: scale(1.05);
    }

    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .table-row:hover {
        background-color: #f8fafc;
    }

    .stats-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
    }
</style>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="gradient-bg shadow-lg">
        <div class="container mx-auto px-6 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Product Management</h1>
                    <p class="text-blue-100">Manage your inventory with ease</p>
                </div>
                <a href="add-product.php"
                    class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-3 px-6 rounded-lg shadow-md transition duration-300 action-btn">
                    <i class="fas fa-plus mr-2"></i>Add New Product
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo count($products); ?></div>
                <div class="text-gray-600 font-medium">Total Products</div>
            </div>
            <div class="stats-card rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl font-bold text-green-600 mb-2"><?php echo count($categories); ?></div>
                <div class="text-gray-600 font-medium">Categories</div>
            </div>
            <div class="stats-card rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    <?php echo array_sum(array_column($products, 'stock')); ?>
                </div>
                <div class="text-gray-600 font-medium">Total Stock</div>
            </div>
            <div class="stats-card rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    ₱<?php echo number_format(array_sum(array_map(function ($p) {
                        return $p['price'] * $p['stock'];
                    }, $products)), 2); ?>
                </div>
                <div class="text-gray-600 font-medium">Total Value</div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Category Filters -->
            <div class="bg-gradient-to-r from-gray-50 to-white p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter by Category</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="products.php"
                        class="category-filter px-6 py-3 rounded-full font-medium shadow-sm <?php echo !$category_id ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-th-large mr-2"></i>All Products
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="products.php?category_id=<?php echo $category['id']; ?>"
                            class="category-filter px-6 py-3 rounded-full font-medium shadow-sm <?php echo $category_id == $category['id'] ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                            <i class="fas fa-tag mr-2"></i><?php echo $category['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Product</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Details</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Batch No</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Expiration Date</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Price</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Stock</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Status</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($products as $product):
                            $category = getProductCategoryById($product['category_id']);
                            ?>
                            <tr class="table-row transition-colors duration-200">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            <img src="../assets/uploads/<?php echo $product['image'] ?: 'default-product.jpg'; ?>"
                                                alt="<?php echo $product['name']; ?>"
                                                class="w-12 h-12 object-cover rounded-xl shadow-md">
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-1 ">
                                        <div class="text-md font-semibold text-gray-900"><?php echo $product['name']; ?>
                                        </div>
                                        <div class="text-sm text-gray-500 max-w-xs truncate">
                                            <?php echo $product['description']; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-sm font-semibold text-gray-900"><?php echo $product['batchno']; ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-sm font-semibold text-gray-900"><?php echo $product['expiry']; ?></span>
                                    </div>
                                </td>
                                <!-- <td class="px-8 py-6">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-1"></i><?php echo $category['name']; ?>
                                    </span>
                                </td> -->
                                <td class="px-8 py-6">
                                    <div class="text-sm font-semibold text-green-600">
                                        ₱<?php echo number_format($product['price'], 2); ?></div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-sm font-semibold text-gray-900"><?php echo $product['stock']; ?></span>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2">
                                        <a class="px-2 py-1 rounded-full text-xs font-medium <?php
                                        echo $product['stock'] == 0
                                            ? 'bg-gray-100 text-gray-600'
                                            : ($product['stock'] > 10
                                                ? 'bg-green-100 text-green-800'
                                                : ($product['stock'] > 5
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-red-100 text-red-800')); ?>">
                                            <?php
                                            echo $product['stock'] == 0
                                                ? 'Out of Stock'
                                                : ($product['stock'] > 10
                                                    ? 'In Stock'
                                                    : ($product['stock'] > 5
                                                        ? 'Low Stock'
                                                        : 'Very Low'));
                                            ?>
                                        </a>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-3">
                                        <a href="edit-product.php?id=<?php echo $product['id']; ?>"
                                            class="action-btn inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg shadow-sm">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </a>
                                        <form action="../php/admin/delete-product.php" method="POST" class="inline">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit"
                                                class="action-btn inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg shadow-sm"
                                                onclick="return confirm('Are you sure you want to delete this product?');">
                                                <i class="fas fa-trash-alt mr-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State (if no products) -->
        <?php if (empty($products)): ?>
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500 mb-6">Get started by adding your first product to the inventory.</p>
                <a href="add-product.php"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-plus mr-2"></i>Add Your First Product
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>