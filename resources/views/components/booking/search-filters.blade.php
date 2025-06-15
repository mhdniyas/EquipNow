@props(['searchParams' => []])

<!-- Advanced Search and Filters -->
<div class="mb-8 bg-white shadow-sm rounded-xl border border-gray-100">
    <div class="px-4 md:px-6 py-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Booking Management</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Manage equipment bookings, track rentals, and process returns
                </p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                @can('bookings.create')
                    <a href="{{ route('bookings.create') }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        New Booking
                    </a>
                @endcan
                
                <button type="button" 
                        onclick="showBulkActionsModal()"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-sm">
                    <i class="fas fa-tasks mr-2"></i>
                    Bulk Actions
                </button>
                
                <a href="{{ route('bookings.calendar') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-sm">
                    <i class="fas fa-calendar mr-2"></i>
                    <span class="hidden sm:inline">Calendar View</span>
                    <span class="sm:hidden">Calendar</span>
                </a>
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="border-t pt-6">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                <!-- Search Input -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search bookings, customers..."
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="lg:col-span-2">
                    <select name="status" 
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <!-- Payment Status Filter -->
                <div class="lg:col-span-2">
                    <select name="payment_status" 
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Payments</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Payment Pending</option>
                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                    </select>
                </div>
                
                <!-- Date Range -->
                <div class="lg:col-span-2">
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date') }}"
                           placeholder="Start Date"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                
                <div class="lg:col-span-2">
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date') }}"
                           placeholder="End Date"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                
                <!-- Filter Actions -->
                <div class="lg:col-span-1 flex gap-2">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('bookings.index') }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-gray-300 border border-transparent rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
