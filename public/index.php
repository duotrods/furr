<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>

<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Main Hero -->
    <div class="container mx-auto px-4 pt-12 pb-16">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Hero Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <div class="p-8 lg:p-12 flex flex-col justify-center">
                    <div class="mb-6">
                        <span
                            class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mb-4">
                            Premium Pet Care - Best in Town
                        </span>
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                            Professional Pet <span class="text-blue-600">Grooming</span> with Loving Care
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            Expert grooming services for your beloved pets. We provide gentle, professional care in a
                            stress-free environment for the perfect balance of style, health, and happiness.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="services.php"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg text-center flex items-center justify-center">
                            <span class="mr-2">Book Now</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                        <a href="#products"
                            class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-4 px-8 rounded-xl transition duration-300 text-center">
                            Shop Products
                        </a>
                    </div>



                    <!-- Quick Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">500+</div>
                            <div class="text-sm text-gray-600">Happy Pets</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">5+</div>
                            <div class="text-sm text-gray-600">Years Experience</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">100%</div>
                            <div class="text-sm text-gray-600">Satisfaction</div>
                        </div>
                    </div>
                </div>

                <div class="relative bg-white lg:h-auto min-h-96 overflow-hidden lg:rounded-r-2xl">

                    <!-- Main circular image container -->
                    <div class="absolute inset-4 flex items-center justify-center">
                        <div class="relative">
                            <!-- Dashed circle border -->


                            <!-- Main image circle -->
                            <div
                                class="w-100 h-100 lg:w-100 lg:h-100 rounded-2xl overflow-hidden bg-white p-2 relative z-20">
                                <img src="../assets/images/hero_image.jpg" alt="Pet Grooming"
                                    class="w-full h-full object-cover rounded-2xl">
                            </div>


                        </div>
                    </div>


                    <div class="absolute inset-0 bg-white lg:rounded-r-2xl"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div id="products" class="container mx-auto px-4 pb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Featured Products</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Discover our carefully selected premium products to keep your pets healthy and happy
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <?php
            $featuredProducts = array_slice(getAllProducts(), 0, 4);
            foreach ($featuredProducts as $product):
                ?>
                <div
                    class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                    <div class="relative overflow-hidden">
                        <img src="../assets/uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                            class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110">
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                New
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-2 text-gray-900 flex-grow"><?php echo $product['name']; ?></h3>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="text-xl font-bold text-blue-600">₱<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                            <a href="products.php?action=add&id=<?php echo $product['id']; ?>"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 transform hover:scale-105 text-center block">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <a href="products.php"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold text-lg transition duration-300">
                View All Products
                <svg class="w-5 h-5 ml-2 transition-transform duration-300 transform hover:translate-x-2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Why Choose FurCare Section -->
    <div class="container mx-auto px-4 pb-16">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8 lg:p-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Why Choose FurCare?</h2>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                        We're not just another pet grooming service. We're your pet's new best friend.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🐾</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-white">Experienced Groomers</h3>
                        <p class="text-blue-100 leading-relaxed">
                            Our certified team has years of experience handling pets of all breeds and sizes with
                            expertise and care.
                        </p>
                    </div>

                    <div class="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">💎</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-white">Premium Products</h3>
                        <p class="text-blue-100 leading-relaxed">
                            We use only the highest quality, pet-safe grooming products that are gentle on your pet's
                            skin and coat.
                        </p>
                    </div>

                    <div class="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">❤️</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-white">Loving Care</h3>
                        <p class="text-blue-100 leading-relaxed">
                            Every pet is treated as family. We provide patient, gentle care that makes grooming a
                            positive experience.
                        </p>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-12">
                    <a href="services.php"
                        class="inline-block bg-white text-blue-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg">
                        Book Your Appointment Today
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="container mx-auto px-4 pb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">What Pet Parents Say</h2>
            <p class="text-xl text-gray-600">Don't just take our word for it</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex text-yellow-400 mb-4">
                    <span>★★★★★</span>
                </div>
                <p class="text-gray-600 mb-4 italic">
                    "FurCare transformed my anxious rescue dog into a happy, well-groomed pup. The staff is amazing!"
                </p>
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        S
                    </div>
                    <div>
                        <div class="font-semibold">Sarah M.</div>
                        <div class="text-sm text-gray-500">Dog Parent</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex text-yellow-400 mb-4">
                    <span>★★★★★</span>
                </div>
                <p class="text-gray-600 mb-4 italic">
                    "Professional service and my cat actually enjoys going there now. Highly recommended!"
                </p>
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        M
                    </div>
                    <div>
                        <div class="font-semibold">Mike R.</div>
                        <div class="text-sm text-gray-500">Cat Parent</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex text-yellow-400 mb-4">
                    <span>★★★★★</span>
                </div>
                <p class="text-gray-600 mb-4 italic">
                    "The best grooming service in town. My pets always come home looking and feeling great!"
                </p>
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        L
                    </div>
                    <div>
                        <div class="font-semibold">Lisa T.</div>
                        <div class="text-sm text-gray-500">Multi-Pet Parent</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>