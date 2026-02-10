<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php
$category_id = $_GET['category_id'] ?? null;
$filter = $_GET['filter'] ?? 'all';
$expiry_days = $_GET['expiry_days'] ?? 7;

// Build the query based on filters
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($category_id) {
    $sql .= " AND category_id = ?";
    $params[] = $category_id;
}

switch ($filter) {
    case 'near_expiry':
        $sql .= " AND expiry <= DATE_ADD(CURRENT_DATE, INTERVAL ? DAY) AND expiry >= CURRENT_DATE";
        $params[] = $expiry_days;
        break;
    case 'expired':
        $sql .= " AND expiry < CURRENT_DATE";
        break;
    case 'low_stock':
        $sql .= " AND stock > 0 AND stock < 10";
        break;
    case 'out_of_stock':
        $sql .= " AND stock = 0";
        break;
    case 'in_stock':
        $sql .= " AND stock >= 10";
        break;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = getProductCategories();

// Get nearly expired products (within 7 days) for notification
$nearExpiryStmt = $pdo->prepare("
    SELECT name, expiry, DATEDIFF(expiry, CURRENT_DATE) AS days_left
    FROM products
    WHERE expiry IS NOT NULL
    AND expiry >= CURRENT_DATE
    AND expiry <= DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY)
    ORDER BY expiry ASC
");
$nearExpiryStmt->execute();
$nearExpiryProducts = $nearExpiryStmt->fetchAll();
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
                <div class="flex gap-3">
                    <a href="manage-categories.php"
                        class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-3 px-6 rounded-lg shadow-md transition duration-300 action-btn">
                        <i class="fas fa-tags mr-2"></i>Manage Categories
                    </a>
                    <a href="add-product.php"
                        class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-3 px-6 rounded-lg shadow-md transition duration-300 action-btn">
                        <i class="fas fa-plus mr-2"></i>Add New Product
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Expiry Notifications -->
        <?php if (!empty($nearExpiryProducts)): ?>
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-xl p-5 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-0.5"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-bold text-yellow-800 mb-2">
                        <i class="fas fa-clock mr-1"></i>
                        <?= count($nearExpiryProducts) ?> Product<?= count($nearExpiryProducts) > 1 ? 's' : '' ?> Nearly Expired
                    </h3>
                    <div class="space-y-1">
                        <?php foreach ($nearExpiryProducts as $ep): ?>
                        <p class="text-sm text-yellow-700">
                            <span class="font-semibold"><?= htmlspecialchars($ep['name']) ?></span>
                            — expires on <span class="font-semibold"><?= date('M j, Y', strtotime($ep['expiry'])) ?></span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold ml-1 <?= $ep['days_left'] <= 2 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                <?= $ep['days_left'] == 0 ? 'Today!' : $ep['days_left'] . ' day' . ($ep['days_left'] > 1 ? 's' : '') . ' left' ?>
                            </span>
                        </p>
                        <?php endforeach; ?>
                    </div>
                    <a href="products.php?filter=near_expiry&expiry_days=7#products-table" class="inline-block mt-2 text-sm font-semibold text-yellow-800 hover:text-yellow-900 underline">
                        View all near-expiry products &rarr;
                    </a>
                </div>
                <button onclick="this.closest('.mb-6').style.display='none'" class="flex-shrink-0 ml-4 text-yellow-400 hover:text-yellow-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo count($products); ?></div>
                <div class="text-gray-600 font-medium">Total Products</div>
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
                <div class="text-gray-600 font-medium">Total Worth of Products</div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Category Filters -->
            <div id="filters" class="bg-gradient-to-r from-gray-50 to-white p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter by Category</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="products.php?filter=<?php echo $filter; ?>#filters"
                        class="category-filter px-6 py-3 rounded-full font-medium shadow-sm <?php echo !$category_id ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-th-large mr-2"></i>All Products
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="products.php?category_id=<?php echo $category['id']; ?>&filter=<?php echo $filter; ?>#filters"
                            class="category-filter px-6 py-3 rounded-full font-medium shadow-sm <?php echo $category_id == $category['id'] ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                            <i class="fas fa-tag mr-2"></i><?php echo $category['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-filter mr-2"></i>Advanced Filters
                    </h3>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=all#filters"
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium shadow-sm <?php echo $filter == 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-list mr-1"></i>All
                    </a>
                    <a href="products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=near_expiry&expiry_days=7#filters"
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium shadow-sm <?php echo $filter == 'near_expiry' ? 'bg-yellow-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Near Expiry (<?php echo $expiry_days; ?> days)
                    </a>
                    <a href="products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=expired#filters"
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium shadow-sm <?php echo $filter == 'expired' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-times-circle mr-1"></i>Expired
                    </a>
                    <a href="products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=in_stock#filters"
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium shadow-sm <?php echo $filter == 'in_stock' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-check-circle mr-1"></i>In Stock
                    </a>
                    <a href="products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=low_stock#filters"
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium shadow-sm <?php echo $filter == 'low_stock' ? 'bg-yellow-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-exclamation-circle mr-1"></i>Low Stock
                    </a>
                    <a href="products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=out_of_stock#filters"
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium shadow-sm <?php echo $filter == 'out_of_stock' ? 'bg-gray-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'; ?>">
                        <i class="fas fa-ban mr-1"></i>Out of Stock
                    </a>
                </div>
                <?php if ($filter == 'near_expiry'): ?>
                    <div class="mt-3">
                        <label for="expiry_days_select" class="text-sm text-gray-600 mr-2">Show products expiring within:</label>
                        <select id="expiry_days_select" onchange="window.location.href='products.php?<?php echo $category_id ? 'category_id=' . $category_id . '&' : ''; ?>filter=near_expiry&expiry_days='+this.value+'#filters'" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                            <option value="3" <?php echo $expiry_days == 3 ? 'selected' : ''; ?>>3 days</option>
                            <option value="7" <?php echo $expiry_days == 7 ? 'selected' : ''; ?>>7 days</option>
                            <option value="14" <?php echo $expiry_days == 14 ? 'selected' : ''; ?>>14 days</option>
                            <option value="30" <?php echo $expiry_days == 30 ? 'selected' : ''; ?>>30 days</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Products Table -->
            <div id="products-table" class="overflow-x-auto">
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
                                            <img src="../assets/uploads/<?php echo $product['image'] ?: 'default-product.svg'; ?>"
                                                alt="<?php echo $product['name']; ?>"
                                                class="w-12 h-12 object-cover rounded-xl shadow-md"
                                                onerror="this.onerror=null; this.src='../assets/uploads/default-product.svg';">
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