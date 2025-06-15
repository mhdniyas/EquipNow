<x-admin-layout title="Create Booking" subtitle="Create a new equipment booking">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Progress Stepper -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 md:space-x-8 w-full">
                    <div class="flex items-center">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs md:text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-xs md:text-sm font-medium text-indigo-600">Customer</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div id="step-2" class="w-6 h-6 md:w-8 md:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs md:text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-xs md:text-sm font-medium text-gray-500">Equipment</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div id="step-3" class="w-6 h-6 md:w-8 md:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs md:text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-xs md:text-sm font-medium text-gray-500">Pricing</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div id="step-4" class="w-6 h-6 md:w-8 md:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs md:text-sm font-medium">
                            4
                        </div>
                        <span class="ml-2 text-xs md:text-sm font-medium text-gray-500">Review</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6" id="booking-form">
            @csrf
            
            <!-- Step 1: Customer Selection -->
            <div id="step-1-content" class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    <p class="text-sm text-gray-600 mt-1">Select or add a customer for this booking</p>
                </div>
                
                <div class="p-4 md:p-6">
                    <x-booking.customer-selector :customers="$customers" />
                </div>
            </div>

            <!-- Step 2: Equipment Selection -->
            <div id="step-2-content" class="hidden bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Equipment Selection</h3>
                    <p class="text-sm text-gray-600 mt-1">Choose the equipment for this booking</p>
                </div>
                
                <div class="p-4 md:p-6">
                    <x-booking.equipment-selector :equipment="$equipment" />
                </div>
            </div>

            <!-- Step 3: Dates & Pricing -->
            <div id="step-3-content" class="hidden bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Dates & Pricing</h3>
                    <p class="text-sm text-gray-600 mt-1">Set rental period and calculate pricing</p>
                </div>
                
                <div class="p-4 md:p-6">
                    <x-booking.pricing-calculator />
                </div>
            </div>

            <!-- Step 4: Review & Submit -->
            <div id="step-4-content" class="hidden bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Review & Submit</h3>
                    <p class="text-sm text-gray-600 mt-1">Review all details before creating the booking</p>
                </div>
                
                <div class="p-4 md:p-6">
                    <x-booking.review-summary />
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex flex-col sm:flex-row justify-between bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                <button type="button" 
                        id="prev-step" 
                        class="hidden mb-3 sm:mb-0 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Previous
                </button>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            id="next-step"
                            class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Next
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    
                    <button type="submit" 
                            id="submit-booking"
                            class="hidden inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Create Booking
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Multi-step Form JavaScript -->
    <script>
        let currentStep = 1;
        const totalSteps = 4;

        function showStep(step) {
            // Hide all steps
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById(`step-${i}-content`).classList.add('hidden');
                
                // Update stepper
                const stepElement = document.getElementById(`step-${i}`) || document.querySelector(`[id*="step-${i}"]`);
                if (stepElement) {
                    if (i === step) {
                        stepElement.classList.add('bg-indigo-600', 'text-white');
                        stepElement.classList.remove('bg-gray-300', 'text-gray-500');
                        stepElement.nextElementSibling.classList.add('text-indigo-600');
                        stepElement.nextElementSibling.classList.remove('text-gray-500');
                    } else if (i < step) {
                        stepElement.classList.add('bg-green-600', 'text-white');
                        stepElement.classList.remove('bg-gray-300', 'text-gray-500', 'bg-indigo-600');
                        stepElement.innerHTML = '<i class="fas fa-check"></i>';
                        stepElement.nextElementSibling.classList.add('text-green-600');
                        stepElement.nextElementSibling.classList.remove('text-gray-500', 'text-indigo-600');
                    } else {
                        stepElement.classList.add('bg-gray-300', 'text-gray-500');
                        stepElement.classList.remove('bg-indigo-600', 'text-white', 'bg-green-600');
                        stepElement.innerHTML = i;
                        stepElement.nextElementSibling.classList.add('text-gray-500');
                        stepElement.nextElementSibling.classList.remove('text-indigo-600', 'text-green-600');
                    }
                }
            }
            
            // Show current step
            document.getElementById(`step-${step}-content`).classList.remove('hidden');
            
            // Update navigation buttons
            const prevBtn = document.getElementById('prev-step');
            const nextBtn = document.getElementById('next-step');
            const submitBtn = document.getElementById('submit-booking');
            
            if (step === 1) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }
            
            if (step === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
            
            currentStep = step;
        }

        function nextStep() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        function validateStep(step) {
            // Add validation logic for each step
            switch (step) {
                case 1:
                    const customerId = document.querySelector('input[name="customer_id"]:checked');
                    if (!customerId) {
                        alert('Please select a customer');
                        return false;
                    }
                    break;
                case 2:
                    const selectedEquipment = document.querySelectorAll('input[name="equipment_ids[]"]:checked');
                    if (selectedEquipment.length === 0) {
                        alert('Please select at least one equipment item');
                        return false;
                    }
                    break;
                case 3:
                    const startDate = document.querySelector('input[name="start_date"]').value;
                    const endDate = document.querySelector('input[name="end_date"]').value;
                    if (!startDate || !endDate) {
                        alert('Please select start and end dates');
                        return false;
                    }
                    if (new Date(startDate) >= new Date(endDate)) {
                        alert('End date must be after start date');
                        return false;
                    }
                    break;
            }
            return true;
        }

        // Event listeners
        document.getElementById('next-step').addEventListener('click', nextStep);
        document.getElementById('prev-step').addEventListener('click', prevStep);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            showStep(1);
        });

        // Form submission
        document.getElementById('booking-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = document.getElementById('submit-booking');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
            submitBtn.disabled = true;
            
            // Submit form
            this.submit();
        });
    </script>

    <!-- Mobile-specific styles -->
    <style>
        @media (max-width: 640px) {
            .step-content {
                padding: 1rem;
            }
            
            .stepper-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .navigation-buttons {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e5e7eb;
                padding: 1rem;
                z-index: 10;
            }
        }
        
        /* Touch-friendly elements */
        @media (hover: none) and (pointer: coarse) {
            .step-button {
                min-height: 44px;
                min-width: 44px;
            }
            
            .form-input {
                min-height: 44px;
            }
        }
    </style>
</x-admin-layout>
