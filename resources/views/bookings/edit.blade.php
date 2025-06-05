<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Booking #' . $booking->id) }}
            </h2>
            <a href="{{ route('bookings.show', $booking) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Booking
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Booking Status Banner -->
            <div class="mb-6 p-4 rounded-lg 
                @if($booking->status === 'pending') bg-yellow-50 border border-yellow-200
                @elseif($booking->status === 'confirmed') bg-blue-50 border border-blue-200
                @elseif($booking->status === 'active') bg-green-50 border border-green-200
                @elseif($booking->status === 'returned') bg-gray-50 border border-gray-200
                @else bg-red-50 border border-red-200 @endif">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($booking->status === 'pending')
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif($booking->status === 'confirmed')
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif($booking->status === 'active')
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium 
                            @if($booking->status === 'pending') text-yellow-800
                            @elseif($booking->status === 'confirmed') text-blue-800
                            @elseif($booking->status === 'active') text-green-800
                            @elseif($booking->status === 'returned') text-gray-800
                            @else text-red-800 @endif">
                            Status: {{ ucfirst($booking->status) }}
                        </h3>
                        <div class="text-sm 
                            @if($booking->status === 'pending') text-yellow-700
                            @elseif($booking->status === 'confirmed') text-blue-700
                            @elseif($booking->status === 'active') text-green-700
                            @elseif($booking->status === 'returned') text-gray-700
                            @else text-red-700 @endif">
                            @if($booking->status === 'pending')
                                This booking is awaiting confirmation.
                            @elseif($booking->status === 'confirmed')
                                This booking has been confirmed and is ready for pickup.
                            @elseif($booking->status === 'active')
                                This booking is currently active - equipment has been picked up.
                            @elseif($booking->status === 'returned')
                                This booking has been completed and equipment returned.
                            @else
                                This booking has been cancelled.
                            @endif
                            @if(in_array($booking->status, ['active', 'returned']))
                                Limited editing is available for {{ $booking->status }} bookings.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('bookings.update', $booking) }}" id="editBookingForm">
                @csrf
                @method('PUT')
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <!-- Customer Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                                    <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                        {{ in_array($booking->status, ['active', 'returned']) ? 'disabled' : '' }} required>
                                        <option value="">Select a customer...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $customer->id == $booking->customer_id ? 'selected' : '' }}>
                                                {{ $customer->name }} - {{ $customer->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Customer Contact</label>
                                    <div class="mt-1 text-sm text-gray-600" id="customerContact">
                                        {{ $booking->customer->phone ?? 'No phone number' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Dates -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Dates</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" 
                                        value="{{ $booking->start_date->format('Y-m-d') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        {{ $booking->status === 'returned' ? 'disabled' : '' }} required>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" name="end_date" id="end_date" 
                                        value="{{ $booking->end_date->format('Y-m-d') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        {{ $booking->status === 'returned' ? 'disabled' : '' }} required>
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rental Period</label>
                                    <div class="mt-1 text-sm text-gray-600" id="rentalPeriod">
                                        {{ $booking->start_date->diffInDays($booking->end_date) + 1 }} day(s)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Selection -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Equipment</h3>
                            <div id="equipmentContainer">
                                @foreach($booking->equipment as $index => $equipment)
                                <div class="equipment-item bg-gray-50 p-4 rounded-lg mb-4" data-index="{{ $index }}">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Equipment</label>
                                            <select name="equipment[{{ $index }}][id]" class="equipment-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                {{ $booking->status === 'returned' ? 'disabled' : '' }} required>
                                                <option value="">Select equipment...</option>
                                                @foreach($equipment_list as $eq)
                                                    <option value="{{ $eq->id }}" 
                                                        data-daily-rate="{{ $eq->daily_rate }}" 
                                                        data-stock="{{ $eq->stock_quantity }}"
                                                        {{ $eq->id == $equipment->id ? 'selected' : '' }}>
                                                        {{ $eq->name }} - ${{ $eq->daily_rate }}/day (Stock: {{ $eq->stock_quantity }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                            <input type="number" name="equipment[{{ $index }}][quantity]" 
                                                value="{{ $equipment->pivot->quantity }}"
                                                class="quantity-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                min="1" {{ $booking->status === 'returned' ? 'disabled' : '' }} required>
                                        </div>
                                        
                                        <div class="flex items-end">
                                            @if($booking->status !== 'returned')
                                            <button type="button" class="remove-equipment bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                                {{ $index === 0 ? 'disabled' : '' }}>
                                                Remove
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span class="equipment-subtotal">
                                            Subtotal: $<span class="subtotal-amount">{{ number_format($equipment->daily_rate * $equipment->pivot->quantity * ($booking->start_date->diffInDays($booking->end_date) + 1), 2) }}</span>
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($booking->status !== 'returned')
                            <button type="button" id="addEquipment" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Add Equipment
                            </button>
                            @endif
                        </div>

                        <!-- Financial Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="deposit_amount" class="block text-sm font-medium text-gray-700">Deposit Amount</label>
                                    <div class="mt-1 relative">
                                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                                        <input type="number" name="deposit_amount" id="deposit_amount" 
                                            value="{{ $booking->deposit_amount }}"
                                            class="block w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                            step="0.01" min="0" {{ in_array($booking->status, ['active', 'returned']) ? 'disabled' : '' }}>
                                    </div>
                                    @error('deposit_amount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                    <div class="mt-1 text-lg font-semibold text-gray-900" id="subtotalAmount">
                                        ${{ number_format($booking->total_rent, 2) }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                                    <div class="mt-1 text-lg font-semibold text-indigo-600" id="totalAmount">
                                        ${{ number_format($booking->total_rent + $booking->deposit_amount, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Any special instructions or notes...">{{ $booking->notes }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden inputs for calculations -->
                        <input type="hidden" name="total_rent" id="totalRentInput" value="{{ $booking->total_rent }}">
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                        <a href="{{ route('bookings.show', $booking) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        @if($booking->status !== 'returned')
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Booking
                        </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let equipmentIndex = {{ count($booking->equipment) }};
        const equipmentOptions = @json($equipment_list->map(function($equipment) {
            return [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'daily_rate' => $equipment->daily_rate,
                'stock_quantity' => $equipment->stock_quantity
            ];
        }));

        // Calculate rental period when dates change
        function updateRentalPeriod() {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            
            if (startDate && endDate && endDate >= startDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                document.getElementById('rentalPeriod').textContent = diffDays + ' day(s)';
                updateTotalAmount();
            } else {
                document.getElementById('rentalPeriod').textContent = '0 day(s)';
            }
        }

        // Calculate total amount
        function updateTotalAmount() {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            
            if (!startDate || !endDate || endDate < startDate) {
                return;
            }
            
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            let subtotal = 0;
            
            document.querySelectorAll('.equipment-item').forEach(item => {
                const select = item.querySelector('.equipment-select');
                const quantityInput = item.querySelector('.quantity-input');
                
                if (select.value && quantityInput.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    const dailyRate = parseFloat(selectedOption.dataset.dailyRate || 0);
                    const quantity = parseInt(quantityInput.value || 0);
                    const itemSubtotal = dailyRate * quantity * days;
                    
                    subtotal += itemSubtotal;
                    item.querySelector('.subtotal-amount').textContent = itemSubtotal.toFixed(2);
                }
            });
            
            const deposit = parseFloat(document.getElementById('deposit_amount').value || 0);
            const total = subtotal + deposit;
            
            document.getElementById('subtotalAmount').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
            document.getElementById('totalRentInput').value = subtotal.toFixed(2);
        }

        // Add equipment item
        document.getElementById('addEquipment')?.addEventListener('click', function() {
            const container = document.getElementById('equipmentContainer');
            const newItem = document.createElement('div');
            newItem.className = 'equipment-item bg-gray-50 p-4 rounded-lg mb-4';
            newItem.dataset.index = equipmentIndex;
            
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Equipment</label>
                        <select name="equipment[${equipmentIndex}][id]" class="equipment-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select equipment...</option>
                            ${equipmentOptions.map(eq => 
                                `<option value="${eq.id}" data-daily-rate="${eq.daily_rate}" data-stock="${eq.stock_quantity}">
                                    ${eq.name} - $${eq.daily_rate}/day (Stock: ${eq.stock_quantity})
                                </option>`
                            ).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="equipment[${equipmentIndex}][quantity]" class="quantity-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1" value="1" required>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-equipment bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Remove
                        </button>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    <span class="equipment-subtotal">
                        Subtotal: $<span class="subtotal-amount">0.00</span>
                    </span>
                </div>
            `;
            
            container.appendChild(newItem);
            equipmentIndex++;
            
            // Add event listeners to new item
            newItem.querySelector('.equipment-select').addEventListener('change', updateTotalAmount);
            newItem.querySelector('.quantity-input').addEventListener('input', updateTotalAmount);
            newItem.querySelector('.remove-equipment').addEventListener('click', function() {
                newItem.remove();
                updateTotalAmount();
            });
        });

        // Remove equipment item
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-equipment')) {
                e.target.closest('.equipment-item').remove();
                updateTotalAmount();
            }
        });

        // Update customer contact info
        document.getElementById('customer_id').addEventListener('change', function() {
            // This would typically fetch customer details via AJAX
            // For now, we'll just clear the contact info
            document.getElementById('customerContact').textContent = 'Select customer to view contact';
        });

        // Event listeners
        document.getElementById('start_date').addEventListener('change', updateRentalPeriod);
        document.getElementById('end_date').addEventListener('change', updateRentalPeriod);
        document.getElementById('deposit_amount').addEventListener('input', updateTotalAmount);

        // Add event listeners to existing equipment items
        document.querySelectorAll('.equipment-select').forEach(select => {
            select.addEventListener('change', updateTotalAmount);
        });
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', updateTotalAmount);
        });
        document.querySelectorAll('.remove-equipment').forEach(button => {
            button.addEventListener('click', function() {
                if (document.querySelectorAll('.equipment-item').length > 1) {
                    button.closest('.equipment-item').remove();
                    updateTotalAmount();
                }
            });
        });

        // Form validation
        document.getElementById('editBookingForm').addEventListener('submit', function(e) {
            const equipmentItems = document.querySelectorAll('.equipment-item');
            let hasValidEquipment = false;
            
            equipmentItems.forEach(item => {
                const select = item.querySelector('.equipment-select');
                const quantity = item.querySelector('.quantity-input');
                
                if (select.value && quantity.value && parseInt(quantity.value) > 0) {
                    hasValidEquipment = true;
                }
            });
            
            if (!hasValidEquipment) {
                e.preventDefault();
                alert('Please add at least one equipment item with a valid quantity.');
                return;
            }
            
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            
            if (endDate < startDate) {
                e.preventDefault();
                alert('End date must be after or equal to start date.');
                return;
            }
        });
    </script>
    @endpush
</x-admin-layout>
