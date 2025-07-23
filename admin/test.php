<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
  <div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="mb-10">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold text-slate-800 mb-3">Appointment Calendar</h1>
          <p class="text-slate-600 text-lg">Manage and view all your appointments in one place</p>
        </div>
        <div>
          <a href="appointments.php"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Appointments
          </a>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
      <!-- Calendar Section -->
      <div class="xl:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
            <h2 class="text-2xl font-bold text-white flex items-center">
              <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              Calendar View
            </h2>
            <p class="text-blue-100 mt-1">Click on any date to view appointments</p>
          </div>
          <div class="p-8">
            <div id="calendar" class="w-full"></div>
          </div>
        </div>
      </div>
      <!-- Day Appointments Section -->
      <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 sticky top-8">
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6 rounded-t-2xl">
            <h2 class="text-xl font-bold text-white flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Daily Schedule
            </h2>
          </div>
          <div class="p-6">
            <div class="mb-4">
              <h3 class="text-lg font-semibold text-slate-800" id="day-header">
                Select a date to view appointments
              </h3>
            </div>
            <div id="day-appointments" class="space-y-4">
              <div class="flex flex-col items-center justify-center py-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-slate-500 font-medium">No date selected</p>
                <p class="text-slate-400 text-sm mt-1">Click on a calendar date to view appointments</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Closure Date Modal -->
<div id="addClosureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
  <div class="bg-white rounded-2xl shadow-2xl p-0 max-w-md w-full">
    <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-8 py-6 rounded-t-2xl">
      <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-white">Add Store Closure Date</h3>
        <button onclick="document.getElementById('addClosureModal').classList.add('hidden')"
          class="text-slate-300 hover:text-white transition-colors duration-200 p-1">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
    <div class="p-8">
      <form id="closureForm">
        <div class="mb-6">
          <label for="closureDate" class="block text-sm font-medium text-slate-700 mb-2">Date</label>
          <input type="date" id="closureDate" name="closureDate"
            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-6">
          <label for="closureReason" class="block text-sm font-medium text-slate-700 mb-2">Reason (Optional)</label>
          <textarea id="closureReason" name="closureReason" rows="3"
            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        <div class="flex justify-end space-x-3">
          <button type="button" onclick="document.getElementById('addClosureModal').classList.add('hidden')"
            class="px-4 py-2.5 bg-slate-200 text-slate-800 font-semibold rounded-lg hover:bg-slate-300 transition-colors duration-200">
            Cancel
          </button>
          <button type="submit"
            class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-sm hover:shadow-md">
            Add Closure Date
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/calendar.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>