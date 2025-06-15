@props(['bookings'])

<!-- Enhanced Bookings Table -->
<div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
    <!-- Table Header with View Toggle -->
    <div class="px-4 md:px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" 
                           id="select-all"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="select-all" class="text-sm font-medium text-gray-700">Select All</label>
                </div>
                <div class="text-sm text-gray-600">
                    {{ $bookings->total() }} total bookings
                </div>
            </div>
            
            <!-- View Toggle Buttons (Desktop) -->
            <div class="hidden md:flex items-center space-x-2">
                <button type="button" 
                        onclick="showTableView()"
                        id="table-view-btn"
                        class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                    <i class="fas fa-table mr-1"></i>
                    Table
                </button>
                <button type="button" 
                        onclick="showCardView()"
                        id="card-view-btn"
                        class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    <i class="fas fa-th-large mr-1"></i>
                    Cards
                </button>
            </div>
        </div>
    </div>

    <!-- Table View -->
    <div id="table-view" class="hidden md:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Select
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Booking ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Equipment
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
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($booking->equipment->count() > 0)
                                        <div class="font-medium">{{ $booking->equipment->first()->name }}</div>
                                        @if($booking->equipment->count() > 1)
                                            <div class="text-xs text-gray-500">+{{ $booking->equipment->count() - 1 }} more</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">No equipment</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div>{{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    ₹{{ number_format($booking->total_rent, 2) }}
                                </div>
                                @if($booking->delivery_fee > 0 || $booking->setup_fee > 0 || $booking->insurance_fee > 0)
                                    <div class="text-xs text-gray-500">
                                        + fees: ₹{{ number_format($booking->delivery_fee + $booking->setup_fee + $booking->insurance_fee, 2) }}
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
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->payment_status == 'paid') bg-green-100 text-green-800
                                    @elseif($booking->payment_status == 'partial') bg-yellow-100 text-yellow-800
                                    @elseif($booking->payment_status == 'pending') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-booking.table-actions :booking="$booking" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium mb-2">No bookings found</h3>
                                    <p class="text-sm">Try adjusting your search filters or create a new booking.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div id="mobile-card-view" class="block md:hidden p-4">
        <div class="space-y-4">
            @forelse($bookings as $booking)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="booking_ids[]" 
                                       value="{{ $booking->id }}"
                                       class="booking-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <span class="font-bold text-indigo-600 text-xs">#{{ $booking->id }}</span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $booking->customer->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $booking->customer->email }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($booking->status == 'active') bg-green-100 text-green-800
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($booking->status == 'returned') bg-gray-100 text-gray-800
                                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Period:</span>
                                <span class="font-medium">{{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Amount:</span>
                                <span class="font-medium">₹{{ number_format($booking->total_rent, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
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
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Items:</span>
                                    <span class="font-medium">{{ $booking->equipment->count() }} equipment(s)</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <x-booking.mobile-actions :booking="$booking" />
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium mb-2">No bookings found</h3>
                        <p class="text-sm">Try adjusting your search filters or create a new booking.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="px-4 md:px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<script>
function showTableView() {
    document.getElementById('table-view').classList.remove('hidden');
    document.getElementById('card-view').classList.add('hidden');
    document.getElementById('table-view-btn').classList.add('bg-indigo-600', 'text-white');
    document.getElementById('table-view-btn').classList.remove('bg-gray-200', 'text-gray-700');
    document.getElementById('card-view-btn').classList.add('bg-gray-200', 'text-gray-700');
    document.getElementById('card-view-btn').classList.remove('bg-indigo-600', 'text-white');
}

function showCardView() {
    document.getElementById('table-view').classList.add('hidden');
    document.getElementById('card-view').classList.remove('hidden');
    document.getElementById('card-view-btn').classList.add('bg-indigo-600', 'text-white');
    document.getElementById('card-view-btn').classList.remove('bg-gray-200', 'text-gray-700');
    document.getElementById('table-view-btn').classList.add('bg-gray-200', 'text-gray-700');
    document.getElementById('table-view-btn').classList.remove('bg-indigo-600', 'text-white');
}

// Initialize table view as default
document.addEventListener('DOMContentLoaded', function() {
    showTableView();
});
</script>
