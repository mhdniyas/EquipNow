<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Welcome to your EquipNow rental management dashboard</x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Total Equipment -->
        <div class="luxury-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-accent-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-white text-lg lg:text-xl"></i>
                    </div>
                </div>
                <div class="ml-3 lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-primary-600">Total Equipment</p>
                    <p class="text-xl lg:text-2xl font-bold text-primary-900">{{ $stats['total_equipment'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="luxury-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-white text-lg lg:text-xl"></i>
                    </div>
                </div>
                <div class="ml-3 lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-primary-600">Categories</p>
                    <p class="text-xl lg:text-2xl font-bold text-primary-900">{{ $stats['total_categories'] }}</p>
                </div>
            </div>
        </div>

        <!-- Bookings -->
        <div class="luxury-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-lg lg:text-xl"></i>
                    </div>
                </div>
                <div class="ml-3 lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-primary-600">Total Bookings</p>
                    <p class="text-xl lg:text-2xl font-bold text-primary-900">{{ $stats['total_bookings'] }}</p>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="luxury-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg lg:text-xl"></i>
                    </div>
                </div>
                <div class="ml-3 lg:ml-4">
                    <p class="text-xs lg:text-sm font-medium text-primary-600">Customers</p>
                    <p class="text-xl lg:text-2xl font-bold text-primary-900">{{ $stats['total_customers'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8">
        <!-- Recent Bookings -->
        <div class="luxury-card">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 lg:mb-6 space-y-2 sm:space-y-0">
                <h3 class="text-lg lg:text-xl font-serif font-semibold text-primary-800">Recent Bookings</h3>
                <a href="{{ route('bookings.index') }}" class="btn-secondary text-xs lg:text-sm px-4 py-2">
                    View All
                </a>
            </div>

            <div class="space-y-3 lg:space-y-4">
                @forelse($stats['recent_bookings'] as $booking)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 lg:p-4 bg-primary-50 rounded-lg space-y-2 sm:space-y-0">
                        <div class="flex-1">
                            <p class="font-medium text-primary-900 text-sm lg:text-base">{{ $booking->customer->name }}</p>
                            <p class="text-xs lg:text-sm text-primary-600">{{ $booking->customer->phone }}</p>
                            <p class="text-xs text-primary-500">{{ $booking->booking_date->diffForHumans() }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-xs lg:text-sm text-primary-600 mb-1">
                                {{ $booking->start_date->format('M d') }} - {{ $booking->end_date->format('M d') }}
                            </p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                @if($booking->status === 'active') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-blue-100 text-blue-800
                                @elseif($booking->status === 'returned') bg-gray-100 text-gray-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-accent-100 text-accent-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-primary-500 py-6 lg:py-8 text-sm lg:text-base">No bookings yet</p>
                @endforelse
            </div>
        </div>

        <!-- Equipment by Category -->
        <div class="luxury-card">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 lg:mb-6 space-y-3 sm:space-y-0">
                <h3 class="text-lg lg:text-xl font-serif font-semibold text-primary-800">Equipment by Category</h3>
            </div>

            <div class="space-y-4">
                @forelse($stats['equipment_by_category'] as $category)
                    <div class="bg-primary-50 rounded-lg p-3 lg:p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-primary-900 text-sm lg:text-base">{{ $category->name }}</span>
                            <span class="text-xs lg:text-sm text-primary-600">{{ $category->equipment_count }} items</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-primary-200 rounded-full h-2">
                                <div class="bg-accent-600 h-2 rounded-full" 
                                     style="width: {{ $stats['total_equipment'] > 0 ? ($category->equipment_count / $stats['total_equipment']) * 100 : 0 }}%">
                                </div>
                            </div>
                            <a href="#" 
                               class="text-primary-600 hover:text-accent-600 transition-colors duration-200 p-1"
                               title="Manage Category">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-primary-500 py-6 lg:py-8 text-sm lg:text-base">No categories found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Equipment Status Overview -->
    <div class="luxury-card mt-8">
        <h3 class="section-subtitle mb-6">Equipment Status Overview</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">
            <div class="luxury-card text-center p-4 lg:p-6">
                <div class="text-xl lg:text-2xl font-bold text-green-600">{{ $stats['available_equipment'] }}</div>
                <div class="text-xs lg:text-sm text-primary-600">Available Equipment</div>
            </div>
            
            <div class="luxury-card text-center p-4 lg:p-6">
                <div class="text-xl lg:text-2xl font-bold text-blue-600">{{ $stats['in_use_equipment'] }}</div>
                <div class="text-xs lg:text-sm text-primary-600">Equipment In Use</div>
            </div>
            
            <div class="luxury-card text-center p-4 lg:p-6">
                <div class="text-xl lg:text-2xl font-bold text-yellow-600">{{ $stats['maintenance_equipment'] }}</div>
                <div class="text-xs lg:text-sm text-primary-600">In Maintenance</div>
            </div>
        </div>
    </div>

    <!-- Monthly Booking Chart -->
    <div class="luxury-card mt-8">
        <h3 class="section-subtitle mb-6">Monthly Booking Trends</h3>
        
        <div class="h-64">
            <canvas id="bookingChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="luxury-card mt-8">
        <h3 class="section-subtitle mb-6">Quick Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('equipment.create') }}" class="btn-primary text-center">
                <i class="fas fa-plus mr-2"></i>
                Add New Equipment
            </a>
            
            <a href="{{ route('bookings.index') }}" class="btn-secondary text-center">
                <i class="fas fa-calendar-plus mr-2"></i>
                New Booking
            </a>
            
            <a href="#" class="btn-secondary text-center">
                <i class="fas fa-chart-bar mr-2"></i>
                View Reports
            </a>
        </div>
    </div>

    <script>
        // Monthly Booking Chart
        const ctx = document.getElementById('bookingChart').getContext('2d');
        const monthlyData = @json($stats['monthly_bookings']);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Bookings',
                    data: monthlyData.map(item => item.count),
                    borderColor: '#c09632',
                    backgroundColor: 'rgba(192, 150, 50, 0.1)',
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
    </script>
</x-admin-layout>
