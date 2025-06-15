@props(['stats'])

<!-- Enhanced Statistics Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6 mb-8">
    <!-- Total Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-blue-600 text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">Total Bookings</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up"></i> +{{ $stats['new_this_month'] }} this month
                </p>
            </div>
        </div>
    </div>

    <!-- Active Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-play-circle text-green-600 text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">Active Rentals</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['active_bookings']) }}</p>
                <p class="text-xs text-gray-500 mt-1">Currently out</p>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">Pending</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['pending_bookings']) }}</p>
                <p class="text-xs text-gray-500 mt-1">Awaiting approval</p>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-rupee-sign text-purple-600 text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">Monthly Revenue</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900">₹{{ number_format($stats['monthly_revenue']) }}</p>
                <p class="text-xs text-gray-500 mt-1">This month</p>
            </div>
        </div>
    </div>

    <!-- Overdue Returns -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">Overdue</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['overdue_returns']) }}</p>
                <p class="text-xs text-gray-500 mt-1">Need attention</p>
            </div>
        </div>
    </div>

    <!-- Equipment Utilization -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">Utilization</p>
                <p class="text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['utilization_rate']) }}%</p>
                <p class="text-xs text-gray-500 mt-1">Equipment usage</p>
            </div>
        </div>
    </div>
</div>
