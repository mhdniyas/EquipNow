<x-admin-layout title="Booking Details" subtitle="View booking information and manage status">
    <div class="space-y-6">
        <!-- Booking Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-4 sm:px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-accent-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-accent-600 font-bold text-xl">
                                #{{ $booking->id }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                            <div class="flex flex-wrap items-center gap-4 mt-1 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Created: {{ $booking->created_at->format('M j, Y g:i A') }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    By: {{ $booking->user->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <!-- Status Actions -->
                        @if($booking->status == 'pending')
                            <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150"
                                        onclick="return confirm('Confirm this booking?')">
                                    <i class="fas fa-check mr-2"></i>
                                    Confirm Booking
                                </button>
                            </form>
                        @endif

                        @if($booking->status == 'confirmed')
                            <form action="{{ route('bookings.activate', $booking) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150"
                                        onclick="return confirm('Mark equipment as picked up and activate booking?')">
                                    <i class="fas fa-play mr-2"></i>
                                    Activate (Equipment Picked Up)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Total Amount</div>
                        <div class="mt-1">
                            <x-price :amount="$booking->total_amount" size="xl" class="text-gray-900" />
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Deposit Required</div>
                        <div class="mt-1">
                            <x-price :amount="$booking->deposit_amount" size="xl" class="text-gray-900" />
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Amount Paid</div>
                        <div class="mt-1">
                            <x-price :amount="$booking->amount_paid" size="xl" class="text-gray-900" />
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-gray-500">Balance Due</div>
                        <div class="mt-1">
                            <x-price :amount="$booking->balance_amount" size="xl" class="text-gray-900" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Booking Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : 
                               ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                 ($booking->status == 'returned' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Payment Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $booking->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 
                               ($booking->payment_status == 'partial' ? 'bg-yellow-100 text-yellow-800' : 
                                ($booking->payment_status == 'refunded' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                    @if($booking->payment_method)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Payment Method</span>
                            <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rental Period Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rental Period</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Start Date</span>
                        <span class="text-sm text-gray-900">{{ $booking->start_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">End Date</span>
                        <span class="text-sm text-gray-900">{{ $booking->end_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Duration</span>
                        <span class="text-sm text-gray-900">{{ $days }} day{{ $days > 1 ? 's' : '' }}</span>
                    </div>
                    @if($booking->status == 'active' && $booking->end_date->isPast())
                        <div class="p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                <span class="text-sm font-medium text-red-600">Overdue</span>
                            </div>
                            <p class="text-sm text-red-600 mt-1">
                                Equipment was due {{ $booking->end_date->diffForHumans() }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Rental Cost</span>
                        <span class="text-sm text-gray-900">${{ number_format($booking->total_rent, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Deposit</span>
                        <span class="text-sm text-gray-900">${{ number_format($booking->deposit_amount, 2) }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-900">Total Amount</span>
                            <span class="text-sm font-semibold text-gray-900">
                                ${{ number_format($booking->total_rent + $booking->deposit_amount, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    <a href="{{ route('customers.show', $booking->customer) }}" 
                       class="text-accent-600 hover:text-accent-700 text-sm font-medium">
                        View Customer Profile
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-accent-100 flex items-center justify-center">
                        <span class="text-accent-600 font-medium text-lg">
                            {{ strtoupper(substr($booking->customer->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">{{ $booking->customer->name }}</h4>
                        <div class="flex flex-wrap items-center gap-4 mt-1 text-sm text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-1"></i>
                                {{ $booking->customer->email }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-phone mr-1"></i>
                                {{ $booking->customer->phone }}
                            </span>
                            @if($booking->customer->company)
                                <span class="flex items-center">
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $booking->customer->company }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($booking->customer->address)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Address</h5>
                        <p class="text-sm text-gray-600">{{ $booking->customer->address }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Equipment Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Equipment Details</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Equipment
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Daily Rate
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Days
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($booking->equipment as $equipment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-tools text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $equipment->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $equipment->model }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $equipment->category->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $equipment->pivot->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($equipment->pivot->daily_rate, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $days }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ${{ number_format($equipment->pivot->quantity * $equipment->pivot->daily_rate * $days, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                Equipment Subtotal:
                            </td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                ${{ number_format($equipmentTotal, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Additional Notes -->
        @if($booking->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Additional Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $booking->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Actions for Quick Booking -->
        @if($booking->status === 'returned')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('bookings.create', ['customer_id' => $booking->customer_id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Create New Booking for {{ $booking->customer->name }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
