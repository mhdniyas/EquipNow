@extends('layouts.marketplace')

@section('title', 'Equipment Rentals - EquipNow')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">Equipment Rentals</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Browse our extensive collection of professional-grade equipment for rent
            </p>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="py-8 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('equipment-page.index') }}" class="space-y-4">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <!-- Search Input -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search equipment..." 
                                   class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4">
                        <!-- Category Filter -->
                        <select name="category" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->equipment_count }})
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Price Range -->
                        <div class="flex gap-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}" 
                                   placeholder="Min ₹" 
                                   class="w-24 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <input type="number" name="price_max" value="{{ request('price_max') }}" 
                                   placeholder="Max ₹" 
                                   class="w-24 px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Availability Filter -->
                        <select name="availability" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Items</option>
                            <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Only</option>
                            <option value="low_stock" {{ request('availability') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        </select>
                        
                        <!-- Sort -->
                        <select name="sort" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                        
                        <!-- Submit and Reset -->
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        
                        @if(request()->hasAny(['search', 'category', 'price_min', 'price_max', 'availability', 'sort']))
                            <a href="{{ route('equipment-page.index') }}" 
                               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Filter Stats -->
                @if(isset($filterStats))
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <span>{{ $equipment->total() }} items found</span>
                        <span>•</span>
                        <span>{{ $filterStats['available_equipment'] }} available</span>
                        <span>•</span>
                        <span>{{ $filterStats['categories_count'] }} categories</span>
                    </div>
                @endif
            </form>
        </div>
    </section>

    <!-- Equipment Grid -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($equipment->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($equipment as $item)
                        <div class="equipment-card card-hover">
                            <div class="relative">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" 
                                         alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-tools text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 right-4 px-2 py-1 rounded-full text-xs font-semibold
                                    @if($item->quantity_available > 5) bg-green-500 text-white
                                    @elseif($item->quantity_available > 0) bg-yellow-500 text-white
                                    @else bg-red-500 text-white @endif">
                                    @if($item->quantity_available > 0)
                                        @if($item->quantity_available <= 2)
                                            {{ $item->quantity_available }} Left
                                        @else
                                            Available
                                        @endif
                                    @else
                                        Out of Stock
                                    @endif
                                </div>
                                
                                <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs">
                                    {{ $item->category->name }}
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($item->description, 80) }}</p>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-blue-600">
                                        ₹{{ number_format($item->daily_rate) }}
                                        <span class="text-sm text-gray-500">/day</span>
                                    </span>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="ml-1 text-sm text-gray-600">
                                            {{ $item->rating ?? '4.5' }} ({{ $item->review_count ?? rand(5, 25) }} reviews)
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('equipment-page.show', $item) }}" 
                                       class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-semibold text-center hover:bg-gray-200 transition-colors">
                                        View Details
                                    </a>
                                    @if($item->quantity_available > 0)
                                        <button class="flex-1 btn-primary text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                            Rent Now
                                        </button>
                                    @else
                                        <button class="flex-1 bg-gray-300 text-gray-500 py-2 px-4 rounded-lg font-semibold cursor-not-allowed" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $equipment->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Equipment Found</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request()->hasAny(['search', 'category', 'price_min', 'price_max', 'availability']))
                                Try adjusting your search criteria or clearing the filters.
                            @else
                                There are currently no equipment items available.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'category', 'price_min', 'price_max', 'availability']))
                            <a href="{{ route('equipment-page.index') }}" 
                               class="btn-primary text-white px-6 py-3 rounded-lg">
                                Clear All Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
