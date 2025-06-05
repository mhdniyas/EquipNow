<x-admin-layout>
    <div class="min-h-screen bg-gray-50 p-4 lg:p-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                    <p class="text-gray-600 mt-1">Manage equipment categories and their subcategories</p>
                </div>
                @can('combo.create')
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Category
                </a>
                @endcan
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Categories</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $categories->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Categories</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $categories->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Equipment</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $categories->sum(function($cat) { return $cat->equipment->count(); }) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
            <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col gap-4">
                <!-- Search and Status Filter -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search categories..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <select name="status" class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'sort']))
                        <a href="{{ route('categories.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors duration-200">
                            Clear
                        </a>
                        @endif
                    </div>
                </div>
                
                <!-- Sort Options (Mobile Optimized) -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 pt-3 border-t border-gray-100">
                    <label for="sort" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="w-5 h-5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                        Sort by:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <select name="sort" id="sort" 
                                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="this.form.submit()">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="equipment_count" {{ request('sort') == 'equipment_count' ? 'selected' : '' }}>Most Equipment</option>
                            <option value="subcategory_count" {{ request('sort') == 'subcategory_count' ? 'selected' : '' }}>Most Subcategories</option>
                        </select>
                        
                        <!-- Mobile Sort Direction Buttons (visible on mobile only) -->
                        <div class="flex sm:hidden gap-1">
                            <button type="button" onclick="toggleSortDirection('asc')" 
                                    class="sort-direction-btn px-3 py-2 text-xs border rounded-lg text-gray-700 hover:bg-gray-100 {{ !str_contains(request('sort', 'name_asc'), 'desc') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-gray-200' }}">
                                <i class="fas fa-sort-up"></i> Asc
                            </button>
                            <button type="button" onclick="toggleSortDirection('desc')" 
                                    class="sort-direction-btn px-3 py-2 text-xs border rounded-lg text-gray-700 hover:bg-gray-100 {{ str_contains(request('sort', 'name_asc'), 'desc') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-gray-200' }}">
                                <i class="fas fa-sort-down"></i> Desc
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- JavaScript for Mobile Sort Direction Toggle -->
            <script>
                function toggleSortDirection(direction) {
                    const sortSelect = document.getElementById('sort');
                    const currentValue = sortSelect.value;
                    
                    // Get the sort field without direction
                    let sortField = currentValue.replace('_asc', '').replace('_desc', '');
                    
                    // Special handling for the default sort options that already have direction built in
                    if (currentValue === 'newest' || currentValue === 'oldest') {
                        sortField = currentValue === 'newest' ? 'created_at' : 'created_at';
                    } else if (currentValue === 'equipment_count' || currentValue === 'subcategory_count') {
                        sortField = currentValue;
                    }
                    
                    // Set the new value with the toggled direction
                    sortSelect.value = sortField + '_' + direction;
                    
                    // Submit the form
                    sortSelect.form.submit();
                }
            </script>
        </div>

        <!-- Categories Grid -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <!-- Mobile Card View (visible on small screens) -->
            <div class="block lg:hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($categories as $category)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                                            <i class="{{ $category->icon ?? 'fas fa-boxes' }} text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-semibold text-gray-900">{{ $category->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $category->equipment->count() }} items</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                @if($category->description)
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $category->description }}</p>
                                @endif

                                <div class="flex justify-between items-center text-sm mt-3 pt-3 border-t border-gray-100">
                                    <div class="space-x-2">
                                        <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 hover:text-indigo-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('category.edit')
                                        <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('category.delete')
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $category->subcategories->count() }} subcategories
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                            @can('category.create')
                            <div class="mt-6">
                                <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    New Category
                                </a>
                            </div>
                            @endcan
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Desktop Grid View (3 columns, hidden on mobile) -->
            <div class="hidden lg:grid lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="{{ $category->icon ?? 'fas fa-boxes' }} text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $category->equipment->count() }} items</p>
                                    </div>
                                </div>
                            </div>

                            @if($category->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $category->description }}</p>
                            @endif

                            @if($category->subcategories->count() > 0)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($category->subcategories->take(3) as $subcategory)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $subcategory->name }}
                                            </span>
                                        @endforeach
                                        @if($category->subcategories->count() > 3)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                +{{ $category->subcategories->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-100">
                                <div class="flex space-x-3">
                                    <a href="{{ route('categories.show', $category) }}" 
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                        <i class="fas fa-eye mr-1.5"></i> View
                                    </a>
                                    @can('category.edit')
                                    <a href="{{ route('categories.edit', $category) }}" 
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                                        <i class="fas fa-edit mr-1.5"></i> Edit
                                    </a>
                                    @endcan
                                    @can('category.delete')
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this category?')"
                                                class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">
                                            <i class="fas fa-trash mr-1.5"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                            @can('category.create')
                            <div class="mt-6">
                                <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    New Category
                                </a>
                            </div>
                            @endcan
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        @if($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
