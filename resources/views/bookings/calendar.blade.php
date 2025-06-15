<x-admin-layout title="Booking Calendar" subtitle="View bookings in calendar format">
    <div class="max-w-7xl mx-auto">
        <!-- Calendar Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Booking Calendar</h2>
                    <p class="text-gray-600 mt-1">View and manage bookings in calendar format</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('bookings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>
                        List View
                    </a>
                    
                    @can('bookings.create')
                        <a href="{{ route('bookings.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            New Booking
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Calendar Legend -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Status Legend</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Pending</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Confirmed</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Active</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Returned</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Cancelled</span>
                </div>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div id="calendar" class="p-6"></div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="booking-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modal-content" class="space-y-4">
                    <!-- Content will be loaded here -->
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                        Close
                    </button>
                    <a id="view-booking-btn" href="#" 
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <style>
        .fc-event {
            border: none !important;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .fc-event:hover {
            opacity: 0.8;
        }
        
        .fc-daygrid-event {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        let calendar;
        
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: function(info, successCallback, failureCallback) {
                    fetch(`{{ route('bookings.calendar') }}?ajax=1&start=${info.startStr}&end=${info.endStr}`)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
                },
                eventClick: function(info) {
                    showBookingModal(info.event);
                },
                eventDidMount: function(info) {
                    // Add tooltips
                    info.el.title = `${info.event.title}\nStatus: ${info.event.extendedProps.status}\nTotal: $${info.event.extendedProps.total}`;
                },
                dateClick: function(info) {
                    // Redirect to create booking with pre-selected date
                    window.location.href = `{{ route('bookings.create') }}?start_date=${info.dateStr}`;
                }
            });
            
            calendar.render();
        });
        
        function showBookingModal(event) {
            const modal = document.getElementById('booking-modal');
            const title = document.getElementById('modal-title');
            const content = document.getElementById('modal-content');
            const viewBtn = document.getElementById('view-booking-btn');
            
            title.textContent = event.title;
            viewBtn.href = `/bookings/${event.id}`;
            
            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Customer</p>
                        <p class="font-medium">${event.extendedProps.customer}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${getStatusClasses(event.extendedProps.status)}">
                            ${event.extendedProps.status.charAt(0).toUpperCase() + event.extendedProps.status.slice(1)}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="font-medium">$${event.extendedProps.total}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Equipment Count</p>
                        <p class="font-medium">${event.extendedProps.equipment_count} items</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Duration</p>
                    <p class="font-medium">${event.startStr} to ${event.endStr}</p>
                </div>
            `;
            
            modal.classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('booking-modal').classList.add('hidden');
        }
        
        function getStatusClasses(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-blue-100 text-blue-800',
                'active': 'bg-green-100 text-green-800',
                'returned': 'bg-gray-100 text-gray-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            
            return classes[status] || 'bg-gray-100 text-gray-800';
        }
        
        // Close modal when clicking outside
        document.getElementById('booking-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
    @endpush
</x-admin-layout>
