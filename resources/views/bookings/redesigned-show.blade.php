<x-admin-layout title="Booking Details" subtitle="View and manage booking information">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Booking Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Left Side - Booking Info -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <span class="text-white font-bold text-xl">
                                #{{ $booking->id }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                            <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Created: {{ $booking->created_at->format('M j, Y \a\t g:i A') }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    By: {{ $booking->user->name }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $booking->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Quick Actions -->
                    <div class="flex flex-wrap gap-3">
                        <!-- Status-based Actions -->
                        @if($booking->status == 'pending')
                            <button onclick="confirmBooking({{ $booking->id }})" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-check mr-2"></i>
                                Confirm Booking
                            </button>
                            <button onclick="cancelBooking({{ $booking->id }})" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </button>
                        @endif

                        @if($booking->status == 'confirmed')
                            <button onclick="activateBooking({{ $booking->id }})" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-play mr-2"></i>
                                Mark as Picked Up
                            </button>
                        @endif

                        @if($booking->status == 'active')
                            <a href="{{ route('bookings.return', $booking) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-undo mr-2"></i>
                                Process Return
                            </a>
                        @endif

                        <!-- Universal Actions -->
                        @can('bookings.edit')
                            <a href="{{ route('bookings.edit', $booking) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Booking
                            </a>
                        @endcan

                        <button onclick="printBooking()" 
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-sm">
                            <i class="fas fa-print mr-2"></i>
                            Print
                        </button>

                        <!-- Dropdown Menu for More Actions -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('bookings.invoice', $booking) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-file-invoice mr-2"></i>
                                        Generate Invoice
                                    </a>
                                    <a href="{{ route('bookings.receipt', $booking) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-receipt mr-2"></i>
                                        Print Receipt
                                    </a>
                                    <button onclick="sendNotification({{ $booking->id }})" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-bell mr-2"></i>
                                        Send Notification
                                    </button>
                                    <button onclick="duplicateBooking({{ $booking->id }})" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-copy mr-2"></i>
                                        Duplicate Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Summary Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Booking</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            @if($booking->status == 'active') bg-green-100 text-green-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($booking->status == 'returned') bg-gray-100 text-gray-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Payment</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            @if($booking->payment_status == 'paid') bg-green-100 text-green-800
                            @elseif($booking->payment_status == 'partial') bg-yellow-100 text-yellow-800
                            @elseif($booking->payment_status == 'pending') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>

                    @if($booking->status == 'active')
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center text-blue-800">
                                <i class="fas fa-clock mr-2"></i>
                                <span class="text-sm font-medium">
                                    @if($booking->end_date->isToday())
                                        Due Today
                                    @elseif($booking->end_date->isPast())
                                        Overdue by {{ $booking->end_date->diffForHumans() }}
                                    @else
                                        Due {{ $booking->end_date->diffForHumans() }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Amount</span>
                        <span class="text-sm font-semibold text-gray-900">₹{{ number_format($booking->total_rent, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Deposit</span>
                        <span class="text-sm font-semibold text-gray-900">₹{{ number_format($booking->deposit_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Paid</span>
                        <span class="text-sm font-semibold text-green-600">₹{{ number_format($booking->amount_paid ?? 0, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-900">Balance</span>
                            <span class="text-sm font-bold text-red-600">₹{{ number_format(($booking->total_rent + $booking->deposit_amount) - ($booking->amount_paid ?? 0), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental Period -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rental Period</h3>
                <div class="space-y-3">
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Start Date</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $booking->start_date->format('M j, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->start_date->format('l, g:i A') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">End Date</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $booking->end_date->format('M j, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->end_date->format('l, g:i A') }}</div>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Duration</div>
                        <div class="text-lg font-bold text-indigo-600">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} Days</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Equipment Items</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $booking->equipment->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Quantity</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $booking->equipment->sum('pivot.quantity') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Daily Rate</span>
                        <span class="text-sm font-semibold text-gray-900">₹{{ number_format($booking->total_rent / ($booking->start_date->diffInDays($booking->end_date) + 1), 2) }}</span>
                    </div>
                    @if($booking->payments->count() > 0)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Payments</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $booking->payments->count() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="showTab('details')" 
                            id="tab-details"
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <i class="fas fa-info-circle mr-2"></i>
                        Details
                    </button>
                    <button onclick="showTab('equipment')" 
                            id="tab-equipment"
                            class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-tools mr-2"></i>
                        Equipment
                    </button>
                    <button onclick="showTab('payments')" 
                            id="tab-payments"
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payments
                    </button>
                    <button onclick="showTab('timeline')" 
                            id="tab-timeline"
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <i class="fas fa-history mr-2"></i>
                        Timeline
                    </button>
                    <button onclick="showTab('customer')" 
                            id="tab-customer"
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i>
                        Customer
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Details Tab -->
                <div id="content-details" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h4>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Booking ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ $booking->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Booking Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $booking->booking_date->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($booking->payment_method) }}</dd>
                                </div>
                                @if($booking->notes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $booking->notes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Rental Details</h4>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Rental Period</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $booking->start_date->format('M j, Y') }} to {{ $booking->end_date->format('M j, Y') }}
                                        <span class="text-gray-500">({{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days)</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Equipment</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $booking->equipment->count() }} items ({{ $booking->equipment->sum('pivot.quantity') }} total quantity)</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900">₹{{ number_format($booking->total_rent, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Security Deposit</dt>
                                    <dd class="mt-1 text-sm text-gray-900">₹{{ number_format($booking->deposit_amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Equipment Tab -->
                <div id="content-equipment">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-gray-900">Equipment List</h4>
                            <span class="text-sm text-gray-500">{{ $booking->equipment->count() }} items</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($booking->equipment as $equipment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h5 class="text-lg font-medium text-gray-900">{{ $equipment->name }}</h5>
                                            @if($equipment->category)
                                                <p class="text-sm text-gray-500 mt-1">{{ $equipment->category->name }}</p>
                                            @endif
                                            <div class="mt-3 space-y-2">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Quantity:</span>
                                                    <span class="font-medium">{{ $equipment->pivot->quantity }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Daily Rate:</span>
                                                    <span class="font-medium">₹{{ number_format($equipment->pivot->daily_rate, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Total Cost:</span>
                                                    <span class="font-bold text-indigo-600">
                                                        ₹{{ number_format($equipment->pivot->daily_rate * $equipment->pivot->quantity * ($booking->start_date->diffInDays($booking->end_date) + 1), 2) }}
                                                    </span>
                                                </div>
                                                @if($equipment->pivot->is_free)
                                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-gift mr-1"></i>
                                                        Complimentary
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Equipment Actions -->
                                    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                        <a href="{{ route('equipment.show', $equipment) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            View Details
                                        </a>
                                        @if($booking->status == 'active')
                                            <button onclick="markAsReturned({{ $equipment->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                Mark Returned
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payments Tab -->
                <div id="content-payments" class="hidden">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-gray-900">Payment History</h4>
                            <button onclick="addPayment({{ $booking->id }})" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add Payment
                            </button>
                        </div>

                        @if($booking->payments->count() > 0)
                            <div class="space-y-4">
                                @foreach($booking->payments as $payment)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-rupee-sign text-green-600"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="text-lg font-medium text-gray-900">₹{{ number_format($payment->amount, 2) }}</h5>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span>{{ ucfirst($payment->type) }}</span>
                                                        <span>{{ $payment->created_at->format('M j, Y \a\t g:i A') }}</span>
                                                        @if($payment->reference_number)
                                                            <span>Ref: {{ $payment->reference_number }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($payment->status == 'completed') bg-green-100 text-green-800
                                                    @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                                <button onclick="printReceipt({{ $payment->id }})" 
                                                        class="text-gray-400 hover:text-gray-600">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-4">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <h5 class="text-lg font-medium text-gray-900 mb-2">No Payments Yet</h5>
                                <p class="text-gray-500 mb-4">No payments have been recorded for this booking.</p>
                                <button onclick="addPayment({{ $booking->id }})" 
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Record First Payment
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline Tab -->
                <div id="content-timeline" class="hidden">
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900">Booking Timeline</h4>
                        
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Booking Created -->
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-plus text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Booking created by <span class="font-medium text-gray-900">{{ $booking->user->name }}</span></p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $booking->created_at->format('M j, Y \a\t g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Status Changes -->
                                @if($booking->status != 'pending')
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Booking confirmed</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $booking->updated_at->format('M j, Y \a\t g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                <!-- Payments -->
                                @foreach($booking->payments as $payment)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-rupee-sign text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            Payment of <span class="font-medium text-gray-900">₹{{ number_format($payment->amount, 2) }}</span> 
                                                            received via {{ ucfirst($payment->type) }}
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $payment->created_at->format('M j, Y \a\t g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Customer Tab -->
                <div id="content-customer" class="hidden">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-gray-900">Customer Information</h4>
                            <a href="{{ route('customers.show', $booking->customer) }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                View Full Profile
                            </a>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Customer Details -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-bold text-xl">{{ substr($booking->customer->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <h5 class="text-xl font-semibold text-gray-900">{{ $booking->customer->name }}</h5>
                                        <p class="text-gray-500">Customer since {{ $booking->customer->created_at->format('M Y') }}</p>
                                    </div>
                                </div>

                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->customer->email }}</dd>
                                    </div>
                                    @if($booking->customer->phone)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->customer->phone }}</dd>
                                        </div>
                                    @endif
                                    @if($booking->customer->address)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->customer->address }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- Customer Stats -->
                            <div>
                                <h5 class="text-lg font-medium text-gray-900 mb-4">Customer Statistics</h5>
                                <div class="space-y-4">
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Total Bookings</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $booking->customer->bookings->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Total Spent</span>
                                            <span class="text-sm font-semibold text-gray-900">₹{{ number_format($booking->customer->bookings->sum('total_rent'), 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Active Bookings</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $booking->customer->bookings->where('status', 'active')->count() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="mt-6 space-y-3">
                                    <button onclick="sendCustomerNotification({{ $booking->customer->id }})" 
                                            class="w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                        <i class="fas fa-bell mr-2"></i>
                                        Send Notification
                                    </button>
                                    <a href="{{ route('bookings.create', ['customer_id' => $booking->customer->id]) }}" 
                                       class="block w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Create New Booking
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Enhanced Functionality -->
    <script>
        // Tab Management
        function showTab(tabName) {
            // Hide all tab contents
            const contents = ['details', 'equipment', 'payments', 'timeline', 'customer'];
            contents.forEach(content => {
                document.getElementById(`content-${content}`).classList.add('hidden');
                document.getElementById(`tab-${content}`).classList.remove('border-indigo-500', 'text-indigo-600');
                document.getElementById(`tab-${content}`).classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            // Show selected tab
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            document.getElementById(`tab-${tabName}`).classList.add('border-indigo-500', 'text-indigo-600');
            document.getElementById(`tab-${tabName}`).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        }

        // Booking Actions
        function confirmBooking(bookingId) {
            if (confirm('Are you sure you want to confirm this booking?')) {
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

        function activateBooking(bookingId) {
            if (confirm('Mark equipment as picked up and activate this booking?')) {
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

        function cancelBooking(bookingId) {
            const reason = prompt('Please provide a reason for cancellation:');
            if (reason !== null) {
                fetch(`/admin/bookings/${bookingId}/cancel`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error cancelling booking: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while cancelling the booking.');
                });
            }
        }

        function addPayment(bookingId) {
            // Redirect to payment form or show modal
            window.location.href = `/admin/bookings/${bookingId}/payments/create`;
        }

        function printBooking() {
            window.print();
        }

        function duplicateBooking(bookingId) {
            if (confirm('Create a duplicate of this booking?')) {
                window.location.href = `/admin/bookings/${bookingId}/duplicate`;
            }
        }

        function sendNotification(bookingId) {
            // Implementation for sending notifications
            alert('Notification feature will be implemented here');
        }

        function sendCustomerNotification(customerId) {
            alert('Customer notification feature will be implemented here');
        }

        function printReceipt(paymentId) {
            window.open(`/admin/payments/${paymentId}/receipt`, '_blank');
        }

        function markAsReturned(equipmentId) {
            if (confirm('Mark this equipment as returned?')) {
                // Implementation for marking equipment as returned
                alert('Equipment return feature will be implemented here');
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Show equipment tab by default
            showTab('equipment');
        });
    </script>

    <!-- Print Styles -->
    <style media="print">
        .no-print { display: none !important; }
        body { background: white !important; }
        .bg-gray-50 { background: white !important; }
        .shadow-sm, .shadow-lg { box-shadow: none !important; }
        .border { border: 1px solid #ddd !important; }
    </style>
</x-admin-layout>
