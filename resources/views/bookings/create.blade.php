<x-admin-layout title="Create Booking" subtitle="Create a new equipment booking">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">New Booking</h3>
                <p class="text-sm text-gray-600 mt-1">Create a new equipment rental booking</p>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" class="p-6 space-y-6" id="booking-form">
                @csrf
                
                <!-- Customer Selection -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Customer Information</h4>
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer <span class="text-red-500">*</span>
                        </label>
                        <select name="customer_id" 
                                id="customer_id"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('customer_id') border-red-500 @enderror"
                                required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        {{ (old('customer_id', $selectedCustomer?->id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-2">
                            <a href="{{ route('customers.create') }}" 
                               class="text-sm text-accent-600 hover:text-accent-700">
                                <i class="fas fa-plus mr-1"></i>
                                Add New Customer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Booking Dates -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Rental Period</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date"
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('start_date') border-red-500 @enderror"
                                   required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date"
                                   value="{{ old('end_date', now()->addDay()->format('Y-m-d')) }}"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('end_date') border-red-500 @enderror"
                                   required>
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div id="rental-days" class="mt-2 text-sm text-gray-600"></div>
                </div>

                <!-- Equipment Selection -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Equipment Selection</h4>
                    <div id="equipment-list">
                        <div class="equipment-item border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Equipment <span class="text-red-500">*</span>
                                    </label>
                                    <select name="equipment[]" 
                                            class="equipment-select block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500"
                                            required>
                                        <option value="">Select equipment</option>
                                        @foreach($equipment as $item)
                                            <option value="{{ $item->id }}" 
                                                    data-rate="{{ $item->daily_rate }}"
                                                    data-name="{{ $item->name }}">
                                                {{ $item->name }} - ${{ number_format($item->daily_rate, 2) }}/day
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="number" 
                                               name="quantities[]" 
                                               class="quantity-input block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500"
                                               value="1" 
                                               min="1" 
                                               required>
                                        <button type="button" 
                                                class="remove-equipment ml-2 text-red-600 hover:text-red-800"
                                                style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="item-total mt-2 text-sm text-gray-600"></div>
                        </div>
                    </div>

                    <button type="button" 
                            id="add-equipment" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Add Equipment
                    </button>

                    @error('equipment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Information -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Payment Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Deposit Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="deposit_amount" 
                                       id="deposit_amount"
                                       value="{{ old('deposit_amount', '0') }}"
                                       min="0" 
                                       step="0.01"
                                       class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('deposit_amount') border-red-500 @enderror"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('deposit_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method
                            </label>
                            <select name="payment_method" 
                                    id="payment_method"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500">
                                <option value="">Select payment method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500"
                              placeholder="Any special instructions or notes...">{{ old('notes') }}</textarea>
                </div>

                <!-- Booking Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Booking Summary</h4>
                    <div id="booking-summary">
                        <div class="text-sm text-gray-600">Select equipment to see pricing breakdown</div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('bookings.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Bookings
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Create Booking
                    </button>
                </div>
                
                <!-- Hidden fields for calculations -->
                <input type="hidden" name="total_rent" id="total_rent" value="0">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const rentalDaysDiv = document.getElementById('rental-days');
            const addEquipmentBtn = document.getElementById('add-equipment');
            const equipmentList = document.getElementById('equipment-list');
            const bookingSummary = document.getElementById('booking-summary');

            let equipmentCount = 1;

            // Update end date minimum when start date changes
            startDateInput.addEventListener('change', function() {
                const startDate = new Date(this.value);
                const nextDay = new Date(startDate);
                nextDay.setDate(nextDay.getDate() + 1);
                endDateInput.min = nextDay.toISOString().split('T')[0];
                
                if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
                    endDateInput.value = nextDay.toISOString().split('T')[0];
                }
                
                updateRentalDays();
                updateBookingSummary();
            });

            // Update rental days when end date changes
            endDateInput.addEventListener('change', function() {
                updateRentalDays();
                updateBookingSummary();
            });

            // Add equipment button
            addEquipmentBtn.addEventListener('click', function() {
                addEquipmentItem();
            });

            // Update calculation when equipment or quantity changes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('equipment-select') || e.target.classList.contains('quantity-input')) {
                    updateItemTotal(e.target.closest('.equipment-item'));
                    updateBookingSummary();
                }
            });

            // Remove equipment item
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-equipment')) {
                    const item = e.target.closest('.equipment-item');
                    item.remove();
                    updateEquipmentNumbers();
                    updateBookingSummary();
                }
            });

            function updateRentalDays() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDate && endDate && endDate > startDate) {
                    const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                    rentalDaysDiv.textContent = `Rental period: ${days} day${days > 1 ? 's' : ''}`;
                } else {
                    rentalDaysDiv.textContent = '';
                }
            }

            function addEquipmentItem() {
                equipmentCount++;
                const template = document.querySelector('.equipment-item').cloneNode(true);
                
                // Reset values
                template.querySelector('.equipment-select').value = '';
                template.querySelector('.quantity-input').value = '1';
                template.querySelector('.item-total').textContent = '';
                
                // Show remove button
                template.querySelector('.remove-equipment').style.display = 'inline-block';
                
                equipmentList.appendChild(template);
                updateEquipmentNumbers();
            }

            function updateEquipmentNumbers() {
                const items = document.querySelectorAll('.equipment-item');
                items.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-equipment');
                    if (index === 0) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'inline-block';
                    }
                });
            }

            function updateItemTotal(item) {
                const select = item.querySelector('.equipment-select');
                const quantityInput = item.querySelector('.quantity-input');
                const totalDiv = item.querySelector('.item-total');
                
                const selectedOption = select.options[select.selectedIndex];
                const rate = parseFloat(selectedOption.dataset.rate) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const days = getRentalDays();
                
                if (rate && quantity && days) {
                    const total = rate * quantity * days;
                    totalDiv.textContent = `${quantity} × $${rate.toFixed(2)}/day × ${days} days = $${total.toFixed(2)}`;
                } else {
                    totalDiv.textContent = '';
                }
            }

            function updateBookingSummary() {
                const items = document.querySelectorAll('.equipment-item');
                const days = getRentalDays();
                let total = 0;
                let summaryHTML = '';
                
                if (days) {
                    summaryHTML += `<div class="mb-2"><strong>Rental Period:</strong> ${days} day${days > 1 ? 's' : ''}</div>`;
                    summaryHTML += '<div class="space-y-1">';
                    
                    items.forEach(item => {
                        const select = item.querySelector('.equipment-select');
                        const quantityInput = item.querySelector('.quantity-input');
                        
                        if (select.value && quantityInput.value) {
                            const selectedOption = select.options[select.selectedIndex];
                            const name = selectedOption.dataset.name;
                            const rate = parseFloat(selectedOption.dataset.rate);
                            const quantity = parseInt(quantityInput.value);
                            const itemTotal = rate * quantity * days;
                            
                            total += itemTotal;
                            summaryHTML += `<div class="flex justify-between text-sm">
                                <span>${name} (${quantity}x)</span>
                                <span>$${itemTotal.toFixed(2)}</span>
                            </div>`;
                        }
                    });
                    
                    summaryHTML += '</div>';
                    summaryHTML += `<div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-medium">
                            <span>Total Rental Cost:</span>
                            <span>$${total.toFixed(2)}</span>
                        </div>
                    </div>`;
                } else {
                    summaryHTML = '<div class="text-sm text-gray-600">Select dates and equipment to see pricing breakdown</div>';
                }
                
                // Update hidden field
                document.getElementById('total_rent').value = total.toFixed(2);
                
                bookingSummary.innerHTML = summaryHTML;
            }

            function getRentalDays() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDate && endDate && endDate > startDate) {
                    return Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                }
                return 0;
            }

            // Initialize
            updateRentalDays();
            updateBookingSummary();
        });
    </script>
</x-admin-layout>
