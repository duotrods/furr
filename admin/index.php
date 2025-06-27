<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php requireAdmin(); ?>

<?php 
$appointmentCounts = getAppointmentCounts();
$recentAppointments = getAllAppointments('confirmed');
?>



<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Welcome back! Here's what's happening today.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900"><?php echo date('l, F j, Y'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo date('g:i A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['today']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-green-600 font-medium">Today</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">This Week</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['week']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 font-medium">7 days</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">This Month</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['month']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 font-medium">30 days</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['pending'] ?? 0; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-amber-600 font-medium">Awaiting approval</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 xl:grid-cols-4 mt-16 gap-8">
            <!-- Calendar Section -->
            <div class="xl:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
    /* Custom FullCalendar styling */
    .fc {
        font-family: inherit;
    }

    .fc-header-toolbar {
        margin-bottom: 1.5rem !important;
        padding: 0 !important;
    }

    .fc-toolbar-chunk {
        display: flex;
        align-items: center;
    }

    .fc-button {
        background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
        border: none !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        margin: 0 0.25rem !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2) !important;
        transition: all 0.2s ease !important;
    }

    .fc-button:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3) !important;
    }

    .fc-button:disabled {
        background: #e2e8f0 !important;
        color: #94a3b8 !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .fc-toolbar-title {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin: 0 1rem !important;
    }

    .fc-daygrid-day {
        transition: background-color 0.2s ease !important;
    }

    .fc-daygrid-day:hover {
        background-color: #f8fafc !important;
    }

    .fc-daygrid-day-number {
        color: #475569 !important;
        font-weight: 600 !important;
        padding: 0.5rem !important;
    }

    .fc-day-today {
        background-color: #eff6ff !important;
        border: 2px solid #3b82f6 !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #1d4ed8 !important;
        font-weight: 700 !important;
    }

    .fc-event {
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        margin: 0.125rem 0 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }

    .fc-event:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    }

    .fc-daygrid-block-event .fc-event-title {
        font-weight: 600 !important;
    }

    /* Status-based event colors */
    .fc-event[data-status="confirmed"] {
        background-color: #10b981 !important;
        color: white !important;
    }

    .fc-event[data-status="pending"] {
        background-color: #f59e0b !important;
        color: white !important;
    }

    .fc-event[data-status="completed"] {
        background-color: #3b82f6 !important;
        color: white !important;
    }

    .fc-event[data-status="cancelled"] {
        background-color: #ef4444 !important;
        color: white !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const dayAppointmentsEl = document.getElementById('day-appointments');
        const dayHeaderEl = document.getElementById('day-header');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth' // Removed week/day views
            },
            events: '../php/admin/get-calendar-events.php',
            dateClick: function(info) {
                // Format date as YYYY-MM-DD
                const selectedDate = info.dateStr;
                const formattedDate = new Date(selectedDate).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Update header
                dayHeaderEl.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    ${formattedDate}
                </div>
            `;

                // Fetch appointments for selected date
                fetchDayAppointments(selectedDate);
            },
            eventClick: function(info) {
                const event = info.event;
                const modalContent = `
                <div class="space-y-4">
                    <div class="text-center pb-4 border-b border-slate-200">
                        <h3 class="font-bold text-2xl text-slate-800 mb-2">${event.title}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusClasses(event.extendedProps.status)}">
                            ${event.extendedProps.status.charAt(0).toUpperCase() + event.extendedProps.status.slice(1)}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                            <svg class="w-5 h-5 text-slate-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-slate-800">Customer</p>
                                <p class="text-slate-600">${event.extendedProps.customer}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                            <svg class="w-5 h-5 text-slate-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-slate-800">Pet</p>
                                <p class="text-slate-600">${event.extendedProps.pet_name} (${event.extendedProps.pet_type})</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                            <svg class="w-5 h-5 text-slate-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-slate-800">Time</p>
                                <p class="text-slate-600">${event.extendedProps.time}</p>
                            </div>
                        </div>
                        
                        ${event.extendedProps.notes ? `
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-slate-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold text-slate-800">Notes</p>
                                    <p class="text-slate-600">${event.extendedProps.notes}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="pt-4 mt-6 border-t border-slate-200">
                        <a href="view-appointment.php?id=${event.extendedProps.id}" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Full Details
                        </a>
                    </div>
                </div>
            `;

                // Create modal
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm';
                modal.innerHTML = `
                <div class="bg-white rounded-2xl shadow-2xl p-0 max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-8 py-6 rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Appointment Details
                            </h3>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="text-slate-300 hover:text-white transition-colors duration-200 p-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-8">
                        ${modalContent}
                    </div>
                </div>
            `;

                document.body.appendChild(modal);
            }
        });

        calendar.render();

        // Function to get status classes
        function getStatusClasses(status) {
            switch (status) {
                case 'confirmed':
                    return 'bg-green-100 text-green-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';d
                case 'completed':
                    return 'bg-blue-100 text-blue-800';
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // Function to fetch appointments for a specific day
        function fetchDayAppointments(date) {
            // Show loading state
            dayAppointmentsEl.innerHTML = `
            <div class="flex flex-col items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600 mb-4"></div>
                <p class="text-slate-500">Loading appointments...</p>
            </div>
        `;

            axios.get(`../admin/get-day-appointments.php?date=${date}`)
                .then(response => {
                    if (response.data.length > 0) {
                        let html = '';
                        response.data.forEach(appointment => {
                            const statusClass = appointment.status === 'confirmed' ? 'bg-green-100 text-green-800 border-green-200' :
                                appointment.status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' :
                                appointment.status === 'completed' ? 'bg-blue-100 text-blue-800 border-blue-200' :
                                'bg-red-100 text-red-800 border-red-200';

                            const statusIcon = appointment.status === 'confirmed' ?
                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
                                appointment.status === 'pending' ?
                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                                appointment.status === 'completed' ?
                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

                            html += `
                            <div class="bg-white border border-slate-200 rounded-xl p-4 hover:shadow-md transition-all duration-200 hover:border-slate-300">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-slate-800 text-lg mb-1">${appointment.service_name}</h3>
                                        <div class="space-y-1">
                                            <div class="flex items-center text-sm text-slate-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                ${appointment.first_name} ${appointment.last_name}
                                            </div>
                                            <div class="flex items-center text-sm text-slate-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                ${appointment.pet_name} (${appointment.pet_type})
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full border ${statusClass}">
                                        ${statusIcon}
                                        <span class="ml-1">${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}</span>
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                                    <div class="flex items-center text-sm text-slate-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${appointment.appointment_time}
                                    </div>
                                    <a href="view-appointment.php?id=${appointment.id}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                </div>
                            </div>
                        `;
                        });
                        dayAppointmentsEl.innerHTML = html;
                    } else {
                        dayAppointmentsEl.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-slate-600 font-semibold text-lg mb-2">No appointments scheduled</p>
                            <p class="text-slate-500 text-sm">This day is currently free</p>
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching day appointments:', error);
                    dayAppointmentsEl.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg class="w-16 h-16 text-red-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-red-600 font-semibold">Error loading appointments</p>
                        <p class="text-red-500 text-sm mt-1">Please try again later</p>
                    </div>
                `;
                });
        }
    });
</script>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>