<section id="categories" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Equipment Categories</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover our wide range of equipment categories for all your project needs
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="group">
                    <div class="category-card card-hover p-8 rounded-2xl text-center group-hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-{{ $category->icon ?? 'tools' }} text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">{{ $category->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $category->description ?? 'Professional ' . strtolower($category->name) . ' equipment for all your needs' }}</p>
                        <div class="text-sm text-blue-600 font-semibold">{{ $category->equipment_count ?? 0 }} Item{{ $category->equipment_count !== 1 ? 's' : '' }} Available</div>
                    </div>
                </a>
                @endforeach
            @else
                <!-- Sample categories - fallback when no dynamic data -->
                <div class="category-card card-hover p-8 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-hammer text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Construction Tools</h3>
                    <p class="text-gray-600 mb-4">Professional construction equipment for all your building needs</p>
                    <div class="text-sm text-blue-600 font-semibold">25+ Items Available</div>
                </div>

                <div class="category-card card-hover p-8 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-seedling text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Garden Equipment</h3>
                    <p class="text-gray-600 mb-4">Landscaping and gardening tools for outdoor projects</p>
                    <div class="text-sm text-green-600 font-semibold">18+ Items Available</div>
                </div>

                <div class="category-card card-hover p-8 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-laptop text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tech Equipment</h3>
                    <p class="text-gray-600 mb-4">Latest technology equipment for events and projects</p>
                    <div class="text-sm text-purple-600 font-semibold">30+ Items Available</div>
                </div>

                <div class="category-card card-hover p-8 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-car text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Automotive</h3>
                    <p class="text-gray-600 mb-4">Professional automotive tools and equipment</p>
                    <div class="text-sm text-orange-600 font-semibold">22+ Items Available</div>
                </div>

                <div class="category-card card-hover p-8 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-camera text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Photography</h3>
                    <p class="text-gray-600 mb-4">Professional camera equipment and lighting gear</p>
                    <div class="text-sm text-yellow-600 font-semibold">15+ Items Available</div>
                </div>

                <div class="category-card card-hover p-8 rounded-2xl text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-music text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Audio Equipment</h3>
                    <p class="text-gray-600 mb-4">Sound systems and audio equipment for events</p>
                    <div class="text-sm text-indigo-600 font-semibold">20+ Items Available</div>
                </div>
            @endif
        </div>
    </div>
</section>
