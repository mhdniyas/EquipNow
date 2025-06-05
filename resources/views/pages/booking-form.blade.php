@extends('layouts.marketplace')

@section('title', 'Book ' . $equipment->name . ' - EquipNow')

@section('content')
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">Book Equipment</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Complete your rental booking for {{ $equipment->name }}
                </p>
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Equipment Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Equipment Details</h3>
                    
                    <div class="flex items-start space-x-4 mb-6">
                        @if($equipment->image_path)
                            <img src="{{ asset('storage/' . $equipment->image_path) }}" 
                                 alt="{{ $equipment->name }}" class="w-24 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tools text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $equipment->name }}</h4>
                            <p class="text-gray-600 mb-2">{{ $equipment->category->name }}</p>
                            <div class="text-2xl font-bold text-blue-600">
                                ₹{{ number_format($equipment->daily_rate) }}<span class="text-sm text-gray-500">/day</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Availability:</span>
                            <span class="font-semibold text-green-600">{{ $equipment->quantity_available }} available</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Deposit Required:</span>
                            <span class="font-semibold">₹{{ number_format($equipment->deposit_amount) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h5 class="font-semibold text-gray-900 mb-2">Rental Terms:</h5>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Minimum rental period: 1 day</li>
                            <li>• Deposit is refundable upon return</li>
                            <li>• Equipment must be returned in good condition</li>
                            <li>• Late returns may incur additional charges</li>
                        </ul>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Booking Information</h3>

                    @if(Auth::check())
                        <div class="mb-6 p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-user-check text-green-600 mr-2"></i>
                                <span class="text-green-800 font-semibold">Logged in as {{ Auth::user()->name }}</span>
                            </div>
                            <p class="text-green-600 text-sm mt-1">Your booking will be faster with pre-filled details!</p>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Have an account?</h4>
                                    <p class="text-gray-600 text-sm">Login for faster booking with saved details</p>
                                </div>
                                <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                                   class="btn-primary text-white px-4 py-2 rounded-lg text-sm">
                                    Login
                                </a>
                            </div>
                        </div>
                    @endif

                    <form id="bookingForm" class="space-y-6">
                        @csrf
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                        @if(Auth::check())
                            <input type="hidden" name="user_authenticated" value="true">
                        @endif

                        <!-- Rental Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="date" name="start_date" required
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" name="end_date" required
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <select name="quantity" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @for($i = 1; $i <= min(5, $equipment->quantity_available); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        @if(!Auth::check())
                            <!-- Customer Information (for guests) -->
                            <div class="border-t pt-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" name="customer_name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" name="customer_email" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="customer_phone" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        @else
                            <!-- Optional customer information update for logged-in users -->
                            <div class="border-t pt-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Additional Information (Optional)</h4>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="customer_phone" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        @endif

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Delivery Address {{ Auth::check() ? '(Optional)' : '' }}
                            </label>
                            <textarea name="customer_address" rows="3" {{ !Auth::check() ? 'required' : '' }}
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter delivery address..."></textarea>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Requirements (Optional)</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any special requirements or notes..."></textarea>
                        </div>

                        <!-- Pricing Summary -->
                        <div id="pricingSummary" class="border-t pt-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Pricing Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Daily Rate:</span>
                                    <span>₹{{ number_format($equipment->daily_rate) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Rental Days:</span>
                                    <span id="rentalDays">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Quantity:</span>
                                    <span id="selectedQuantity">1</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">₹0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Security Deposit:</span>
                                    <span id="depositAmount">₹{{ number_format($equipment->deposit_amount) }}</span>
                                </div>
                                <div class="border-t pt-2 mt-2">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>Total Amount:</span>
                                        <span id="totalAmount">₹{{ number_format($equipment->deposit_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full btn-primary text-white py-4 rounded-lg text-lg font-semibold">
                            <i class="fas fa-calendar-check mr-2"></i>Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const startDateInput = form.querySelector('[name="start_date"]');
            const endDateInput = form.querySelector('[name="end_date"]');
            const quantitySelect = form.querySelector('[name="quantity"]');
            
            const dailyRate = {{ $equipment->daily_rate }};
            const depositPerItem = {{ $equipment->deposit_amount }};

            function updatePricing() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                const quantity = parseInt(quantitySelect.value) || 1;

                if (startDate && endDate && endDate > startDate) {
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include both start and end dates
                    
                    const subtotal = dailyRate * quantity * daysDiff;
                    const deposit = depositPerItem * quantity;
                    const total = subtotal + deposit;

                    document.getElementById('rentalDays').textContent = daysDiff;
                    document.getElementById('selectedQuantity').textContent = quantity;
                    document.getElementById('subtotal').textContent = '₹' + subtotal.toLocaleString();
                    document.getElementById('depositAmount').textContent = '₹' + deposit.toLocaleString();
                    document.getElementById('totalAmount').textContent = '₹' + total.toLocaleString();
                } else {
                    document.getElementById('rentalDays').textContent = '-';
                    document.getElementById('subtotal').textContent = '₹0';
                    document.getElementById('totalAmount').textContent = '₹' + (depositPerItem * parseInt(quantitySelect.value) || 1).toLocaleString();
                }
            }

            // Update end date minimum when start date changes
            startDateInput.addEventListener('change', function() {
                const startDate = new Date(this.value);
                const nextDay = new Date(startDate);
                nextDay.setDate(nextDay.getDate() + 1);
                endDateInput.min = nextDay.toISOString().split('T')[0];
                updatePricing();
            });

            endDateInput.addEventListener('change', updatePricing);
            quantitySelect.addEventListener('change', updatePricing);

            // Handle form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

                const formData = new FormData(form);

                fetch('{{ route("marketplace.booking.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('border-red-500');
                                    // Add error message display
                                }
                            });
                        }
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-calendar-check mr-2"></i>Confirm Booking';
                });
            });

            // Initial pricing calculation
            updatePricing();
        });
    </script>
@endsection
