<section id="equipment" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Featured Equipment</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Check out our most popular and high-quality equipment rentals
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($featuredEquipment) && $featuredEquipment->count() > 0)
                @foreach($featuredEquipment as $equipment)
                <div class="equipment-card card-hover">
                    <div class="relative">
                        @if($equipment->image_path)
                            <img src="{{ asset('storage/' . $equipment->image_path) }}" 
                                 alt="{{ $equipment->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-tools text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 px-2 py-1 rounded-full text-xs font-semibold
                            @if($equipment->quantity_available > 5) bg-green-500 text-white
                            @elseif($equipment->quantity_available > 0) bg-yellow-500 text-white
                            @else bg-red-500 text-white @endif">
                            @if($equipment->quantity_available > 0)
                                @if($equipment->quantity_available <= 2)
                                    {{ $equipment->quantity_available }} Left
                                @else
                                    Available
                                @endif
                            @else
                                Out of Stock
                            @endif
                        </div>
                        
                        <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs">
                            {{ $equipment->category->name }}
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $equipment->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($equipment->description, 80) }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">₹{{ number_format($equipment->daily_rate) }}<span class="text-sm text-gray-500">/day</span></span>
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-1 text-sm text-gray-600">{{ $equipment->rating ?? '4.5' }} ({{ $equipment->review_count ?? rand(5, 25) }} reviews)</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('equipment-page.show', $equipment) }}" 
                               class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-semibold text-center hover:bg-gray-200 transition-colors">
                                View Details
                            </a>
                            @if($equipment->quantity_available > 0)
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
            @else
                <!-- Sample equipment items - fallback when no dynamic data -->
                <div class="equipment-card card-hover">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Power Drill" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            Available
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Professional Power Drill</h3>
                        <p class="text-gray-600 mb-4">High-performance cordless drill perfect for construction work</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">₹150<span class="text-sm text-gray-500">/day</span></span>
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-1 text-sm text-gray-600">4.8 (24 reviews)</span>
                            </div>
                        </div>
                        <button class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                            Rent Now
                        </button>
                    </div>
                </div>

                <div class="equipment-card card-hover">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Laptop" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            Available
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">MacBook Pro 16"</h3>
                        <p class="text-gray-600 mb-4">Latest MacBook Pro with M2 chip for professional work</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">₹800<span class="text-sm text-gray-500">/day</span></span>
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-1 text-sm text-gray-600">4.9 (18 reviews)</span>
                            </div>
                        </div>
                        <button class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                            Rent Now
                        </button>
                    </div>
                </div>

                <div class="equipment-card card-hover">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1502920917128-1aa500764cbd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Camera" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            2 Left
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Canon EOS R5</h3>
                        <p class="text-gray-600 mb-4">Professional mirrorless camera for photography and videography</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">₹1200<span class="text-sm text-gray-500">/day</span></span>
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-1 text-sm text-gray-600">4.7 (31 reviews)</span>
                            </div>
                        </div>
                        <button class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                            Rent Now
                        </button>
                    </div>
                </div>
            @endif
        </div>            <div class="text-center mt-12">
                <a href="{{ route('equipment-page.index') }}" class="btn-primary text-white px-8 py-4 rounded-lg text-lg font-semibold">
                    View All Equipment
                </a>
            </div>
    </div>
</section>
