<?php require_once __DIR__ . '/../includes/config.php';
requireAdmin();

if (!isset($_GET['id']) || !$product = getProductById($_GET['id'])) {
    header('Location: products.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $category_id = (int) $_POST['category_id'];
    $description = sanitize($_POST['description']);
    $batchno = (int) $_POST['batchno'];
    $expiry = sanitize($_POST['expiry']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    $update_data = [
        'name' => $name,
        'category_id' => $category_id,
        'description' => $description,
        'batchno' => $batchno,
        'expiry' => $expiry,
        'price' => $price,
        'stock' => $stock,
        'id' => $product['id']
    ];

    $sql = "UPDATE products SET name = :name, category_id = :category_id, description = :description, batchno = :batchno, expiry = :expiry, 
            price = :price, stock = :stock";

    // Handle image upload if provided
    if (!empty($_FILES['image']['name'])) {
        $image = uploadProductImage($_FILES['image']);
        if ($image) {
            $sql .= ", image = :image";
            $update_data['image'] = $image;
            // Delete old image if it exists
            if ($product['image']) {
                @unlink("../assets/uploads/{$product['image']}");
            }
        }
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($update_data);

    $_SESSION['success_message'] = 'Product updated successfully!';
    header('Location: products.php');
    exit();
}
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Product</h1>
            <p class="text-lg text-gray-600">Update product information and settings</p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <h2 class="text-2xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <?php echo htmlspecialchars($product['name']); ?>
                </h2>
                <p class="text-blue-100 mt-1">Product ID: #<?php echo $product['id']; ?></p>
            </div>

            <!-- Error Messages -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="mx-8 mt-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">
                                    <?php echo $_SESSION['error_message'];
                                    unset($_SESSION['error_message']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Content -->
            <div class="px-8 py-8">
                <form action="edit-product.php?id=<?php echo $product['id']; ?>" method="POST"
                    enctype="multipart/form-data" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <!-- Product Name -->
                    <div class="group">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-3">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="name" name="name" required
                                value="<?php echo htmlspecialchars($product['name']); ?>"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                placeholder="Enter product name">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="group">
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-3">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-gray-900 appearance-none bg-white">
                                <option value="">Select Category</option>
                                <?php foreach (getProductCategories() as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="group">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">
                            Product Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-gray-900 placeholder-gray-400 resize-none"
                            placeholder="Describe your product..."><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <!-- Batch and Expiration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="batchno" class="block text-sm font-semibold text-slate-700">
                                Batch No. <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="batchno" name="batchno" min="0" required
                                    value="<?php echo $product['batchno']; ?>"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white"
                                    placeholder="Batch No.">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="expiry" class="block text-sm font-semibold text-slate-700">
                                Expiration Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="expiry" name="expiry" required min="<?php echo date('Y-m-d'); ?>"
                                value="<?php echo $product['expiry']; ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white"
                                placeholder="Expiration Date">
                        </div>
                    </div>

                    <!-- Price and Stock Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-3">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-lg font-medium">$</span>
                                </div>
                                <input type="number" id="price" name="price" step="0.01" min="0" required
                                    value="<?php echo $product['price']; ?>"
                                    class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-gray-900"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="group">
                            <label for="stock" class="block text-sm font-semibold text-gray-700 mb-3">
                                Stock Quantity <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="stock" name="stock" min="0" required
                                    value="<?php echo $product['stock']; ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-gray-900"
                                    placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="group">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-3">
                            Product Image
                        </label>

                        <?php if ($product['image']): ?>
                            <div class="mb-6">
                                <div
                                    class="inline-flex items-center space-x-4 p-4 bg-gray-50 rounded-xl border-2 border-gray-100">
                                    <div class="flex-shrink-0">
                                        <img src="../assets/uploads/<?php echo $product['image'] ?: 'default-product.svg'; ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            class="w-20 h-20 object-cover rounded-lg shadow-md border-2 border-white"
                                            onerror="this.onerror=null; this.src='../assets/uploads/default-product.svg';">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Current Image</p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo $product['image']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="relative">
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Leave blank to keep current image. Accepted formats: JPG, PNG, GIF
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-105 focus:ring-4 focus:ring-blue-100 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update Product
                            </span>
                        </button>

                        <a href="products.php"
                            class="flex-1 sm:flex-initial bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 px-8 rounded-xl transition-all duration-200 text-center">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel
                            </span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-100">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-blue-900 mb-1">Important Notes</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Changes will be applied immediately after submission</li>
                        <li>• Uploading a new image will replace the current one</li>
                        <li>• All fields marked with <span class="text-red-600">*</span> are required</li>
                        <li>• Price should be entered without currency symbols</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>