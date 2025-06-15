@props(['pricing' => []])

<div class="space-y-6">
    <!-- Date Selection -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-1"></i>
                Start Date
            </label>
            <input type="date" 
                   id="start_date" 
                   name="start_date" 
                   required
                   min="{{ date('Y-m-d') }}"
                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
        
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-1"></i>
                End Date
            </label>
            <input type="date" 
                   id="end_date" 
                   name="end_date" 
                   required
                   min="{{ date('Y-m-d') }}"
                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

    <!-- Additional Services -->
    <div class="bg-gray-50 rounded-lg p-4 md:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Additional Services</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Delivery Service -->
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="delivery_required" 
                               id="delivery_required"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                        <span class="text-sm font-medium text-gray-900">Delivery Service</span>
                    </label>
                </div>
                
                <div id="delivery-details" class="hidden space-y-3">
                    <div>
                        <label for="delivery_address" class="block text-xs font-medium text-gray-700 mb-1">Delivery Address</label>
                        <textarea name="delivery_address" 
                                  id="delivery_address"
                                  rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                                  placeholder="Enter delivery address..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="delivery_date" class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" 
                                   name="delivery_date" 
                                   id="delivery_date"
                                   class="block w-full px-2 py-1 border border-gray-300 rounded text-xs">
                        </div>
                        <div>
                            <label for="delivery_time" class="block text-xs font-medium text-gray-700 mb-1">Time</label>
                            <input type="time" 
                                   name="delivery_time" 
                                   id="delivery_time"
                                   class="block w-full px-2 py-1 border border-gray-300 rounded text-xs">
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 text-sm">
                    <span class="text-gray-600">Fee: </span>
                    <span class="font-medium text-green-600" id="delivery-fee">₹0</span>
                </div>
            </div>

            <!-- Setup Service -->
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="setup_required" 
                               id="setup_required"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                        <span class="text-sm font-medium text-gray-900">Setup Service</span>
                    </label>
                </div>
                
                <p class="text-xs text-gray-500 mb-3">Professional setup and configuration of equipment</p>
                
                <div class="text-sm">
                    <span class="text-gray-600">Fee: </span>
                    <span class="font-medium text-green-600" id="setup-fee">₹0</span>
                </div>
            </div>

            <!-- Insurance -->
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="insurance_required" 
                               id="insurance_required"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                        <span class="text-sm font-medium text-gray-900">Insurance</span>
                    </label>
                </div>
                
                <p class="text-xs text-gray-500 mb-3">Protect against damage or loss</p>
                
                <div class="text-sm">
                    <span class="text-gray-600">Fee: </span>
                    <span class="font-medium text-green-600" id="insurance-fee">₹0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Summary -->
    <div class="bg-indigo-50 rounded-lg p-4 md:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Pricing Summary</h4>
        
        <div id="pricing-breakdown" class="space-y-2">
            <!-- Dynamic pricing will be populated here -->
            <div class="flex justify-between text-sm text-gray-600">
                <span>Loading pricing...</span>
                <span class="animate-pulse">-</span>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-indigo-200">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                <span class="text-2xl font-bold text-indigo-600" id="total-amount">₹0</span>
            </div>
        </div>
    </div>

    <!-- Hidden inputs for calculated fees -->
    <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">
    <input type="hidden" name="setup_fee" id="setup_fee_input" value="0">
    <input type="hidden" name="insurance_fee" id="insurance_fee_input" value="0">
    <input type="hidden" name="total_rent" id="total_rent_input" value="0">
</div>

<script>
// Pricing calculator functionality
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const deliveryCheckbox = document.getElementById('delivery_required');
    const setupCheckbox = document.getElementById('setup_required');
    const insuranceCheckbox = document.getElementById('insurance_required');

    // Show/hide delivery details
    deliveryCheckbox.addEventListener('change', function() {
        const deliveryDetails = document.getElementById('delivery-details');
        if (this.checked) {
            deliveryDetails.classList.remove('hidden');
            // Set default delivery date to start date
            const startDate = startDateInput.value;
            if (startDate) {
                document.getElementById('delivery_date').value = startDate;
            }
        } else {
            deliveryDetails.classList.add('hidden');
        }
        calculatePricing();
    });

    // Calculate pricing when dates change
    startDateInput.addEventListener('change', function() {
        if (this.value) {
            // Set minimum end date
            endDateInput.min = this.value;
            // If end date is before start date, reset it
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            // Update delivery date if delivery is enabled
            if (deliveryCheckbox.checked) {
                document.getElementById('delivery_date').value = this.value;
            }
        }
        calculatePricing();
    });

    endDateInput.addEventListener('change', calculatePricing);
    setupCheckbox.addEventListener('change', calculatePricing);
    insuranceCheckbox.addEventListener('change', calculatePricing);

    function calculatePricing() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) {
            updatePricingDisplay({
                equipment_total: 0,
                delivery_fee: 0,
                setup_fee: 0,
                insurance_fee: 0,
                total: 0,
                days: 0
            });
            return;
        }

        if (new Date(startDate) >= new Date(endDate)) {
            updatePricingDisplay({
                equipment_total: 0,
                delivery_fee: 0,
                setup_fee: 0,
                insurance_fee: 0,
                total: 0,
                days: 0
            });
            return;
        }

        // Get selected equipment
        const selectedEquipment = Array.from(document.querySelectorAll('input[name="equipment_ids[]"]:checked'))
            .map(cb => cb.value);

        if (selectedEquipment.length === 0) {
            updatePricingDisplay({
                equipment_total: 0,
                delivery_fee: 0,
                setup_fee: 0,
                insurance_fee: 0,
                total: 0,
                days: 0
            });
            return;
        }

        // Show loading state
        document.getElementById('pricing-breakdown').innerHTML = `
            <div class="flex justify-between text-sm text-gray-600">
                <span>Calculating pricing...</span>
                <span class="animate-pulse"><i class="fas fa-spinner fa-spin"></i></span>
            </div>
        `;

        // Make AJAX request to calculate pricing
        fetch('/bookings/calculate-pricing', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                equipment_ids: selectedEquipment,
                start_date: startDate,
                end_date: endDate,
                delivery_required: deliveryCheckbox.checked,
                setup_required: setupCheckbox.checked,
                insurance_required: insuranceCheckbox.checked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updatePricingDisplay(data.pricing);
            } else {
                console.error('Pricing calculation error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error calculating pricing:', error);
        });
    }

    function updatePricingDisplay(pricing) {
        const breakdown = document.getElementById('pricing-breakdown');
        const totalAmount = document.getElementById('total-amount');
        
        let html = '';
        
        if (pricing.days > 0) {
            html += `
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700">Equipment rental (${pricing.days} days):</span>
                    <span class="font-medium">₹${parseFloat(pricing.equipment_total).toFixed(2)}</span>
                </div>
            `;
        }
        
        if (pricing.delivery_fee > 0) {
            html += `
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700">Delivery fee:</span>
                    <span class="font-medium">₹${parseFloat(pricing.delivery_fee).toFixed(2)}</span>
                </div>
            `;
        }
        
        if (pricing.setup_fee > 0) {
            html += `
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700">Setup fee:</span>
                    <span class="font-medium">₹${parseFloat(pricing.setup_fee).toFixed(2)}</span>
                </div>
            `;
        }
        
        if (pricing.insurance_fee > 0) {
            html += `
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700">Insurance fee:</span>
                    <span class="font-medium">₹${parseFloat(pricing.insurance_fee).toFixed(2)}</span>
                </div>
            `;
        }
        
        if (html === '') {
            html = `
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Select dates and equipment to calculate pricing</span>
                    <span>-</span>
                </div>
            `;
        }
        
        breakdown.innerHTML = html;
        totalAmount.textContent = `₹${parseFloat(pricing.total).toFixed(2)}`;
        
        // Update fee displays
        document.getElementById('delivery-fee').textContent = `₹${parseFloat(pricing.delivery_fee).toFixed(2)}`;
        document.getElementById('setup-fee').textContent = `₹${parseFloat(pricing.setup_fee).toFixed(2)}`;
        document.getElementById('insurance-fee').textContent = `₹${parseFloat(pricing.insurance_fee).toFixed(2)}`;
        
        // Update hidden inputs
        document.getElementById('delivery_fee_input').value = pricing.delivery_fee;
        document.getElementById('setup_fee_input').value = pricing.setup_fee;
        document.getElementById('insurance_fee_input').value = pricing.insurance_fee;
        document.getElementById('total_rent_input').value = pricing.total;
    }

    // Initial calculation
    calculatePricing();
});
</script>
