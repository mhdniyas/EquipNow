<x-admin-layout>
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $combo->name }}</h1>
                <p class="text-gray-600">Combo package details and information</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                <a href="{{ route('combos.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Combos
                </a>
                <a href="{{ route('combos.edit', $combo) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Combo
                </a>
                <form action="{{ route('combos.toggle-status', $combo) }}" method="POST" class="w-full sm:w-auto inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 {{ $combo->status === 'active' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-power-off mr-2"></i>
                        {{ $combo->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Combo Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Name</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $combo->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Category</label>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $combo->category->name }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Price</label>
                                    <p class="mt-1">
                                        <x-price :amount="$combo->combo_price" size="lg" class="text-gray-900" />
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Description</label>
                                    <p class="mt-1 text-gray-900">{{ $combo->description ?: 'No description provided' }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Created</label>
                                    <p class="mt-1 text-gray-900">{{ $combo->created_at->format('M d, Y g:i A') }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Last Updated</label>
                                    <p class="mt-1 text-gray-900">{{ $combo->updated_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Combo Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Combo Items</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Equipment</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Category</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Quantity</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Daily Rate</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Subtotal</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($combo->items as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center space-x-3">
                                                    <div>
                                                        <div class="font-semibold text-gray-900">{{ $item->equipment->name }}</div>
                                                        @if($item->is_free)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                                FREE
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-gray-600">{{ $item->equipment->category->name }}</td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 font-medium text-gray-900">₹{{ number_format($item->equipment->daily_rate, 2) }}</td>
                                            <td class="py-4 px-4">
                                                @if($item->is_free)
                                                    <span class="text-green-600 font-semibold">₹0.00 (FREE)</span>
                                                @else
                                                    <span class="font-medium text-gray-900">₹{{ number_format($item->equipment->daily_rate * $item->quantity, 2) }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($item->equipment->status === 'available')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                        Available
                                                    </span>
                                                @elseif($item->equipment->status === 'rented')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                                        <div class="w-2 h-2 bg-amber-400 rounded-full mr-2"></div>
                                                        Rented
                                                    </span>
                                                @elseif($item->equipment->status === 'maintenance')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                        Maintenance
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($item->equipment->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                @if($combo->bookings && $combo->bookings->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Booking ID</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Customer</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Start Date</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-900">End Date</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($combo->bookings->take(5) as $booking)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="py-4 px-4">
                                                    <a href="{{ route('bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                                        #{{ $booking->id }}
                                                    </a>
                                                </td>
                                                <td class="py-4 px-4 font-medium text-gray-900">{{ $booking->customer->name }}</td>
                                                <td class="py-4 px-4 text-gray-600">{{ $booking->start_date->format('M d, Y') }}</td>
                                                <td class="py-4 px-4 text-gray-600">{{ $booking->end_date->format('M d, Y') }}</td>
                                                <td class="py-4 px-4">
                                                    @if($booking->status === 'pending')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                                            <div class="w-2 h-2 bg-amber-400 rounded-full mr-2"></div>
                                                            Pending
                                                        </span>
                                                    @elseif($booking->status === 'confirmed')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                                                            Confirmed
                                                        </span>
                                                    @elseif($booking->status === 'active')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                            Active
                                                        </span>
                                                    @elseif($booking->status === 'completed')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                            <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                                            Completed
                                                        </span>
                                                    @elseif($booking->status === 'cancelled')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                            <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                            Cancelled
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-4 font-medium text-gray-900">₹{{ number_format($booking->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($combo->bookings->count() > 5)
                                <div class="text-center mt-6">
                                    <a href="{{ route('bookings.index', ['combo_id' => $combo->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        View All Bookings
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Price Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Price Summary</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Individual Total:</span>
                                <span class="font-semibold text-gray-900">₹{{ number_format($individualTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Combo Price:</span>
                                <span class="font-semibold text-green-600">₹{{ number_format($combo->combo_price, 2) }}</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Savings:</span>
                                <span class="font-semibold text-green-600">₹{{ number_format($savings, 2) }}</span>
                            </div>
                            <div class="text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ number_format($savingsPercentage, 1) }}% OFF
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Items</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $combo->items->count() }}</div>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-boxes text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Free Items</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $combo->items->where('is_free', true)->count() }}</div>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Bookings</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $combo->bookings ? $combo->bookings->count() : 0 }}</div>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-purple-600"></i>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Bookings</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $combo->bookings ? $combo->bookings->whereIn('status', ['pending', 'confirmed', 'active'])->count() : 0 }}
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-amber-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipment Availability -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Equipment Availability</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($combo->items as $item)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $item->equipment->name }}</div>
                                        <small class="text-gray-500">Qty: {{ $item->quantity }}</small>
                                    </div>
                                    <div>
                                        @if($item->equipment->status === 'available')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                Available
                                            </span>
                                        @elseif($item->equipment->status === 'rented')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                                <div class="w-2 h-2 bg-amber-400 rounded-full mr-2"></div>
                                                Rented
                                            </span>
                                        @elseif($item->equipment->status === 'maintenance')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                Maintenance
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($item->equipment->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('bookings.create', ['combo_id' => $combo->id]) }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-calendar-plus mr-2"></i>Create Booking
                            </a>
                            <a href="{{ route('combos.edit', $combo) }}" class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit Combo
                            </a>
                            <form action="{{ route('combos.toggle-status', $combo) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 {{ $combo->status === 'active' ? 'border border-amber-300 hover:bg-amber-50 text-amber-700' : 'border border-green-300 hover:bg-green-50 text-green-700' }} font-medium rounded-lg transition-colors">
                                    <i class="fas fa-power-off mr-2"></i>
                                    {{ $combo->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
