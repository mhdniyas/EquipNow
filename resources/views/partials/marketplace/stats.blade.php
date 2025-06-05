<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-6">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['equipment_count'] ?? '500+' }}</div>
                <div class="text-gray-600">Equipment Items</div>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['category_count'] ?? '50+' }}</div>
                <div class="text-gray-600">Categories</div>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['customer_count'] ?? '1000+' }}</div>
                <div class="text-gray-600">Happy Customers</div>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ isset($stats['booking_count']) ? $stats['booking_count'] . '+' : '24/7' }}</div>
                <div class="text-gray-600">{{ isset($stats['booking_count']) ? 'Successful Bookings' : 'Support' }}</div>
            </div>
        </div>
    </div>
</section>
