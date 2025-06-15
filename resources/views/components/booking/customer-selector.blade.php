@props(['customers'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
        <p class="text-sm text-gray-600 mt-1">Select or add a customer for this booking</p>
    </div>
    
    <div class="p-4 sm:p-6">
        <!-- Customer Selection -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
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
                <div id="customer-list" class="max-h-80 sm:max-h-96 overflow-y-auto border border-gray-300 rounded-lg">
                    <div id="customer-results">
                        @foreach($customers as $customer)
                            <div class="customer-option p-3 sm:p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                 data-customer-id="{{ $customer->id }}"
                                 data-customer-name="{{ $customer->name }}"
                                 data-customer-email="{{ $customer->email }}"
                                 data-customer-phone="{{ $customer->phone }}"
                                 onclick="selectCustomer({{ $customer->id }}, '{{ $customer->name }}', '{{ $customer->email }}', '{{ $customer->phone }}')">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium text-xs sm:text-sm">{{ strtoupper(substr($customer->name, 0, 2)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $customer->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $customer->email }}</p>
                                        @if($customer->phone)
                                            <p class="text-xs text-gray-500 sm:hidden">{{ $customer->phone }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-2 flex-shrink-0">
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- No results message -->
                    <div id="no-customers-message" class="hidden p-6 text-center text-gray-500">
                        <i class="fas fa-user-slash text-2xl mb-2"></i>
                        <p class="font-medium">No customers found</p>
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
            <h4 class="text-sm font-medium text-indigo-900 mb-2">Selected Customer</h4>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-indigo-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-900" id="selected-customer-name"></p>
                        <p class="text-xs text-indigo-700" id="selected-customer-email"></p>
                        <p class="text-xs text-indigo-700" id="selected-customer-phone"></p>
                    </div>
                </div>
                <button type="button" onclick="clearCustomerSelection()" 
                        class="text-indigo-600 hover:text-indigo-800 text-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
