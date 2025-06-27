document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: {
                url: '../../php/admin/get-calendar-events.php',
                method: 'GET',
                failure: function() {
                    alert('Failed to load calendar events');
                }
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            },
            eventClick: function(info) {
                const event = info.event;
                const modal = document.createElement('div');
                
                modal.innerHTML = `
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-xl font-bold mb-4">Appointment Details</h3>
                            <div class="space-y-2">
                                <p><strong>Customer:</strong> ${event.extendedProps.customer}</p>
                                <p><strong>Service:</strong> ${event.title}</p>
                                <p><strong>Pet:</strong> ${event.extendedProps.pet_name} (${event.extendedProps.pet_type})</p>
                                <p><strong>Date:</strong> ${event.start.toLocaleDateString()}</p>
                                <p><strong>Time:</strong> ${event.extendedProps.time}</p>
                                <p><strong>Status:</strong> <span class="px-2 py-1 rounded ${getStatusClass(event.extendedProps.status)}">${event.extendedProps.status}</span></p>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                modal.querySelector('button').addEventListener('click', function() {
                    document.body.removeChild(modal);
                });
            }
        });
        
        calendar.render();
    }
});

function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'confirmed':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'completed':
            return 'bg-blue-100 text-blue-800';
        default:
            return 'bg-red-100 text-red-800';
    }
}