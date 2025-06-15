<x-admin-layout title="Create Booking" subtitle="Create a new equipment booking">
    <div class="max-w-6xl mx-auto">
        <!-- Progress Stepper -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium text-indigo-600">Customer</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div id="step-2" class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Equipment</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div id="step-3" class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Dates & Pricing</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <div class="flex items-center">
                        <div id="step-4" class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            4
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Review</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6" id="booking-form">
            @csrf
            
            <!-- Step 1: Customer Selection -->
            <div id="step-1-content" class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    <p class="text-sm text-gray-600 mt-1">Select or add a customer for this booking</p>
                </div>
                
                <div class="p-6">
                    <!-- Customer Selection -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Existing Customers -->
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-3">
                                Existing Customer <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Customer Search -->
                            <div class="mb-3">
                                <div class="relative">
                                    <input type="text" 
                                           id="customer-search-input"
                                           placeholder="Search customers by name, email, or phone..."
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <div id="customer-search-spinner" class="hidden">
                                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Customer List -->
                            <div id="customer-list" class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg">
                                <div id="customer-results">
                                    @foreach($customers as $customer)
                                        <div class="customer-option p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                             data-customer-id="{{ $customer->id }}"
                                             data-customer-name="{{ $customer->name }}"
                                             data-customer-email="{{ $customer->email }}"
                                             data-customer-phone="{{ $customer->phone }}"
                                             onclick="selectCustomer({{ $customer->id }}, '{{ $customer->name }}', '{{ $customer->email }}', '{{ $customer->phone }}')">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                        <span class="text-indigo-600 font-medium">{{ strtoupper(substr($customer->name, 0, 2)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $customer->email }}</p>
                                                    @if($customer->phone)
                                                        <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- No results message -->
                                <div id="no-customers-message" class="hidden p-6 text-center text-gray-500">
                                    <i class="fas fa-user-slash text-2xl mb-2"></i>
                                    <p>No customers found</p>
                                    <p class="text-sm">Try a different search term or add a new customer</p>
                                </div>
                                
                                <!-- Load more button -->
                                <div id="load-more-customers" class="hidden p-4 text-center border-t border-gray-200">
                                    <button type="button" onclick="loadMoreCustomers()" 
                                            class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                        <i class="fas fa-plus mr-1"></i>
                                        Load More Customers
                                    </button>
                                </div>
                            </div>
                            
                            <input type="hidden" name="customer_id" id="customer_id" required>
                        </div>

                        <!-- Quick Add Customer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Quick Add New Customer</label>
                            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                                <div>
                                    <input type="text" 
                                           id="new-customer-name"
                                           placeholder="Customer Name"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <input type="email" 
                                           id="new-customer-email"
                                           placeholder="Email Address"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <input type="tel" 
                                           id="new-customer-phone"
                                           placeholder="Phone Number"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <button type="button" 
                                        onclick="createNewCustomer()"
                                        class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Customer
                                </button>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="{{ route('customers.create') }}" 
                                   target="_blank"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    Create Detailed Customer Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Customer Preview -->
                    <div id="selected-customer" class="hidden mt-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-lg font-medium text-indigo-900" id="selected-customer-name"></h4>
                                <p class="text-sm text-indigo-700" id="selected-customer-email"></p>
                                <p class="text-sm text-indigo-700" id="selected-customer-phone"></p>
                            </div>
                            <button type="button" 
                                    onclick="clearCustomerSelection()"
                                    class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Equipment Selection -->
            <div id="step-2-content" class="hidden bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Equipment Selection</h3>
                    <p class="text-sm text-gray-600 mt-1">Choose equipment items for this rental</p>
                </div>
                
                <div class="p-6">
                    <!-- Equipment Search and Filter -->
                    <div class="mb-6">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" 
                                           id="equipment-search"
                                           placeholder="Search equipment by name, category..."
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:w-48">
                                <select id="category-filter" 
                                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Categories</option>
                                    <!-- Add categories dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Equipment Summary -->
                    <div id="selected-equipment-summary" class="hidden mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-sm font-medium text-green-900 mb-2">Selected Equipment</h4>
                        <div id="selected-equipment-list" class="space-y-2"></div>
                        <div class="mt-3 pt-3 border-t border-green-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-green-700">Total Items:</span>
                                <span class="font-medium text-green-900" id="total-items">0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-700">Estimated Daily Rate:</span>
                                <span class="font-medium text-green-900" id="estimated-daily-rate">₹0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="equipment-grid">
                        @foreach($equipment as $item)
                            <div class="equipment-card border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors duration-200 cursor-pointer"
                                 data-equipment-id="{{ $item->id }}"
                                 data-equipment-name="{{ $item->name }}"
                                 data-equipment-rate="{{ $item->daily_rate }}"
                                 data-equipment-category="{{ $item->category->name ?? '' }}"
                                 onclick="toggleEquipmentSelection({{ $item->id }}, '{{ $item->name }}', {{ $item->daily_rate }})">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $item->name }}</h4>
                                        @if($item->category)
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->category->name }}</p>
                                        @endif
                                        <p class="text-sm font-bold text-indigo-600 mt-2">₹{{ number_format($item->daily_rate, 2) }}/day</p>
                                        @if($item->description)
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="equipment-checkbox w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs hidden"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quantity Selector (initially hidden) -->
                                <div class="quantity-selector hidden mt-4 pt-4 border-t border-gray-200">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                onclick="event.stopPropagation(); decreaseQuantity({{ $item->id }})"
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors duration-200">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantities[{{ $item->id }}]"
                                               value="1" 
                                               min="1"
                                               max="{{ $item->quantity }}"
                                               class="quantity-input w-16 text-center text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               onclick="event.stopPropagation()"
                                               onchange="updateQuantity({{ $item->id }}, this.value)">
                                        <button type="button" 
                                                onclick="event.stopPropagation(); increaseQuantity({{ $item->id }})"
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors duration-200">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Available: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Hidden inputs for selected equipment -->
                    <div id="selected-equipment-inputs"></div>
                </div>
            </div>

            <!-- Step 3: Dates & Pricing -->
            <div id="step-3-content" class="hidden bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Rental Period & Pricing</h3>
                    <p class="text-sm text-gray-600 mt-1">Set the rental dates and configure pricing</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Rental Dates -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Rental Period</h4>
                            <div class="space-y-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           name="start_date" 
                                           id="start_date"
                                           value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                           min="{{ now()->format('Y-m-d') }}"
                                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           required
                                           onchange="calculatePricing()">
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
                                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           required
                                           onchange="calculatePricing()">
                                </div>
                                
                                <div id="rental-summary" class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <span class="font-medium">Rental Duration:</span> 
                                        <span id="rental-days">1 day</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Additional Options -->
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Additional Options</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="delivery_required" 
                                               value="1"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Delivery Required</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="setup_required" 
                                               value="1"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Setup Service Required</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="insurance_required" 
                                               value="1"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Equipment Insurance</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Breakdown -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Pricing Breakdown</h4>
                            
                            <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                                <div id="equipment-pricing" class="space-y-2">
                                    <!-- Equipment pricing will be populated by JavaScript -->
                                </div>
                                
                                <div class="border-t border-gray-300 pt-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium" id="subtotal">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Delivery:</span>
                                        <span class="font-medium" id="delivery-cost">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Setup:</span>
                                        <span class="font-medium" id="setup-cost">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Insurance:</span>
                                        <span class="font-medium" id="insurance-cost">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tax ({{ config('app.tax_rate', 18) }}%):</span>
                                        <span class="font-medium" id="tax-amount">₹0.00</span>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-400 pt-3">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Total Amount:</span>
                                        <span class="text-indigo-600" id="total-amount">₹0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Deposit and Payment -->
                            <div class="mt-6 space-y-4">
                                <div>
                                    <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                        Security Deposit
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                        <input type="number" 
                                               name="deposit_amount" 
                                               id="deposit_amount"
                                               value="{{ old('deposit_amount', 0) }}"
                                               min="0"
                                               step="0.01"
                                               class="block w-full pl-8 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Security deposit to be collected from customer</p>
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                        Payment Method
                                    </label>
                                    <select name="payment_method" 
                                            id="payment_method"
                                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="cash">Cash</option>
                                        <option value="card">Credit/Debit Card</option>
                                        <option value="upi">UPI</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes Section -->
                    <div class="mt-8">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Notes
                        </label>
                        <textarea name="notes" 
                                  id="notes"
                                  rows="3"
                                  placeholder="Any special instructions or notes for this booking..."
                                  class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Hidden inputs for totals -->
                    <input type="hidden" name="total_rent" id="total_rent" value="0">
                </div>
            </div>

            <!-- Step 4: Review & Submit -->
            <div id="step-4-content" class="hidden bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Review Booking</h3>
                    <p class="text-sm text-gray-600 mt-1">Review all details before creating the booking</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Booking Summary -->
                        <div class="space-y-6">
                            <!-- Customer Summary -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Customer Details</h4>
                                <div id="review-customer-details">
                                    <!-- Populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Equipment Summary -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Equipment Details</h4>
                                <div id="review-equipment-details">
                                    <!-- Populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Rental Details -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Rental Details</h4>
                                <div id="review-rental-details">
                                    <!-- Populated by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Final Pricing -->
                        <div>
                            <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                                <h4 class="text-lg font-medium text-indigo-900 mb-4">Final Pricing</h4>
                                <div id="review-pricing-details">
                                    <!-- Populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mt-6">
                                <label class="flex items-start">
                                    <input type="checkbox" 
                                           name="terms_accepted" 
                                           value="1"
                                           required
                                           class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        I confirm that all details are correct and agree to the 
                                        <a href="#" class="text-indigo-600 hover:text-indigo-700 underline">terms and conditions</a>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between">
                    <button type="button" 
                            id="prev-btn"
                            onclick="previousStep()"
                            class="hidden px-6 py-3 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Previous
                    </button>
                    
                    <div class="ml-auto flex space-x-4">
                        <button type="button" 
                                id="next-btn"
                                onclick="nextStep()"
                                class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        
                        <button type="submit" 
                                id="submit-btn"
                                class="hidden px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>
                            Create Booking
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        let currentStep = 1;
        let selectedCustomer = null;
        let selectedEquipment = {};

        // Step Navigation
        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < 4) {
                    currentStep++;
                    updateStepDisplay();
                }
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        }

        function updateStepDisplay() {
            // Hide all steps
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`step-${i}-content`).classList.add('hidden');
                const stepCircle = document.getElementById(`step-${i}`);
                if (stepCircle) {
                    stepCircle.classList.remove('bg-indigo-600', 'text-white');
                    stepCircle.classList.add('bg-gray-300', 'text-gray-500');
                }
            }

            // Show current step
            document.getElementById(`step-${currentStep}-content`).classList.remove('hidden');
            const currentStepCircle = document.getElementById(`step-${currentStep}`);
            if (currentStepCircle) {
                currentStepCircle.classList.remove('bg-gray-300', 'text-gray-500');
                currentStepCircle.classList.add('bg-indigo-600', 'text-white');
            }

            // Update navigation buttons
            document.getElementById('prev-btn').classList.toggle('hidden', currentStep === 1);
            document.getElementById('next-btn').classList.toggle('hidden', currentStep === 4);
            document.getElementById('submit-btn').classList.toggle('hidden', currentStep !== 4);

            // Load step-specific data
            if (currentStep === 4) {
                populateReviewStep();
            }
        }

        function validateCurrentStep() {
            switch (currentStep) {
                case 1:
                    if (!selectedCustomer) {
                        alert('Please select a customer');
                        return false;
                    }
                    return true;
                case 2:
                    if (Object.keys(selectedEquipment).length === 0) {
                        alert('Please select at least one equipment item');
                        return false;
                    }
                    return true;
                case 3:
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    if (!startDate || !endDate) {
                        alert('Please select both start and end dates');
                        return false;
                    }
                    if (new Date(startDate) >= new Date(endDate)) {
                        alert('End date must be after start date');
                        return false;
                    }
                    return true;
                case 4:
                    return document.querySelector('input[name="terms_accepted"]').checked;
                default:
                    return true;
            }
        }

        // Customer Management
        function selectCustomer(id, name, email, phone) {
            selectedCustomer = { id, name, email, phone };
            document.getElementById('customer_id').value = id;
            
            // Update selected customer display
            document.getElementById('selected-customer').classList.remove('hidden');
            document.getElementById('selected-customer-name').textContent = name;
            document.getElementById('selected-customer-email').textContent = email;
            document.getElementById('selected-customer-phone').textContent = phone || 'No phone provided';
            
            // Highlight selected customer
            document.querySelectorAll('.customer-option').forEach(option => {
                option.classList.remove('bg-indigo-50', 'border-indigo-300');
                option.classList.add('hover:bg-gray-50');
            });
            
            const selectedOption = document.querySelector(`[data-customer-id="${id}"]`);
            if (selectedOption) {
                selectedOption.classList.add('bg-indigo-50', 'border-indigo-300');
                selectedOption.classList.remove('hover:bg-gray-50');
            }
        }

        function clearCustomerSelection() {
            selectedCustomer = null;
            document.getElementById('customer_id').value = '';
            document.getElementById('selected-customer').classList.add('hidden');
            
            document.querySelectorAll('.customer-option').forEach(option => {
                option.classList.remove('bg-indigo-50', 'border-indigo-300');
                option.classList.add('hover:bg-gray-50');
            });
        }

        function createNewCustomer() {
            const name = document.getElementById('new-customer-name').value;
            const email = document.getElementById('new-customer-email').value;
            const phone = document.getElementById('new-customer-phone').value;

            if (!name || !email) {
                alert('Please provide at least name and email for the new customer');
                return;
            }

            // AJAX call to create customer
            fetch('/admin/customers/quick-create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name, email, phone })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectCustomer(data.customer.id, data.customer.name, data.customer.email, data.customer.phone);
                    // Clear form
                    document.getElementById('new-customer-name').value = '';
                    document.getElementById('new-customer-email').value = '';
                    document.getElementById('new-customer-phone').value = '';
                } else {
                    alert('Error creating customer: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the customer.');
            });
        }

        // Equipment Management
        function toggleEquipmentSelection(id, name, rate) {
            const card = document.querySelector(`[data-equipment-id="${id}"]`);
            const checkbox = card.querySelector('.equipment-checkbox');
            const quantitySelector = card.querySelector('.quantity-selector');
            
            if (selectedEquipment[id]) {
                // Deselect
                delete selectedEquipment[id];
                card.classList.remove('border-indigo-500', 'bg-indigo-50');
                checkbox.classList.remove('bg-indigo-600', 'border-indigo-600');
                checkbox.classList.add('border-gray-300');
                checkbox.querySelector('i').classList.add('hidden');
                quantitySelector.classList.add('hidden');
            } else {
                // Select
                selectedEquipment[id] = { name, rate, quantity: 1 };
                card.classList.add('border-indigo-500', 'bg-indigo-50');
                checkbox.classList.add('bg-indigo-600', 'border-indigo-600');
                checkbox.classList.remove('border-gray-300');
                checkbox.querySelector('i').classList.remove('hidden');
                quantitySelector.classList.remove('hidden');
            }
            
            updateEquipmentSummary();
            updateSelectedEquipmentInputs();
        }

        function increaseQuantity(id) {
            if (selectedEquipment[id]) {
                selectedEquipment[id].quantity++;
                const input = document.querySelector(`input[name="quantities[${id}]"]`);
                input.value = selectedEquipment[id].quantity;
                updateEquipmentSummary();
            }
        }

        function decreaseQuantity(id) {
            if (selectedEquipment[id] && selectedEquipment[id].quantity > 1) {
                selectedEquipment[id].quantity--;
                const input = document.querySelector(`input[name="quantities[${id}]"]`);
                input.value = selectedEquipment[id].quantity;
                updateEquipmentSummary();
            }
        }

        function updateQuantity(id, quantity) {
            if (selectedEquipment[id]) {
                selectedEquipment[id].quantity = Math.max(1, parseInt(quantity) || 1);
                updateEquipmentSummary();
            }
        }

        function updateEquipmentSummary() {
            const summary = document.getElementById('selected-equipment-summary');
            const list = document.getElementById('selected-equipment-list');
            
            if (Object.keys(selectedEquipment).length === 0) {
                summary.classList.add('hidden');
                return;
            }
            
            summary.classList.remove('hidden');
            list.innerHTML = '';
            
            let totalItems = 0;
            let totalRate = 0;
            
            Object.entries(selectedEquipment).forEach(([id, item]) => {
                totalItems += item.quantity;
                totalRate += item.rate * item.quantity;
                
                const itemElement = document.createElement('div');
                itemElement.className = 'flex justify-between text-sm';
                itemElement.innerHTML = `
                    <span class="text-green-700">${item.name} x ${item.quantity}</span>
                    <span class="font-medium text-green-900">₹${(item.rate * item.quantity).toFixed(2)}/day</span>
                `;
                list.appendChild(itemElement);
            });
            
            document.getElementById('total-items').textContent = totalItems;
            document.getElementById('estimated-daily-rate').textContent = `₹${totalRate.toFixed(2)}`;
        }

        function updateSelectedEquipmentInputs() {
            const container = document.getElementById('selected-equipment-inputs');
            container.innerHTML = '';
            
            Object.keys(selectedEquipment).forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'equipment[]';
                input.value = id;
                container.appendChild(input);
            });
        }

        // Pricing Calculations
        function calculatePricing() {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            
            if (!startDate || !endDate || startDate >= endDate) {
                return;
            }
            
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            document.getElementById('rental-days').textContent = `${days} day${days > 1 ? 's' : ''}`;
            
            let subtotal = 0;
            const equipmentPricing = document.getElementById('equipment-pricing');
            equipmentPricing.innerHTML = '';
            
            Object.entries(selectedEquipment).forEach(([id, item]) => {
                const itemTotal = item.rate * item.quantity * days;
                subtotal += itemTotal;
                
                const itemElement = document.createElement('div');
                itemElement.className = 'flex justify-between text-sm';
                itemElement.innerHTML = `
                    <span class="text-gray-600">${item.name} x ${item.quantity} x ${days} days</span>
                    <span class="font-medium">₹${itemTotal.toFixed(2)}</span>
                `;
                equipmentPricing.appendChild(itemElement);
            });
            
            // Calculate additional costs
            const deliveryCost = document.querySelector('input[name="delivery_required"]').checked ? 500 : 0;
            const setupCost = document.querySelector('input[name="setup_required"]').checked ? 300 : 0;
            const insuranceCost = document.querySelector('input[name="insurance_required"]').checked ? subtotal * 0.02 : 0;
            
            const taxRate = {{ config('app.tax_rate', 18) }} / 100;
            const taxAmount = (subtotal + deliveryCost + setupCost + insuranceCost) * taxRate;
            const totalAmount = subtotal + deliveryCost + setupCost + insuranceCost + taxAmount;
            
            // Update display
            document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
            document.getElementById('delivery-cost').textContent = `₹${deliveryCost.toFixed(2)}`;
            document.getElementById('setup-cost').textContent = `₹${setupCost.toFixed(2)}`;
            document.getElementById('insurance-cost').textContent = `₹${insuranceCost.toFixed(2)}`;
            document.getElementById('tax-amount').textContent = `₹${taxAmount.toFixed(2)}`;
            document.getElementById('total-amount').textContent = `₹${totalAmount.toFixed(2)}`;
            
            // Update hidden input
            document.getElementById('total_rent').value = totalAmount.toFixed(2);
        }

        // Review Step Population
        function populateReviewStep() {
            // Customer details
            if (selectedCustomer) {
                document.getElementById('review-customer-details').innerHTML = `
                    <div class="space-y-1">
                        <p class="text-sm"><span class="font-medium">Name:</span> ${selectedCustomer.name}</p>
                        <p class="text-sm"><span class="font-medium">Email:</span> ${selectedCustomer.email}</p>
                        <p class="text-sm"><span class="font-medium">Phone:</span> ${selectedCustomer.phone || 'Not provided'}</p>
                    </div>
                `;
            }
            
            // Equipment details
            const equipmentHtml = Object.entries(selectedEquipment).map(([id, item]) => 
                `<div class="flex justify-between text-sm">
                    <span>${item.name} x ${item.quantity}</span>
                    <span>₹${item.rate}/day</span>
                </div>`
            ).join('');
            document.getElementById('review-equipment-details').innerHTML = equipmentHtml;
            
            // Rental details
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const days = Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24));
            
            document.getElementById('review-rental-details').innerHTML = `
                <div class="space-y-1">
                    <p class="text-sm"><span class="font-medium">Start Date:</span> ${new Date(startDate).toLocaleDateString()}</p>
                    <p class="text-sm"><span class="font-medium">End Date:</span> ${new Date(endDate).toLocaleDateString()}</p>
                    <p class="text-sm"><span class="font-medium">Duration:</span> ${days} day${days > 1 ? 's' : ''}</p>
                    <p class="text-sm"><span class="font-medium">Payment Method:</span> ${document.getElementById('payment_method').options[document.getElementById('payment_method').selectedIndex].text}</p>
                </div>
            `;
            
            // Pricing details
            document.getElementById('review-pricing-details').innerHTML = document.querySelector('.bg-gray-50 .space-y-3').innerHTML;
        }

        // Customer search functionality with AJAX and pagination
        let currentCustomerPage = 1;
        let searchTimeout;
        let allCustomersLoaded = false;
        
        function searchCustomers(searchTerm = '', page = 1, append = false) {
            const spinner = document.getElementById('customer-search-spinner');
            const resultsContainer = document.getElementById('customer-results');
            const noResultsMessage = document.getElementById('no-customers-message');
            const loadMoreButton = document.getElementById('load-more-customers');
            
            // Show spinner
            spinner.classList.remove('hidden');
            
            // Clear results if not appending
            if (!append) {
                resultsContainer.innerHTML = '';
                currentCustomerPage = 1;
                allCustomersLoaded = false;
            }
            
            // Calculate limit - show 4 customers per page
            const limit = 4;
            const offset = (page - 1) * limit;
            
            fetch(`/admin/bookings/search/customers?search=${encodeURIComponent(searchTerm)}&limit=${limit}&offset=${offset}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide spinner
                    spinner.classList.add('hidden');
                    
                    if (data.customers.length === 0) {
                        if (!append) {
                            noResultsMessage.classList.remove('hidden');
                            loadMoreButton.classList.add('hidden');
                        } else {
                            allCustomersLoaded = true;
                            loadMoreButton.classList.add('hidden');
                        }
                        return;
                    }
                    
                    // Hide no results message
                    noResultsMessage.classList.add('hidden');
                    
                    // Add customers to results
                    data.customers.forEach(customer => {
                        const customerElement = createCustomerElement(customer);
                        resultsContainer.appendChild(customerElement);
                    });
                    
                    // Show/hide load more button
                    if (data.customers.length < limit) {
                        allCustomersLoaded = true;
                        loadMoreButton.classList.add('hidden');
                    } else {
                        loadMoreButton.classList.remove('hidden');
                    }
                } else {
                    console.error('Error searching customers:', data.message);
                    spinner.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                spinner.classList.add('hidden');
            });
        }
        
        function createCustomerElement(customer) {
            const customerDiv = document.createElement('div');
            customerDiv.className = 'customer-option p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200';
            customerDiv.setAttribute('data-customer-id', customer.id);
            customerDiv.setAttribute('data-customer-name', customer.name);
            customerDiv.setAttribute('data-customer-email', customer.email);
            customerDiv.setAttribute('data-customer-phone', customer.phone || '');
            customerDiv.onclick = () => selectCustomer(customer.id, customer.name, customer.email, customer.phone);
            
            customerDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-indigo-600 font-medium">${customer.initials}</span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">${customer.name}</p>
                        <p class="text-xs text-gray-500">${customer.email}</p>
                        ${customer.phone ? `<p class="text-xs text-gray-500">${customer.phone}</p>` : ''}
                        ${customer.booking_count > 0 ? `<p class="text-xs text-blue-600">${customer.booking_count} booking${customer.booking_count > 1 ? 's' : ''}</p>` : ''}
                    </div>
                    <div class="ml-3">
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            `;
            
            return customerDiv;
        }
        
        // Customer search input with debouncing
        document.getElementById('customer-search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();
            
            // Clear timeout to debounce
            clearTimeout(searchTimeout);
            
            // Wait 300ms before searching
            searchTimeout = setTimeout(() => {
                searchCustomers(searchTerm);
            }, 300);
        });
        
        // Load more customers function
        function loadMoreCustomers() {
            if (allCustomersLoaded) return;
            
            const searchTerm = document.getElementById('customer-search-input').value.trim();
            currentCustomerPage++;
            searchCustomers(searchTerm, currentCustomerPage, true);
        }
        
        // Legacy search functionality for equipment (keep existing)
        document.getElementById('customer-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.customer-option').forEach(option => {
                const name = option.dataset.customerName.toLowerCase();
                const email = option.dataset.customerEmail.toLowerCase();
                const phone = option.dataset.customerPhone?.toLowerCase() || '';
                
                if (name.includes(searchTerm) || email.includes(searchTerm) || phone.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        document.getElementById('equipment-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.equipment-card').forEach(card => {
                const name = card.dataset.equipmentName.toLowerCase();
                const category = card.dataset.equipmentCategory.toLowerCase();
                
                if (name.includes(searchTerm) || category.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners for checkboxes
            document.querySelectorAll('input[name="delivery_required"], input[name="setup_required"], input[name="insurance_required"]').forEach(checkbox => {
                checkbox.addEventListener('change', calculatePricing);
            });
            
            // Initialize customer pagination - show load more button if we have exactly 4 customers (indicating more available)
            const customerOptions = document.querySelectorAll('.customer-option');
            const loadMoreButton = document.getElementById('load-more-customers');
            
            if (customerOptions.length >= 4) {
                loadMoreButton.classList.remove('hidden');
            }
        });
    </script>
</x-admin-layout>
