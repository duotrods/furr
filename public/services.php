<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>
<!-- Hero Section -->
<div style="background: linear-gradient(135deg, #12B3EB 0%, #5460F9 100%);" class="py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-6xl py-4 mx-auto">
            <h1 class="text-5xl font-bold text-white mb-4">FurCare Premium Pet Grooming Services</h1>
            <p class="text-xl text-white" style="opacity: 0.9;">Professional care for your beloved companions with love,
                expertise, and attention to detail</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- Services Grid -->
    <div id="services" class="mb-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Services</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (getAllServices() as $service): ?>
                <div style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                    onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 25px 50px -12px rgba(0, 0, 0, 0.25)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''"
                    class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="relative">
                        <div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);" class="p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($service['name']); ?>
                                    </h3>
                                    <p class="text-sm font-bold mb-2"><?php echo htmlspecialchars($service['size']); ?>
                                    </p>
                                    <div style="background: linear-gradient(45deg, #10b981, #059669); "
                                        class="inline-block px-3 py-1 rounded-full text-sm font-semibold">
                                        ₱<?php echo number_format($service['price'], 2); ?>
                                    </div>
                                </div>
                                <i class="fas fa-cut text-3xl" style="opacity: 0.8;"></i>
                            </div>
                        </div>
                        <!-- <div class="absolute -bottom-4 right-4">
                            <div class="w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center">
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                        </div> -->
                    </div>

                    <div class="p-6">
                        <p class="text-gray-600 mb-4" style="line-height: 1.625;">
                            <?php echo htmlspecialchars($service['description']); ?>
                        </p>

                        <div class="flex items-center text-gray-500 mb-6">
                            <i class="far fa-clock mr-2 text-blue-500"></i>
                            <span class="text-sm font-medium">Duration: <?php echo $service['duration']; ?> minutes</span>
                        </div>

                        <?php if (isLoggedIn()): ?>
                            <a href="book-appointment.php?service_id=<?php echo $service['id']; ?>"
                                style="background: linear-gradient(45deg, #3b82f6, #1d4ed8); transition: all 0.3s ease;"
                                onmouseover="this.style.background='linear-gradient(45deg, #1d4ed8, #1e40af)'; this.style.transform='scale(1.02)'"
                                onmouseout="this.style.background='linear-gradient(45deg, #3b82f6, #1d4ed8)'; this.style.transform='scale(1)'"
                                class="block w-full text-white text-center font-bold py-3 px-6 rounded-xl shadow-lg">
                                <i class="fas fa-calendar-check mr-2"></i>
                                Book Now
                            </a>
                        <?php else: ?>
                            <a href="login.php" style="transition: all 0.3s ease;"
                                onmouseover="this.style.backgroundColor='#374151'"
                                onmouseout="this.style.backgroundColor='#4b5563'"
                                class="block w-full bg-gray-600 text-white text-center font-bold py-3 px-6 rounded-xl shadow-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login to Book
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Service Guidelines -->
    <div style="background: linear-gradient(to right, #eff6ff, #e0e7ff);"
        class="rounded-3xl p-8 mb-12 border border-blue-100">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-info-circle text-white text-xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Service Guidelines</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-1"
                        style="flex-shrink: 0;">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <p class="text-gray-700" style="line-height: 1.625;">Please arrive 10 minutes before your scheduled
                        appointment.</p>
                </div>

                <div class="flex items-start">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-1"
                        style="flex-shrink: 0;">
                        <i class="fas fa-file-medical text-white text-sm"></i>
                    </div>
                    <p class="text-gray-700" style="line-height: 1.625;">Bring your pet's vaccination records for
                        first-time customers.</p>
                </div>

                <div class="flex items-start">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3 mt-1"
                        style="flex-shrink: 0;">
                        <i class="fas fa-calendar-times text-white text-sm"></i>
                    </div>
                    <p class="text-gray-700" style="line-height: 1.625;">Please notify us at least 24 hours in advance
                        for cancellations or rescheduling.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3 mt-1"
                        style="flex-shrink: 0;">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <p class="text-gray-700" style="line-height: 1.625;">Aggressive pets may require special handling or
                        may be refused service.</p>
                </div>

                <div class="flex items-start">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-1"
                        style="flex-shrink: 0;">
                        <i class="fas fa-heartbeat text-white text-sm"></i>
                    </div>
                    <p class="text-gray-700" style="line-height: 1.625;">We reserve the right to refuse service to any
                        pet showing signs of illness.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
        <div class="text-center mb-12">
            <div style="background: linear-gradient(to right, #8b5cf6, #ec4899);"
                class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4">
                <i class="fas fa-question-circle text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
            <div style="background: linear-gradient(to right, #8b5cf6, #ec4899);" class="w-24 h-1 mx-auto rounded-full">
            </div>
        </div>

        <div class="space-y-6 max-w-4xl mx-auto">
            <div style="transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='#f8fafc'; this.style.transform='translateX(4px)'"
                onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateX(0)'"
                class="p-6 rounded-2xl border border-gray-100">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4"
                        style="flex-shrink: 0;">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">How often should I groom my pet?</h3>
                        <p class="text-gray-600" style="line-height: 1.625;">It depends on the breed and coat type.
                            Generally, every 4-6 weeks is recommended for most pets to maintain optimal health and
                            appearance.</p>
                    </div>
                </div>
            </div>

            <div style="transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='#f8fafc'; this.style.transform='translateX(4px)'"
                onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateX(0)'"
                class="p-6 rounded-2xl border border-gray-100">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4"
                        style="flex-shrink: 0;">
                        <i class="fas fa-cat text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Do you groom cats?</h3>
                        <p class="text-gray-600" style="line-height: 1.625;">Yes, we groom cats with special handling
                            procedures and techniques designed to ensure their comfort and safety throughout the
                            process.</p>
                    </div>
                </div>
            </div>

            <div style="transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='#f8fafc'; this.style.transform='translateX(4px)'"
                onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateX(0)'"
                class="p-6 rounded-2xl border border-gray-100">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4"
                        style="flex-shrink: 0;">
                        <i class="fas fa-heart text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">What if my pet has special needs?</h3>
                        <p class="text-gray-600" style="line-height: 1.625;">Please inform us of any special needs,
                            medical conditions, or behavioral concerns when booking your appointment so we can provide
                            the best care possible.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div style="background: linear-gradient(to right, #2563eb, #7c3aed);"
        class="text-center mt-16 rounded-3xl p-12 text-white">
        <i class="fas fa-phone-alt text-4xl mb-4" style="opacity: 0.9;"></i>
        <h2 class="text-3xl font-bold mb-4">Ready to Pamper Your Pet?</h2>
        <p class="text-xl mb-8" style="opacity: 0.9;">Contact us today to schedule your pet's grooming appointment</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#services" style="transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f9fafb'"
                onmouseout="this.style.backgroundColor='white'"
                class="inline-block bg-white text-blue-600 font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl">
                <i class="fas fa-calendar-plus mr-2"></i>
                Book Appointment
            </a>
            <a href="tel:+639700249877" style="transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='white'; this.style.color='#2563eb'"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'"
                class="inline-block border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-blue-600">
                <i class="fas fa-phone mr-2"></i>
                Call Us Now
            </a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>