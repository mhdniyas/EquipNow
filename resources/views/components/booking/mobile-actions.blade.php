@props(['booking'])

<div class="flex items-center justify-between">
    <!-- Primary Actions -->
    <div class="flex space-x-2">
        <a href="{{ route('bookings.show', $booking) }}" 
           class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-200 transition-colors duration-200">
            <i class="fas fa-eye mr-1"></i>
            View
        </a>
        
        @can('bookings.edit')
            <a href="{{ route('bookings.edit', $booking) }}" 
               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors duration-200">
                <i class="fas fa-edit mr-1"></i>
                Edit
            </a>
        @endcan
    </div>
    
    <!-- Status-based Quick Actions -->
    <div class="flex space-x-2">
        @if($booking->status == 'pending')
            <button onclick="quickConfirm({{ $booking->id }})"
                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-check mr-1"></i>
                Confirm
            </button>
        @elseif($booking->status == 'confirmed')
            <button onclick="quickActivate({{ $booking->id }})"
                    class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                <i class="fas fa-play mr-1"></i>
                Activate
            </button>
        @elseif($booking->status == 'active')
            <a href="{{ route('bookings.show', $booking) }}#return-section"
               class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                <i class="fas fa-undo mr-1"></i>
                Return
            </a>
        @endif
        
        <!-- More Actions Button -->
        <button type="button" 
                onclick="toggleMobileDropdown({{ $booking->id }})"
                class="inline-flex items-center px-2 py-1.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </div>
</div>

<!-- Mobile Dropdown Menu -->
<div id="mobile-dropdown-{{ $booking->id }}" 
     class="hidden mt-3 pt-3 border-t border-gray-100">
    <div class="grid grid-cols-2 gap-2">
        <a href="{{ route('bookings.invoice', $booking) }}" 
           class="inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors duration-200">
            <i class="fas fa-file-invoice mr-1"></i>
            Invoice
        </a>
        
        <button onclick="duplicateBooking({{ $booking->id }})"
                class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-copy mr-1"></i>
            Duplicate
        </button>
        
        @if($booking->status == 'pending')
            <button onclick="quickCancel({{ $booking->id }})"
                    class="inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors duration-200">
                <i class="fas fa-times mr-1"></i>
                Cancel
            </button>
        @endif
        
        @can('bookings.delete')
            <button onclick="confirmDelete({{ $booking->id }})"
                    class="inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors duration-200">
                <i class="fas fa-trash mr-1"></i>
                Delete
            </button>
        @endcan
    </div>
</div>

<script>
function toggleMobileDropdown(bookingId) {
    const dropdown = document.getElementById(`mobile-dropdown-${bookingId}`);
    
    // Close all other mobile dropdowns
    document.querySelectorAll('[id^="mobile-dropdown-"]').forEach(d => {
        if (d.id !== `mobile-dropdown-${bookingId}`) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}
</script>
