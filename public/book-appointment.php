<?php require_once '../includes/config.php'; ?>
<?php requireAuth(); ?>

<?php
if (!isset($_GET['service_id']) || !$service = getServiceById($_GET['service_id'])) {
    header('Location: services.php');
    exit();
}

//fetch nato diri ang services para makuha nato ang sizes mabutang sa from inig mag book og appointment ang user.
$allServices = getAllServices();
$availableSizes = [];

if ($allServices) {
    foreach ($allServices as $s) {
        if (!empty($s['size']) && !in_array($s['size'], $availableSizes)) {
            $availableSizes[] = $s['size'];
        }
    }
}

?>

<?php require_once '../includes/header.php'; ?>

<!-- Header Section -->
<div style="background: linear-gradient(135deg, #12B3EB 0%, #5460F9 100%);" class="py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-6xl py-4 mx-auto">
            <h1 class="text-5xl font-bold text-white mb-4">Book Your Appointment</h1>
            <p class="text-xl text-white" style="opacity: 0.9;">Schedule your pet's care with our professional team</p>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Service Information Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-slate-200 sticky top-8">
                        <div class="text-center mb-6">
                            <div
                                class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800 mb-2">Service Details</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-600 font-medium">Service</span>
                                <span class="text-slate-800 font-semibold"><?php echo $service['name']; ?></span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <span class="text-slate-600 font-medium">Size</span>
                                <span class="text-green-600 font-bold text-lg"><?php echo $service['size']; ?></span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <span class="text-slate-600 font-medium">Price</span>
                                <span
                                    class="text-green-600 font-bold text-lg">₱<?php echo number_format($service['price'], 2); ?></span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <span class="text-slate-600 font-medium">Duration</span>
                                <span class="text-blue-600 font-semibold"><?php echo $service['duration']; ?> min</span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-amber-500 text-sm font-medium">Important</p>
                                    <p class="text-amber-500 text-sm">Please arrive 10 minutes early for your
                                        appointment.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                        <!-- Form Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                            <h3 class="text-xl font-bold text-white">Appointment Information</h3>
                            <p class="text-blue-100 mt-1">Please fill in all required fields</p>
                        </div>

                        <!-- Error Message -->
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div
                                class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <?php echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form Content -->
                        <div class="p-8">
                            <form action="../php/appointments/book-appointment.php" method="POST" class="space-y-6">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">



                                <!-- Pet Information Section -->
                                <div class="space-y-6">
                                    <div class="border-b border-slate-200 pb-4">
                                        <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                            Pet Information
                                        </h4>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="pet_name" class="block text-slate-700 font-semibold text-sm">
                                            Pets Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="pet_name" name="pet_name" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white">
                                    </div>

                                    <div class="space-y-2">
                                        <label for="pet_type" class="block text-slate-700 font-semibold text-sm">
                                            Pet Type <span class="text-red-500">*</span>
                                        </label>
                                        <select id="pet_type" name="pet_type" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white">
                                            <option value="">Select Pet Type</option>
                                            <option value="Dog">🐕 Dog</option>
                                            <option value="Cat">🐱 Cat</option>
                                            <option value="Rabbit">🐰 Rabbit</option>
                                            <option value="Other">🐾 Other</option>
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="pet_size" class="block text-slate-700 font-semibold text-sm">
                                            Pet Size <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="pet_size_display"
                                            value="<?php echo htmlspecialchars($service['size']); ?>" readonly
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                        <input type="hidden" name="pet_size"
                                            value="<?php echo htmlspecialchars($service['size']); ?>">
                                    </div>
                                </div>
                                <!-- Personal Information Section -->
                                <div class="space-y-6">
                                    <div class="border-b border-slate-200 pb-4">
                                        <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            Personal Information
                                        </h4>
                                    </div>

                                    <div class="grid  gap-6">

                                        <div class="space-y-2">
                                            <label for="contact_number"
                                                class="block text-slate-700 font-semibold text-sm">
                                                Contact Number <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" id="contact_number" name="contact_number" required
                                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white"
                                                placeholder="09123456789">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="email" class="block text-slate-700 font-semibold text-sm">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white"
                                            value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
                                    </div>
                                </div>

                                <!-- Appointment Scheduling Section -->
                                <div class="space-y-6">
                                    <div class="border-b border-slate-200 pb-4">
                                        <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Schedule Details
                                        </h4>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="appointment_date"
                                                class="block text-slate-700 font-semibold text-sm">
                                                Preferred Date <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" id="appointment_date" name="appointment_date" required
                                                min="<?php echo date('Y-m-d'); ?>"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white">
                                            <!-- Add this right below your date input -->
                                            <div id="dateClosedWarning"
                                                class="hidden mt-2 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-start">
                                                <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="font-medium">We're closed on this date</p>
                                                    <p class="text-sm">Please choose another available date for your
                                                        appointment.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="appointment_time"
                                                class="block text-slate-700 font-semibold text-sm">
                                                Preferred Time <span class="text-red-500">*</span>
                                            </label>
                                            <select id="appointment_time" name="appointment_time" required
                                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white">
                                                <option value="">Select Time</option>
                                                <!-- Time slots will be populated by JavaScript -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information Section -->
                                <div class="space-y-6">
                                    <div class="border-b border-slate-200 pb-4">
                                        <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Additional Information
                                        </h4>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="notes" class="block text-slate-700 font-semibold text-sm">
                                            Special Instructions <span class="text-slate-500">(Optional)</span>
                                        </label>
                                        <textarea id="notes" name="notes" rows="4"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white resize-none"
                                            placeholder="Any special instructions or notes about your pet..."></textarea>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-6">
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center">

                                        Confirm Booking
                                    </button>
                                    <a href="services.php"
                                        class="w-full mt-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center">
                                        Cancel
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('appointment_date').addEventListener('change', function () {
        const date = this.value;
        if (!date) return;

        fetch(`../php/appointments/get-time-slots.php?date=${date}&service_id=<?php echo $service['id']; ?>`)
            .then(response => response.json())
            .then(data => {
                const timeSelect = document.getElementById('appointment_time');
                timeSelect.innerHTML = '<option value="">Select Time</option>';

                if (data.length > 0) {
                    data.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = formatTime(time);
                        timeSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No available time slots';
                    option.disabled = true;
                    timeSelect.appendChild(option);
                }
            });
    });

    function formatTime(timeStr) {
        const [hours, minutes] = timeStr.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Fetch closed dates when page loads
        fetch('../php/appointments/check-closed-dates.php')
            .then(response => response.json())
            .then(closedDates => {
                const dateInput = document.getElementById('appointment_date');

                // Set min date to today
                dateInput.min = new Date().toISOString().split('T')[0];

                // Add event listener to prevent selecting closed dates
                dateInput.addEventListener('input', function () {
                    const selectedDate = this.value;
                    const warningDiv = document.getElementById('dateClosedWarning');

                    if (closedDates.includes(selectedDate)) {
                        // Reset the date and show warning
                        this.value = '';
                        warningDiv.classList.remove('hidden');
                        document.getElementById('appointment_time').innerHTML = '<option value="">Select Time</option>';
                        document.querySelector('button[type="submit"]').disabled = true;
                    } else {
                        warningDiv.classList.add('hidden');
                        document.querySelector('button[type="submit"]').disabled = false;
                    }
                });

                // For browsers that support the 'disabledDates' property
                if ('disabledDates' in dateInput) {
                    dateInput.disabledDates = closedDates;
                }
            })
            .catch(error => {
                console.error('Error loading closed dates:', error);
            });
    });
</script>

<?php require_once '../includes/footer.php'; ?>