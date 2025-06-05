<x-admin-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Record Payment</h1>
                        <p class="mt-2 text-sm text-gray-600">Process a new payment transaction for a booking</p>
                    </div>
                    <a href="{{ route('payments.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Payments
                    </a>
                </div>
            </div>
            <form method="POST" action="{{ route('payments.store') }}" id="paymentForm" class="space-y-8">
                @csrf
                
                <!-- Booking Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Booking Information</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if($booking)
                            <!-- Pre-selected booking -->
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Booking ID</label>
                                            <div class="text-lg font-semibold text-gray-900">#{{ $booking->id }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                                            <div class="text-lg font-semibold text-gray-900">{{ $booking->customer->name }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Rental Period</label>
                                            <div class="text-sm text-gray-900">
                                                {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                                            <div class="text-lg font-semibold text-gray-900">₹{{ number_format($booking->total_rent, 2) }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
                                            <div class="text-lg font-semibold text-green-600">₹{{ number_format($booking->payments->where('status', 'completed')->sum('amount'), 2) }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Outstanding Balance</label>
                                            <div class="text-xl font-bold text-red-600">
                                                ₹{{ number_format($booking->total_rent - $booking->payments->where('status', 'completed')->sum('amount'), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Booking selection dropdown -->
                            <div class="space-y-2">
                                <label for="booking_id" class="block text-sm font-medium text-gray-700">Select Booking</label>
                                <select name="booking_id" id="booking_id" class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" required>
                                    <option value="">Choose a booking...</option>
                                    @foreach(App\Models\Booking::with('customer')->where('payment_status', '!=', 'paid')->get() as $availableBooking)
                                        <option value="{{ $availableBooking->id }}" data-customer="{{ $availableBooking->customer->name }}" data-total="{{ $availableBooking->total_rent }}">
                                            #{{ $availableBooking->id }} - {{ $availableBooking->customer->name }} (₹{{ number_format($availableBooking->total_rent, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">₹</span>
                                    </div>
                                    <input type="number" name="amount" id="amount" 
                                           class="block w-full pl-8 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                                           step="0.01" min="0.01" value="{{ old('amount') }}" 
                                           placeholder="0.00" required>
                                </div>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" required>
                                    <option value="">Select payment method...</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                    <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>📱 UPI</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>💳 Card</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>📝 Cheque</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date & Time</label>
                                <input type="datetime-local" name="payment_date" id="payment_date" 
                                       value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}"
                                       class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" required>
                                @error('payment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                                <select name="type" id="type" class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" required>
                                    <option value="">Select payment type...</option>
                                    <option value="rent" {{ old('type') == 'rent' ? 'selected' : '' }}>🏠 Rent Payment</option>
                                    <option value="deposit" {{ old('type') == 'deposit' ? 'selected' : '' }}>🔒 Security Deposit</option>
                                    <option value="refund" {{ old('type') == 'refund' ? 'selected' : '' }}>↩️ Refund</option>
                                    <option value="penalty" {{ old('type') == 'penalty' ? 'selected' : '' }}>⚠️ Penalty/Fine</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                                <input type="text" name="reference_number" id="reference_number" 
                                       value="{{ old('reference_number') }}"
                                       class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"
                                       placeholder="Transaction ID, Check number, Receipt number, etc.">
                                <p class="text-xs text-gray-500">Optional: Add any reference number for this transaction</p>
                                @error('reference_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Amount Selection Card -->
                @if($booking)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Quick Amount Selection</h3>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @php
                                    $paidAmount = $booking->payments->where('status', 'completed')->sum('amount');
                                    $balance = $booking->total_rent - $paidAmount;
                                    $depositAmount = $booking->deposit_amount;
                                    $rentAmount = $booking->total_rent - $booking->deposit_amount;
                                @endphp
                                
                                @if($balance > 0)
                                    <button type="button" 
                                            class="amount-btn group relative bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl p-4 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl" 
                                            data-amount="{{ $balance }}">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Full Balance</span>
                                            <span class="text-lg font-bold">₹{{ number_format($balance, 2) }}</span>
                                        </div>
                                    </button>
                                @endif
                                
                                @if($depositAmount > 0 && $paidAmount < $depositAmount)
                                    <button type="button" 
                                            class="amount-btn group relative bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl p-4 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl" 
                                            data-amount="{{ min($depositAmount, $balance) }}">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Security Deposit</span>
                                            <span class="text-lg font-bold">₹{{ number_format(min($depositAmount, $balance), 2) }}</span>
                                        </div>
                                    </button>
                                @endif
                                
                                @if($rentAmount > 0)
                                    <button type="button" 
                                            class="amount-btn group relative bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-xl p-4 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl" 
                                            data-amount="{{ min($rentAmount, $balance) }}">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Rent Only</span>
                                            <span class="text-lg font-bold">₹{{ number_format(min($rentAmount, $balance), 2) }}</span>
                                        </div>
                                    </button>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-4 text-center">Click on any amount to automatically fill the payment field</p>
                        </div>
                    </div>
                @endif

                <!-- Notes Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Additional Notes</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Payment Notes</label>
                            <textarea name="notes" id="notes" rows="4" 
                                      class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"
                                      placeholder="Add any additional notes about this payment transaction...">{{ old('notes') }}</textarea>
                            <p class="text-xs text-gray-500">Optional: Add any relevant details, special instructions, or comments about this payment</p>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('payments.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Quick amount buttons with enhanced feedback
        document.querySelectorAll('.amount-btn').forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.dataset.amount;
                const amountField = document.getElementById('amount');
                
                // Add visual feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                amountField.value = parseFloat(amount).toFixed(2);
                
                // Add a subtle highlight to the amount field
                amountField.classList.add('ring-2', 'ring-green-400');
                setTimeout(() => {
                    amountField.classList.remove('ring-2', 'ring-green-400');
                }, 1000);
            });
        });

        // Auto-suggest payment type based on booking state
        document.getElementById('booking_id')?.addEventListener('change', function() {
            const selectedBooking = this.value;
            if (selectedBooking) {
                // Auto-focus amount field for better UX
                setTimeout(() => {
                    document.getElementById('amount').focus();
                }, 100);
            }
        });

        // Enhanced form validation with better UX
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount').value);
            const paymentMethod = document.getElementById('payment_method').value;
            const paymentType = document.getElementById('type').value;
            
            let isValid = true;
            let firstErrorField = null;
            
            // Clear previous error states
            document.querySelectorAll('.border-red-500').forEach(field => {
                field.classList.remove('border-red-500');
            });
            
            if (!amount || amount <= 0) {
                const amountField = document.getElementById('amount');
                amountField.classList.add('border-red-500');
                if (!firstErrorField) firstErrorField = amountField;
                isValid = false;
            }
            
            if (!paymentMethod) {
                const methodField = document.getElementById('payment_method');
                methodField.classList.add('border-red-500');
                if (!firstErrorField) firstErrorField = methodField;
                isValid = false;
            }
            
            if (!paymentType) {
                const typeField = document.getElementById('type');
                typeField.classList.add('border-red-500');
                if (!firstErrorField) firstErrorField = typeField;
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Show toast notification
                showToast('Please fill in all required fields correctly.', 'error');
                
                // Focus first error field
                if (firstErrorField) {
                    firstErrorField.focus();
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing Payment...
            `;
            submitBtn.disabled = true;
            
            // Restore button state after timeout (fallback)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 10000);
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-transform duration-300 translate-x-full ${
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'error' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                        }
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after 5 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 5000);
        }

        // Real-time input formatting for amount field
        document.getElementById('amount').addEventListener('input', function() {
            let value = this.value;
            if (value && !isNaN(value)) {
                // Remove any existing error styling
                this.classList.remove('border-red-500');
            }
        });
    </script>
    @endpush
</x-admin-layout>
