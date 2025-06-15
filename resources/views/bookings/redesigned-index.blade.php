<x-admin-layout>
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Enhanced Header with Search and Quick Actions -->
            <div class="mb-8 bg-white shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Booking Management</h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Manage equipment bookings, track rentals, and process returns
                            </p>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            @can('bookings.create')
                                <a href="{{ route('bookings.create') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm">
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
                               class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-calendar mr-2"></i>
                                Calendar View
                            </a>
                        </div>
                    </div>
                    
                    <!-- Advanced Search and Filters -->
                    <div class="mt-6 border-t pt-6">
                        <form method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                            <!-- Search Input -->
                            <div class="lg:col-span-3">
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
                                        class="inline-flex items-center justify-center w-full px-3 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <a href="{{ route('bookings.index') }}" 
                                   class="inline-flex items-center justify-center w-full px-3 py-2.5 bg-gray-300 border border-transparent rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Statistics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up"></i> +{{ $stats['new_this_month'] }} this month
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-play-circle text-green-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active Rentals</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_bookings']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Currently out</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_bookings']) }}</p>
                            <p class="text-xs text-yellow-600 mt-1">Need attention</p>
                        </div>
                    </div>
                </div>

                <!-- Due Today -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Due Today</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['due_today']) }}</p>
                            <p class="text-xs text-red-600 mt-1">Returns expected</p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-indigo-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['revenue_this_month']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">This month</p>
                        </div>
                    </div>
                </div>

                <!-- Average Booking Value -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Avg. Booking</p>
                            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['avg_booking_value'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Per booking</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Bookings Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $bookings->total() }} total bookings</p>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex items-center space-x-2">
                        <button onclick="toggleView('table')" 
                                id="table-view-btn"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            <i class="fas fa-table mr-1"></i> Table
                        </button>
                        <button onclick="toggleView('card')" 
                                id="card-view-btn"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-th-large mr-1"></i> Cards
                        </button>
                    </div>
                </div>

                <!-- Table View -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Booking
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rental Period
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" 
                                               name="booking_ids[]" 
                                               value="{{ $booking->id }}"
                                               class="booking-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                #{{ $booking->id }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $booking->created_at->format('M j, Y') }}
                                            </div>
                                            @if($booking->equipment->count() > 0)
                                                <div class="text-xs text-indigo-600 mt-1">
                                                    {{ $booking->equipment->count() }} item(s)
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ substr($booking->customer->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $booking->customer->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $booking->customer->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            ₹{{ number_format($booking->total_rent, 2) }}
                                        </div>
                                        @if($booking->deposit_amount > 0)
                                            <div class="text-xs text-gray-500">
                                                Deposit: ₹{{ number_format($booking->deposit_amount, 2) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($booking->status == 'active') bg-green-100 text-green-800
                                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($booking->status == 'returned') bg-gray-100 text-gray-800
                                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($booking->payment_status == 'paid') bg-green-100 text-green-800
                                            @elseif($booking->payment_status == 'partial') bg-yellow-100 text-yellow-800
                                            @elseif($booking->payment_status == 'pending') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-100 rounded transition-colors duration-200"
                                               title="View Details">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            
                                            @can('bookings.edit')
                                                <a href="{{ route('bookings.edit', $booking) }}" 
                                                   class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-100 rounded transition-colors duration-200"
                                                   title="Edit">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            @endcan
                                            
                                            <!-- Quick Status Actions -->
                                            @if($booking->status == 'pending')
                                                <button onclick="quickConfirm({{ $booking->id }})"
                                                        class="text-green-600 hover:text-green-900 p-1 hover:bg-green-100 rounded transition-colors duration-200"
                                                        title="Quick Confirm">
                                                    <i class="fas fa-check text-sm"></i>
                                                </button>
                                            @endif
                                            
                                            @if($booking->status == 'confirmed')
                                                <button onclick="quickActivate({{ $booking->id }})"
                                                        class="text-emerald-600 hover:text-emerald-900 p-1 hover:bg-emerald-100 rounded transition-colors duration-200"
                                                        title="Mark as Picked Up">
                                                    <i class="fas fa-play text-sm"></i>
                                                </button>
                                            @endif
                                            
                                            @if($booking->status == 'active')
                                                <button onclick="quickReturn({{ $booking->id }})"
                                                        class="text-purple-600 hover:text-purple-900 p-1 hover:bg-purple-100 rounded transition-colors duration-200"
                                                        title="Process Return">
                                                    <i class="fas fa-undo text-sm"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                            <p class="text-lg font-medium">No bookings found</p>
                                            <p class="text-sm">Try adjusting your search criteria or create a new booking.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Card View (Initially Hidden) -->
                <div id="card-view" class="hidden p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($bookings as $booking)
                            <div class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <span class="font-bold text-indigo-600">#{{ $booking->id }}</span>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $booking->customer->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $booking->customer->email }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($booking->status == 'active') bg-green-100 text-green-800
                                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($booking->status == 'returned') bg-gray-100 text-gray-800
                                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Period:</span>
                                            <span class="font-medium">{{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Amount:</span>
                                            <span class="font-medium">₹{{ number_format($booking->total_rent, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Payment:</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if($booking->payment_status == 'paid') bg-green-100 text-green-800
                                                @elseif($booking->payment_status == 'partial') bg-yellow-100 text-yellow-800
                                                @elseif($booking->payment_status == 'pending') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                        @if($booking->equipment->count() > 0)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Items:</span>
                                                <span class="font-medium">{{ $booking->equipment->count() }} equipment(s)</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-6 flex justify-between items-center">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 p-2 hover:bg-indigo-100 rounded transition-colors duration-200"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('bookings.edit')
                                                <a href="{{ route('bookings.edit', $booking) }}" 
                                                   class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-100 rounded transition-colors duration-200"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                        
                                        <!-- Quick Actions -->
                                        @if($booking->status == 'pending')
                                            <button onclick="quickConfirm({{ $booking->id }})"
                                                    class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors duration-200">
                                                Confirm
                                            </button>
                                        @elseif($booking->status == 'confirmed')
                                            <button onclick="quickActivate({{ $booking->id }})"
                                                    class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded hover:bg-emerald-700 transition-colors duration-200">
                                                Activate
                                            </button>
                                        @elseif($booking->status == 'active')
                                            <button onclick="quickReturn({{ $booking->id }})"
                                                    class="px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700 transition-colors duration-200">
                                                Return
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No bookings found</p>
                                    <p class="text-sm">Try adjusting your search criteria or create a new booking.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulk-actions-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Actions</h3>
                <div class="space-y-3">
                    <button onclick="bulkAction('confirm')" 
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-check mr-2"></i> Confirm Selected
                    </button>
                    <button onclick="bulkAction('cancel')" 
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-times mr-2"></i> Cancel Selected
                    </button>
                    <button onclick="bulkAction('export')" 
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-download mr-2"></i> Export Selected
                    </button>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="hideBulkActionsModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-400 transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Enhanced Functionality -->
    <script>
        // View Toggle Functionality
        function toggleView(view) {
            const tableView = document.getElementById('table-view');
            const cardView = document.getElementById('card-view');
            const tableBtn = document.getElementById('table-view-btn');
            const cardBtn = document.getElementById('card-view-btn');
            
            if (view === 'table') {
                tableView.classList.remove('hidden');
                cardView.classList.add('hidden');
                tableBtn.classList.add('bg-gray-100');
                tableBtn.classList.remove('bg-white', 'border', 'border-gray-300');
                cardBtn.classList.remove('bg-gray-100');
                cardBtn.classList.add('bg-white', 'border', 'border-gray-300');
            } else {
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');
                cardBtn.classList.add('bg-gray-100');
                cardBtn.classList.remove('bg-white', 'border', 'border-gray-300');
                tableBtn.classList.remove('bg-gray-100');
                tableBtn.classList.add('bg-white', 'border', 'border-gray-300');
            }
        }

        // Bulk Actions
        function showBulkActionsModal() {
            document.getElementById('bulk-actions-modal').classList.remove('hidden');
        }

        function hideBulkActionsModal() {
            document.getElementById('bulk-actions-modal').classList.add('hidden');
        }

        function bulkAction(action) {
            const selectedBookings = [];
            document.querySelectorAll('.booking-checkbox:checked').forEach(checkbox => {
                selectedBookings.push(checkbox.value);
            });

            if (selectedBookings.length === 0) {
                alert('Please select at least one booking.');
                return;
            }

            if (confirm(`Are you sure you want to ${action} ${selectedBookings.length} booking(s)?`)) {
                // Implementation for bulk actions
                console.log(`Bulk ${action} for bookings:`, selectedBookings);
                // Add your bulk action logic here
            }
            
            hideBulkActionsModal();
        }

        // Select All Functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.booking-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Quick Actions
        function quickConfirm(bookingId) {
            if (confirm('Confirm this booking?')) {
                // AJAX call to confirm booking
                fetch(`/admin/bookings/${bookingId}/confirm`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error confirming booking: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while confirming the booking.');
                });
            }
        }

        function quickActivate(bookingId) {
            if (confirm('Mark equipment as picked up and activate booking?')) {
                // AJAX call to activate booking
                fetch(`/admin/bookings/${bookingId}/activate`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error activating booking: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while activating the booking.');
                });
            }
        }

        function quickReturn(bookingId) {
            if (confirm('Process return for this booking?')) {
                // Redirect to return processing page
                window.location.href = `/admin/bookings/${bookingId}/return`;
            }
        }

        // Auto-refresh for live updates (optional)
        let autoRefreshInterval;
        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => {
                // Only refresh if no modals are open
                if (document.getElementById('bulk-actions-modal').classList.contains('hidden')) {
                    location.reload();
                }
            }, 30000); // Refresh every 30 seconds
        }

        // Start auto-refresh on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Uncomment the line below to enable auto-refresh
            // startAutoRefresh();
        });
    </script>
</x-admin-layout>
