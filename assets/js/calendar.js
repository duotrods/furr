 document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const dayAppointmentsEl = document.getElementById('day-appointments');
    const dayHeaderEl = document.getElementById('day-header');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth addClosureButton'
      },
      customButtons: {
        addClosureButton: {
          text: 'Add Closure',
          click: function () {
            // Get the current date from the calendar
            const currentDate = calendar.view.currentStart;
            const formattedDate = currentDate.toISOString().split('T')[0];

            // Set the date in the modal
            document.getElementById('closureDate').value = formattedDate;

            // Show the modal
            document.getElementById('addClosureModal').classList.remove('hidden');
          }
        }
      },
      events: '../php/admin/calendar/get-calendar-event.php',
      dateClick: function (info) {
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
                        <svg class="w-5 h-5 text-black-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        ${formattedDate}
                    </div>
                `;

        // Fetch appointments for selected date
        fetchDayAppointments(selectedDate);
      },
      eventClick: function (info) {
        const event = info.event;

        // Don't show modal for closure events
        if (event.extendedProps.type === 'closure') {
          return;
        }

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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
          return 'bg-yellow-100 text-yellow-800';
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

    // Handle closure form submission
    document.getElementById('closureForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = {
        date: document.getElementById('closureDate').value,
        reason: document.getElementById('closureReason').value
      };

      axios.post('../php/admin/calendar/add-store-closure.php', formData)
        .then(response => {
          // Refresh calendar to show the closure
          calendar.refetchEvents();

          // Show success message
          alert('Store closure date added successfully');

          // Hide modal
          document.getElementById('addClosureModal').classList.add('hidden');
        })
        .catch(error => {
          console.error('Error adding store closure:', error);
          alert('Error adding store closure. Please try again.');
        });
    });

    // Add right-click context menu for closures
    calendarEl.addEventListener('contextmenu', function (evt) {
      evt.preventDefault();

      // Get the clicked date
      const dateStr = evt.target.closest('.fc-daygrid-day')?.dataset.date;
      if (!dateStr) return;

      // Check if this date is a closure
      axios.get(`../php/admin/calendar/check-closure.php?date=${dateStr}`)
        .then(response => {
          if (response.data.isClosure) {
            showClosureContextMenu(evt, dateStr, response.data.closureId);
          }
        });
    });

    function showClosureContextMenu(evt, dateStr, closureId) {
      // Create context menu
      const menu = document.createElement('div');
      menu.className = 'absolute bg-white shadow-xl rounded-lg py-1 z-50 w-48';
      menu.style.left = `${evt.pageX}px`;
      menu.style.top = `${evt.pageY}px`;

      menu.innerHTML = `
                <div class="px-4 py-2 text-sm text-slate-700 border-b border-slate-100">Store Closure</div>
                <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center"
                        onclick="removeClosure(${closureId}, this.closest('div'))">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Remove Closure
                </button>
            `;

      document.body.appendChild(menu);

      // Close menu when clicking elsewhere
      const closeMenu = function () {
        document.body.removeChild(menu);
        document.removeEventListener('click', closeMenu);
      };

      document.addEventListener('click', closeMenu);
    }

    window.removeClosure = function (closureId, menuElement) {
      if (confirm('Are you sure you want to remove this store closure?')) {
        axios.delete(`..php/admin/calendar/remove-closure.php?id=${closureId}`)
          .then(response => {
            calendar.refetchEvents();
            if (menuElement) menuElement.remove();
          })
          .catch(error => {
            console.error('Error removing closure:', error);
            alert('Error removing closure. Please try again.');
          });
      }
    }
  });