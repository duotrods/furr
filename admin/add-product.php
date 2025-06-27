<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-slate-800 mb-2">Add New Product</h1>
                <p class="text-slate-600">Fill in the details below to add a new product to your inventory</p>
            </div>
            
            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Product Information
                    </h2>
                </div>
                
                <div class="p-8">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <form action="../php/admin/add-product-process.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <!-- Product Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white"
                                   placeholder="Enter product name">
                        </div>
                        
                        <!-- Category -->
                        <div class="space-y-2">
                            <label for="category_id" class="block text-sm font-semibold text-slate-700">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="category_id" name="category_id" required 
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white appearance-none">
                                    <option value="">Select Category</option>
                                    <?php foreach (getProductCategories() as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-slate-700">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white resize-vertical"
                                      placeholder="Enter product description (optional)"></textarea>
                        </div>
                        
                        <!-- Price and Stock Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="price" class="block text-sm font-semibold text-slate-700">
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 text-lg">₱</span>
                                    </div>
                                    <input type="number" id="price" name="price" step="0.01" min="0" required 
                                           class="w-full pl-8 pr-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="stock" class="block text-sm font-semibold text-slate-700">
                                    Stock Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stock" name="stock" min="0" required 
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-slate-50 focus:bg-white"
                                       placeholder="0">
                            </div>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <label for="image" class="block text-sm font-semibold text-slate-700">
                                Product Image
                            </label>
                            <div class="border-2 border-dashed border-slate-300 rounded-lg p-6 hover:border-blue-400 transition-colors duration-200">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="mt-4">
                                        <label for="image" class="cursor-pointer">
                                            <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                Upload Image
                                            </span>
                                            <input type="file" id="image" name="image" accept="image/*" class="sr-only">
                                        </label>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">PNG, JPG, GIF up to 10MB</p>
                                    <p class="text-xs text-slate-500">Recommended size: 500x500 pixels</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="pt-6 border-t border-slate-200">
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Product to Inventory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Footer Note -->
            <div class="text-center mt-8">
                <p class="text-slate-500 text-sm">
                    <span class="text-red-500">*</span> Required fields must be completed
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>