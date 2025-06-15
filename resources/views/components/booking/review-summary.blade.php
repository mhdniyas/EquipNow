<!-- Review Summary Component -->
<div class="space-y-6">
    <!-- Customer Summary -->
    <div class="bg-gray-50 rounded-lg p-4 md:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-user mr-2"></i>
            Customer Information
        </h4>
        <div id="customer-summary" class="space-y-2">
            <p class="text-sm text-gray-600">No customer selected</p>
        </div>
    </div>

    <!-- Equipment Summary -->
    <div class="bg-gray-50 rounded-lg p-4 md:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-tools mr-2"></i>
            Selected Equipment
        </h4>
        <div id="equipment-summary" class="space-y-3">
            <p class="text-sm text-gray-600">No equipment selected</p>
        </div>
    </div>

    <!-- Rental Details -->
    <div class="bg-gray-50 rounded-lg p-4 md:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-calendar mr-2"></i>
            Rental Details
        </h4>
        <div id="rental-summary" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Rental details will be populated here -->
        </div>
    </div>

    <!-- Services Summary -->
    <div id="services-summary" class="hidden bg-gray-50 rounded-lg p-4 md:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-concierge-bell mr-2"></i>
            Additional Services
        </h4>
        <div id="services-list" class="space-y-2">
            <!-- Services will be populated here -->
        </div>
    </div>

    <!-- Final Pricing -->
    <div class="bg-indigo-50 rounded-lg p-4 md:p-6 border border-indigo-200">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-calculator mr-2"></i>
            Final Pricing
        </h4>
        <div id="final-pricing-breakdown" class="space-y-2 mb-4">
            <!-- Pricing breakdown will be populated here -->
        </div>
        <div class="pt-4 border-t border-indigo-200">
            <div class="flex justify-between items-center">
                <span class="text-xl font-semibold text-gray-900">Total Amount:</span>
                <span class="text-2xl font-bold text-indigo-600" id="final-total">₹0</span>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions -->
    <div class="bg-yellow-50 rounded-lg p-4 md:p-6 border border-yellow-200">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-file-contract mr-2"></i>
            Terms and Conditions
        </h4>
        <div class="space-y-3 text-sm text-gray-700">
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                <span>Equipment must be returned in the same condition as received</span>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                <span>Late returns will incur additional charges as per our policy</span>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                <span>Customer is responsible for any damage or loss during rental period</span>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                <span>Payment is due upon confirmation of booking</span>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-yellow-200">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" 
                       name="terms_accepted" 
                       id="terms_accepted"
                       required
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                <span class="text-sm font-medium text-gray-900">
                    I agree to the terms and conditions
                </span>
            </label>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="bg-white rounded-lg p-4 md:p-6 border border-gray-200">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-sticky-note mr-2"></i>
            Additional Notes
        </h4>
        <textarea name="notes" 
                  id="booking_notes"
                  rows="4"
                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                  placeholder="Add any special instructions or notes for this booking..."></textarea>
    </div>
</div>

<script>
function updateReviewSummary() {
    updateCustomerSummary();
    updateEquipmentSummary();
    updateRentalSummary();
    updateServicesSummary();
    updateFinalPricing();
}

function updateCustomerSummary() {
    const selectedCustomer = document.querySelector('input[name="customer_id"]:checked');
    const customerSummary = document.getElementById('customer-summary');
    
    if (selectedCustomer) {
        const customerCard = selectedCustomer.closest('.customer-card');
        const customerName = customerCard.querySelector('.customer-name').textContent;
        const customerEmail = customerCard.querySelector('.customer-email').textContent;
        const customerPhone = customerCard.querySelector('.customer-phone').textContent;
        
        customerSummary.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="font-medium text-indigo-600">${customerName.substring(0, 2).toUpperCase()}</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900">${customerName}</p>
                    <p class="text-sm text-gray-600">${customerEmail}</p>
                    <p class="text-sm text-gray-600">${customerPhone}</p>
                </div>
            </div>
        `;
    } else {
        customerSummary.innerHTML = '<p class="text-sm text-gray-600">No customer selected</p>';
    }
}

function updateEquipmentSummary() {
    const selectedEquipment = document.querySelectorAll('input[name="equipment_ids[]"]:checked');
    const equipmentSummary = document.getElementById('equipment-summary');
    
    if (selectedEquipment.length > 0) {
        let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';
        
        selectedEquipment.forEach(checkbox => {
            const equipmentCard = checkbox.closest('.equipment-card');
            const equipmentName = equipmentCard.querySelector('.equipment-name').textContent;
            const equipmentPrice = equipmentCard.querySelector('.equipment-price').textContent;
            const equipmentImage = equipmentCard.querySelector('.equipment-image').src;
            
            html += `
                <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                    <img src="${equipmentImage}" alt="${equipmentName}" class="w-12 h-12 object-cover rounded">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">${equipmentName}</p>
                        <p class="text-sm text-gray-600">${equipmentPrice}</p>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        equipmentSummary.innerHTML = html;
    } else {
        equipmentSummary.innerHTML = '<p class="text-sm text-gray-600">No equipment selected</p>';
    }
}

function updateRentalSummary() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const rentalSummary = document.getElementById('rental-summary');
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        
        rentalSummary.innerHTML = `
            <div class="bg-white p-3 rounded-lg border border-gray-200">
                <p class="text-sm font-medium text-gray-700">Start Date</p>
                <p class="text-lg font-semibold text-gray-900">${start.toLocaleDateString('en-IN', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                })}</p>
            </div>
            <div class="bg-white p-3 rounded-lg border border-gray-200">
                <p class="text-sm font-medium text-gray-700">End Date</p>
                <p class="text-lg font-semibold text-gray-900">${end.toLocaleDateString('en-IN', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                })}</p>
            </div>
            <div class="bg-white p-3 rounded-lg border border-gray-200 md:col-span-2">
                <p class="text-sm font-medium text-gray-700">Rental Duration</p>
                <p class="text-lg font-semibold text-gray-900">${days} day${days !== 1 ? 's' : ''}</p>
            </div>
        `;
    } else {
        rentalSummary.innerHTML = `
            <div class="bg-white p-3 rounded-lg border border-gray-200 md:col-span-2">
                <p class="text-sm text-gray-600">Please select rental dates</p>
            </div>
        `;
    }
}

function updateServicesSummary() {
    const deliveryRequired = document.getElementById('delivery_required').checked;
    const setupRequired = document.getElementById('setup_required').checked;
    const insuranceRequired = document.getElementById('insurance_required').checked;
    const servicesSummary = document.getElementById('services-summary');
    const servicesList = document.getElementById('services-list');
    
    if (deliveryRequired || setupRequired || insuranceRequired) {
        let html = '';
        
        if (deliveryRequired) {
            const deliveryAddress = document.getElementById('delivery_address').value;
            const deliveryDate = document.getElementById('delivery_date').value;
            const deliveryTime = document.getElementById('delivery_time').value;
            
            html += `
                <div class="flex items-start space-x-3">
                    <i class="fas fa-truck text-green-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Delivery Service</p>
                        ${deliveryAddress ? `<p class="text-sm text-gray-600">Address: ${deliveryAddress}</p>` : ''}
                        ${deliveryDate ? `<p class="text-sm text-gray-600">Date: ${new Date(deliveryDate).toLocaleDateString('en-IN')}</p>` : ''}
                        ${deliveryTime ? `<p class="text-sm text-gray-600">Time: ${deliveryTime}</p>` : ''}
                    </div>
                </div>
            `;
        }
        
        if (setupRequired) {
            html += `
                <div class="flex items-start space-x-3">
                    <i class="fas fa-tools text-blue-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Setup Service</p>
                        <p class="text-sm text-gray-600">Professional equipment setup and configuration</p>
                    </div>
                </div>
            `;
        }
        
        if (insuranceRequired) {
            html += `
                <div class="flex items-start space-x-3">
                    <i class="fas fa-shield-alt text-purple-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Insurance Coverage</p>
                        <p class="text-sm text-gray-600">Protection against damage or loss</p>
                    </div>
                </div>
            `;
        }
        
        servicesList.innerHTML = html;
        servicesSummary.classList.remove('hidden');
    } else {
        servicesSummary.classList.add('hidden');
    }
}

function updateFinalPricing() {
    const breakdown = document.getElementById('pricing-breakdown');
    const finalBreakdown = document.getElementById('final-pricing-breakdown');
    const finalTotal = document.getElementById('final-total');
    const totalAmount = document.getElementById('total-amount');
    
    // Copy pricing breakdown to final review
    if (breakdown) {
        finalBreakdown.innerHTML = breakdown.innerHTML;
    }
    
    if (totalAmount) {
        finalTotal.textContent = totalAmount.textContent;
    }
}

// Update review summary when navigating to step 4
document.addEventListener('DOMContentLoaded', function() {
    // Listen for step changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.target.id === 'step-4-content' && !mutation.target.classList.contains('hidden')) {
                updateReviewSummary();
            }
        });
    });
    
    const step4Content = document.getElementById('step-4-content');
    if (step4Content) {
        observer.observe(step4Content, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>
