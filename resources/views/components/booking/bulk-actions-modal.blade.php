<!-- Bulk Actions Modal -->
<div id="bulk-actions-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md bg-white rounded-lg shadow-lg">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Bulk Actions</h3>
                <button type="button" 
                        onclick="hideBulkActionsModal()"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Select an action to perform on the selected bookings:
                </p>
                <div id="selected-count" class="text-sm font-medium text-indigo-600 mt-1">
                    0 bookings selected
                </div>
            </div>
            
            <form id="bulk-action-form" method="POST" action="{{ route('bookings.bulk-action') }}">
                @csrf
                <input type="hidden" name="booking_ids" id="bulk-booking-ids">
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Action:
                        </label>
                        <select name="action" 
                                id="bulk-action-select"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Choose an action...</option>
                            <option value="confirm">Confirm Selected Bookings</option>
                            <option value="cancel">Cancel Selected Bookings</option>
                            <option value="export">Export as CSV</option>
                            <option value="delete">Delete Selected Bookings</option>
                        </select>
                    </div>
                    
                    <!-- Confirmation Message (shown for destructive actions) -->
                    <div id="confirmation-message" class="hidden p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800" id="confirmation-text">
                                    <!-- Dynamic confirmation text -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="hideBulkActionsModal()"
                            class="px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="bulk-action-submit"
                            disabled
                            class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Execute Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let selectedBookings = new Set();

function showBulkActionsModal() {
    updateSelectedBookings();
    if (selectedBookings.size === 0) {
        alert('Please select at least one booking to perform bulk actions.');
        return;
    }
    document.getElementById('bulk-actions-modal').classList.remove('hidden');
    updateSelectedCount();
}

function hideBulkActionsModal() {
    document.getElementById('bulk-actions-modal').classList.add('hidden');
    document.getElementById('bulk-action-form').reset();
    document.getElementById('confirmation-message').classList.add('hidden');
}

function updateSelectedBookings() {
    selectedBookings.clear();
    document.querySelectorAll('.booking-checkbox:checked').forEach(checkbox => {
        selectedBookings.add(checkbox.value);
    });
}

function updateSelectedCount() {
    const count = selectedBookings.size;
    document.getElementById('selected-count').textContent = `${count} booking${count !== 1 ? 's' : ''} selected`;
    document.getElementById('bulk-booking-ids').value = Array.from(selectedBookings).join(',');
}

// Handle action selection
document.getElementById('bulk-action-select').addEventListener('change', function() {
    const action = this.value;
    const submitBtn = document.getElementById('bulk-action-submit');
    const confirmationDiv = document.getElementById('confirmation-message');
    const confirmationText = document.getElementById('confirmation-text');
    
    if (action) {
        submitBtn.disabled = false;
        
        // Show confirmation for destructive actions
        if (action === 'cancel' || action === 'delete') {
            confirmationDiv.classList.remove('hidden');
            const actionText = action === 'delete' ? 'permanently delete' : 'cancel';
            confirmationText.textContent = `Are you sure you want to ${actionText} ${selectedBookings.size} booking${selectedBookings.size !== 1 ? 's' : ''}? This action cannot be undone.`;
        } else {
            confirmationDiv.classList.add('hidden');
        }
    } else {
        submitBtn.disabled = true;
        confirmationDiv.classList.add('hidden');
    }
});

// Handle form submission
document.getElementById('bulk-action-form').addEventListener('submit', function(e) {
    const action = document.getElementById('bulk-action-select').value;
    
    if (action === 'cancel' || action === 'delete') {
        const actionText = action === 'delete' ? 'delete' : 'cancel';
        if (!confirm(`Are you absolutely sure you want to ${actionText} ${selectedBookings.size} booking${selectedBookings.size !== 1 ? 's' : ''}?`)) {
            e.preventDefault();
            return;
        }
    }
    
    // Show loading state
    const submitBtn = document.getElementById('bulk-action-submit');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    submitBtn.disabled = true;
});

// Update selected bookings when checkboxes change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('booking-checkbox') || e.target.id === 'select-all') {
        updateSelectedBookings();
        updateSelectedCount();
    }
});

// Select All Functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.booking-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedBookings();
    updateSelectedCount();
});
</script>
