<x-admin-layout title="Customer Details" subtitle="View customer information and booking history">
    <div class="space-y-6">
        <!-- Customer Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center">
                        <div class="h-16 w-16 rounded-full bg-accent-100 flex items-center justify-center">
                            <span class="text-accent-600 font-bold text-xl">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                            <div class="flex flex-wrap items-center gap-4 mt-1 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-envelope mr-1"></i>
                                    {{ $customer->email }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $customer->phone }}
                                </span>
                                @if($customer->company)
                                    <span class="flex items-center">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $customer->company }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 lg:mt-0 flex space-x-3">
                        <a href="{{ route('customers.edit', $customer) }}" 
                           class="inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Customer
                        </a>
                        <a href="{{ route('customers.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Customers
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-100">
                        <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-yellow-100">
                        <i class="fas fa-calendar-plus text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Customer Since</p>
                        <p class="text-lg font-bold text-gray-900">{{ $customer->created_at->format('M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Full Name</div>
                            <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Email Address</div>
                            <div class="font-medium text-gray-900">
                                <a href="mailto:{{ $customer->email }}" class="text-accent-600 hover:text-accent-700">
                                    {{ $customer->email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                            <i class="fas fa-phone text-purple-600"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Phone Number</div>
                            <div class="font-medium text-gray-900">
                                <a href="tel:{{ $customer->phone }}" class="text-accent-600 hover:text-accent-700">
                                    {{ $customer->phone }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($customer->id_number)
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
                                <i class="fas fa-id-card text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">ID Number</div>
                                <div class="font-medium text-gray-900">{{ $customer->id_number }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Address & Company Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Address & Company</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($customer->company)
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-building text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Company</div>
                                <div class="font-medium text-gray-900">{{ $customer->company }}</div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Address</div>
                            <div class="font-medium text-gray-900">{{ $customer->address }}</div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-plus text-purple-600"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Member Since</div>
                            <div class="font-medium text-gray-900">{{ $customer->created_at->format('F j, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Booking History</h3>
                    <button onclick="createNewBooking()" 
                            class="inline-flex items-center px-3 py-2 bg-accent-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        New Booking
                    </button>
                </div>
            </div>

            @if($customer->bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Booking #
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Period
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Equipment
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($customer->bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $booking->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $booking->start_date->format('M j, Y') }}</div>
                                        <div class="text-gray-500">to {{ $booking->end_date->format('M j, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $booking->equipment->count() }} item(s)
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            @foreach($booking->equipment->take(2) as $equipment)
                                                {{ $equipment->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if($booking->equipment->count() > 2)
                                                <span class="text-gray-400">+{{ $booking->equipment->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : 
                                               ($booking->status == 'returned' ? 'bg-blue-100 text-blue-800' : 
                                                ($booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-accent-600 hover:text-accent-900 mr-3" title="View Booking">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($booking->status == 'active')
                                            <a href="#" class="text-blue-600 hover:text-blue-900" title="Process Return">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                        <p class="text-gray-500 mb-4">This customer hasn't made any bookings yet.</p>
                        <button onclick="createNewBooking()" 
                                class="inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Create First Booking
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function createNewBooking() {
            // This would typically redirect to booking creation with customer pre-selected
            alert('Booking creation functionality would be implemented here');
        }
    </script>
</x-admin-layout>
