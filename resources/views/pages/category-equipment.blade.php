@extends('layouts.marketplace')

@section('title', $category->name . ' Equipment - EquipNow')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">{{ $category->name }} Equipment</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                {{ $category->description ?? 'Professional ' . $category->name . ' equipment for rent' }}
            </p>
        </div>
    </section>

    <!-- Equipment Grid -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($equipment->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                                        {{ $item->quantity_available }} Available
                                    @else
                                        Out of Stock
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($item->description, 100) }}</p>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-blue-600">
                                        ₹{{ number_format($item->daily_rate) }}
                                        <span class="text-sm text-gray-500">/day</span>
                                    </span>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="ml-1 text-sm text-gray-600">
                                            {{ $item->rating ?? '4.5' }} ({{ $item->review_count ?? '10' }} reviews)
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('equipment-page.show', $item) }}" 
                                       class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-semibold text-center hover:bg-gray-200 transition-colors">
                                        View Details
                                    </a>
                                    @if($item->quantity_available > 0)
                                        <button class="flex-1 btn-primary text-white py-2 px-4 rounded-lg font-semibold">
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
                <div class="text-center py-16">
                    <i class="fas fa-tools text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Equipment Available</h3>
                    <p class="text-gray-600 mb-6">There are currently no equipment items available in this category.</p>
                    <a href="{{ route('equipment-page.index') }}" class="btn-primary text-white px-6 py-3 rounded-lg">
                        Browse All Equipment
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
