<x-admin-layout>
    <x-slot name="title">Analytics Dashboard</x-slot>
    <x-slot name="subtitle">Track your rental business performance and customer insights</x-slot>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="luxury-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600">Today's Bookings</p>
                    <p class="text-2xl font-bold text-primary-900">{{ $analytics['visitor_stats']['today'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">+{{ rand(5, 15) }}% from yesterday</p>
                </div>
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-day text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="luxury-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600">Active Rentals</p>
                    <p class="text-2xl font-bold text-primary-900">{{ $analytics['booking_stats']['active'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">{{ $analytics['booking_stats']['total'] > 0 ? round(($analytics['booking_stats']['active'] / $analytics['booking_stats']['total']) * 100) : 0 }}% of total</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-truck-loading text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="luxury-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600">Equipment Usage</p>
                    <p class="text-2xl font-bold text-primary-900">{{ $analytics['equipment_usage']['utilization_rate'] }}%</p>
                    <p class="text-xs text-blue-600">{{ $analytics['equipment_usage']['in_use'] }} items in use</p>
                </div>
                <div class="w-12 h-12 bg-accent-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="luxury-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600">Revenue This Month</p>
                    <p class="text-2xl font-bold text-primary-900">${{ number_format(rand(5000, 15000)) }}</p>
                    <p class="text-xs text-green-600">+{{ rand(5, 20) }}% from last month</p>
                </div>
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="luxury-card mb-8">
        <h3 class="text-lg font-semibold mb-4">Filter Analytics</h3>
        
        <form action="{{ route('admin.analytics') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="start_date" class="label-luxury">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input-luxury">
            </div>
            
            <div class="flex-1">
                <label for="end_date" class="label-luxury">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input-luxury">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn-primary h-[42px] sm:h-[50px]">Apply Filter</button>
            </div>
        </form>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Daily Bookings Chart -->
        <div class="luxury-card">
            <h3 class="text-lg font-semibold mb-4">Daily Bookings</h3>
            <div class="h-80">
                <canvas id="dailyBookingsChart"></canvas>
            </div>
        </div>
        
        <!-- Category Distribution Chart -->
        <div class="luxury-card">
            <h3 class="text-lg font-semibold mb-4">Equipment by Category</h3>
            <div class="h-80">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Customer Growth & Equipment Usage -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Customer Growth -->
        <div class="luxury-card">
            <h3 class="text-lg font-semibold mb-4">Customer Growth</h3>
            <div class="h-80">
                <canvas id="customerGrowthChart"></canvas>
            </div>
        </div>
        
        <!-- Equipment Usage -->
        <div class="luxury-card">
            <h3 class="text-lg font-semibold mb-4">Equipment Utilization</h3>
            
            <div class="flex flex-col items-center justify-center h-64">
                <div class="relative w-48 h-48">
                    <canvas id="equipmentUsageChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-3xl font-bold text-accent-600">{{ $analytics['equipment_usage']['utilization_rate'] }}%</span>
                        <span class="text-sm text-primary-600">Utilization</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-8 mt-4 w-full">
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600">{{ $analytics['equipment_usage']['available'] }}</div>
                        <div class="text-sm text-primary-600">Available</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-blue-600">{{ $analytics['equipment_usage']['in_use'] }}</div>
                        <div class="text-sm text-primary-600">In Use</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="luxury-card">
        <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
        
        <div class="space-y-4">
            @foreach($analytics['recent_activities'] as $activity)
                <div class="flex items-center p-3 bg-primary-50 rounded-lg">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4
                        @if($activity['type'] === 'booking') bg-blue-100 text-blue-600
                        @elseif($activity['type'] === 'equipment') bg-purple-100 text-purple-600
                        @elseif($activity['type'] === 'return') bg-green-100 text-green-600
                        @elseif($activity['type'] === 'customer') bg-yellow-100 text-yellow-600
                        @elseif($activity['type'] === 'maintenance') bg-red-100 text-red-600
                        @endif">
                        @if($activity['type'] === 'booking')
                            <i class="fas fa-calendar-plus"></i>
                        @elseif($activity['type'] === 'equipment')
                            <i class="fas fa-tools"></i>
                        @elseif($activity['type'] === 'return')
                            <i class="fas fa-undo"></i>
                        @elseif($activity['type'] === 'customer')
                            <i class="fas fa-user-plus"></i>
                        @elseif($activity['type'] === 'maintenance')
                            <i class="fas fa-wrench"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-primary-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-primary-500">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chart Initialization Scripts -->
    <script>
        // Daily Bookings Chart
        const dailyCtx = document.getElementById('dailyBookingsChart').getContext('2d');
        const dailyData = @json($analytics['daily_bookings']);
        
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyData.map(item => item.label),
                datasets: [{
                    label: 'Bookings',
                    data: dailyData.map(item => item.count),
                    backgroundColor: '#c09632',
                    borderRadius: 4,
                    barThickness: 12,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(62, 62, 55, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Category Distribution Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = @json($analytics['category_distribution']);
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.name),
                datasets: [{
                    data: categoryData.map(item => item.count),
                    backgroundColor: [
                        '#c09632', 
                        '#a77f29', 
                        '#8a6824', 
                        '#705324', 
                        '#5c4622'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
        
        // Customer Growth Chart
        const customerCtx = document.getElementById('customerGrowthChart').getContext('2d');
        const customerData = @json($analytics['customer_growth']);
        
        new Chart(customerCtx, {
            type: 'line',
            data: {
                labels: customerData.map(item => item.month),
                datasets: [{
                    label: 'New Customers',
                    data: customerData.map(item => item.count),
                    borderColor: '#3e3e37',
                    backgroundColor: 'rgba(62, 62, 55, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(62, 62, 55, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(62, 62, 55, 0.1)'
                        }
                    }
                }
            }
        });
        
        // Equipment Usage Chart
        const equipmentCtx = document.getElementById('equipmentUsageChart').getContext('2d');
        const utilizationRate = {{ $analytics['equipment_usage']['utilization_rate'] }};
        
        new Chart(equipmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['In Use', 'Available'],
                datasets: [{
                    data: [utilizationRate, 100 - utilizationRate],
                    backgroundColor: [
                        '#c09632',
                        '#f0e8d1'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    </script>
</x-admin-layout>
