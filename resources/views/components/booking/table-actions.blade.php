@props(['booking'])

<div class="flex items-center space-x-2">
    <!-- View Button -->
    <a href="{{ route('bookings.show', $booking) }}" 
       class="text-indigo-600 hover:text-indigo-900 p-2 hover:bg-indigo-100 rounded-full transition-colors duration-200"
       title="View Details">
        <i class="fas fa-eye text-sm"></i>
    </a>

    <!-- Edit Button -->
    @can('bookings.edit')
        <a href="{{ route('bookings.edit', $booking) }}" 
           class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-100 rounded-full transition-colors duration-200"
           title="Edit">
            <i class="fas fa-edit text-sm"></i>
        </a>
    @endcan

    <!-- Invoice Button -->
    <a href="{{ route('bookings.invoice', $booking) }}" 
       class="text-green-600 hover:text-green-900 p-2 hover:bg-green-100 rounded-full transition-colors duration-200"
       title="Invoice">
        <i class="fas fa-file-invoice text-sm"></i>
    </a>

    <!-- Status-based Quick Actions -->
    @if($booking->status == 'pending')
        <button onclick="quickConfirm({{ $booking->id }})"
                class="text-emerald-600 hover:text-emerald-900 p-2 hover:bg-emerald-100 rounded-full transition-colors duration-200"
                title="Quick Confirm">
            <i class="fas fa-check text-sm"></i>
        </button>
        <button onclick="quickCancel({{ $booking->id }})"
                class="text-red-600 hover:text-red-900 p-2 hover:bg-red-100 rounded-full transition-colors duration-200"
                title="Cancel">
            <i class="fas fa-times text-sm"></i>
        </button>
    @elseif($booking->status == 'confirmed')
        <button onclick="quickActivate({{ $booking->id }})"
                class="text-purple-600 hover:text-purple-900 p-2 hover:bg-purple-100 rounded-full transition-colors duration-200"
                title="Activate (Equipment Picked Up)">
            <i class="fas fa-play text-sm"></i>
        </button>
    @elseif($booking->status == 'active')
        <a href="{{ route('bookings.show', $booking) }}#return-section"
           class="text-orange-600 hover:text-orange-900 p-2 hover:bg-orange-100 rounded-full transition-colors duration-200"
           title="Process Return">
            <i class="fas fa-undo text-sm"></i>
        </a>
    @endif

    <!-- Duplicate Button -->
    <button onclick="duplicateBooking({{ $booking->id }})"
            class="text-gray-600 hover:text-gray-900 p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
            title="Duplicate Booking">
        <i class="fas fa-copy text-sm"></i>
    </button>

    <!-- Dropdown Menu for More Actions -->
    <div class="relative inline-block text-left">
        <button type="button" 
                onclick="toggleDropdown({{ $booking->id }})"
                class="text-gray-600 hover:text-gray-900 p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
                title="More Actions">
            <i class="fas fa-ellipsis-v text-sm"></i>
        </button>
        
        <div id="dropdown-{{ $booking->id }}" 
             class="hidden absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                @can('bookings.delete')
                    <button onclick="confirmDelete({{ $booking->id }})"
                            class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-900">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Booking
                    </button>
                @endcan
                
                <a href="{{ route('bookings.show', $booking) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-print mr-2"></i>
                    Print Details
                </a>
                
                <button onclick="exportBooking({{ $booking->id }})"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-download mr-2"></i>
                    Export as PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown(bookingId) {
    const dropdown = document.getElementById(`dropdown-${bookingId}`);
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== `dropdown-${bookingId}`) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            d.classList.add('hidden');
        });
    }
});

function confirmDelete(bookingId) {
    if (confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/bookings/${bookingId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function duplicateBooking(bookingId) {
    if (confirm('Create a duplicate of this booking?')) {
        fetch(`/bookings/${bookingId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/bookings/${data.booking_id}/edit`;
            } else {
                alert('Error duplicating booking: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while duplicating the booking.');
        });
    }
}

function exportBooking(bookingId) {
    window.open(`/bookings/${bookingId}/invoice?format=pdf`, '_blank');
}
</script>
