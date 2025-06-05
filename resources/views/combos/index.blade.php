<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="section-title mb-2">Combo Management</h1>
                <p class="text-primary-600 text-lg">Manage your equipment combo packages and bundles</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('combos.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Create New Combo
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="luxury-card border-l-4 border-accent-600">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-primary-600 uppercase tracking-wide mb-1">
                            Total Combos
                        </div>
                        <div class="text-3xl font-bold text-primary-900">{{ $stats['total_combos'] }}</div>
                    </div>
                    <div class="w-12 h-12 bg-accent-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-box-open text-accent-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">
                            Active Combos
                        </div>
                        <div class="text-3xl font-bold text-primary-900">{{ $stats['active_combos'] }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">
                            Inactive Combos
                        </div>
                        <div class="text-3xl font-bold text-primary-900">{{ $stats['inactive_combos'] }}</div>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-pause-circle text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="luxury-card border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">
                            Most Popular
                        </div>
                        <div class="text-lg font-bold text-primary-900">
                            {{ $stats['most_popular'] ? Str::limit($stats['most_popular']->name, 15) : 'N/A' }}
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="luxury-card mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-primary-900">Filter Combos</h3>
                <i class="fas fa-filter text-accent-600"></i>
            </div>
            <form method="GET" action="{{ route('combos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="label-luxury">Search</label>
                    <input type="text" class="input-luxury" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by name or description...">
                </div>
                <div>
                    <label for="category_id" class="label-luxury">Category</label>
                    <select class="input-luxury" id="category_id" name="category_id">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="label-luxury">Status</label>
                    <select class="input-luxury" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('combos.index') }}" class="btn-secondary px-4 py-3">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Combos Table -->
        <div class="luxury-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-primary-900">Combo Packages</h3>
                <span class="text-sm text-primary-600">{{ $combos->total() }} total combos</span>
            </div>
            
            @if($combos->count() > 0)
                <div class="overflow-x-auto">
                    <!-- Mobile Card View (visible on small screens) -->
                    <div class="block md:hidden">
                        @foreach($combos as $combo)
                            @php
                                $individualTotal = $combo->items->sum(function($item) {
                                    return $item->equipment->daily_rate * $item->quantity;
                                });
                                $savings = $individualTotal - $combo->combo_price;
                                $savingsPercentage = $individualTotal > 0 ? ($savings / $individualTotal) * 100 : 0;
                            @endphp
                            <div class="luxury-card mb-4 p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-primary-900">{{ $combo->name }}</h3>
                                        @if($combo->description)
                                            <p class="text-xs text-primary-500 mt-1">{{ Str::limit($combo->description, 60) }}</p>
                                        @endif
                                        <p class="text-xs text-primary-600 mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $combo->category->name }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="ml-2">
                                        @if($combo->status === 'active')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <p class="text-xs text-primary-500">Items</p>
                                        <p class="text-sm font-medium text-primary-900">{{ $combo->items->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-primary-500">Combo Price</p>
                                        <p class="text-sm font-medium text-green-600">₹{{ number_format($combo->combo_price, 2) }}</p>
                                    </div>
                                </div>
                                
                                @if($savings > 0)
                                <div class="bg-green-50 text-green-700 text-xs p-2 rounded-lg mb-3">
                                    <div class="flex justify-between">
                                        <span>Individual Price: ₹{{ number_format($individualTotal, 2) }}</span>
                                        <span>Savings: {{ number_format($savingsPercentage, 1) }}% off</span>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('combos.show', $combo) }}" 
                                       class="text-primary-600 hover:text-primary-900 p-1.5 hover:bg-primary-100 rounded transition-all duration-200" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    
                                    <a href="{{ route('combos.edit', $combo) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-100 rounded transition-all duration-200" 
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    
                                    <form action="{{ route('combos.toggle-status', $combo) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-yellow-600 hover:text-yellow-900 p-1.5 hover:bg-yellow-100 rounded transition-all duration-200" 
                                                title="Toggle Status">
                                            <i class="fas fa-power-off text-sm"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('combos.destroy', $combo) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this combo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-1.5 hover:bg-red-100 rounded transition-all duration-200" 
                                                title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View (hidden on small screens) -->
                    <table class="w-full table-auto hidden md:table">
                        <thead>
                            <tr class="bg-primary-50 border-b border-primary-100">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-primary-900">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-primary-900">Category</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-primary-900">Items</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-primary-900">Individual Price</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-primary-900">Combo Price</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-primary-900">Savings</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-primary-900">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-primary-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary-100">
                            @foreach($combos as $combo)
                                @php
                                    $individualTotal = $combo->items->sum(function($item) {
                                        return $item->equipment->daily_rate * $item->quantity;
                                    });
                                    $savings = $individualTotal - $combo->combo_price;
                                    $savingsPercentage = $individualTotal > 0 ? ($savings / $individualTotal) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-primary-25 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-semibold text-primary-900">{{ $combo->name }}</div>
                                            @if($combo->description)
                                                <div class="text-sm text-primary-600 mt-1">{{ Str::limit($combo->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $combo->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                            {{ $combo->items->count() }} items
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-primary-900">₹{{ number_format($individualTotal, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600">₹{{ number_format($combo->combo_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($savings > 0)
                                            <div class="text-green-600 font-semibold">
                                                ₹{{ number_format($savings, 2) }}
                                                <div class="text-xs text-green-500">({{ number_format($savingsPercentage, 1) }}% off)</div>
                                            </div>
                                        @else
                                            <span class="text-primary-400">No savings</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($combo->status === 'active')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('combos.show', $combo) }}" 
                                               class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200" 
                                               title="View Details">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('combos.edit', $combo) }}" 
                                               class="p-2 bg-accent-100 text-accent-600 rounded-lg hover:bg-accent-200 transition-colors duration-200" 
                                               title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form action="{{ route('combos.toggle-status', $combo) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-colors duration-200" 
                                                        title="Toggle Status">
                                                    <i class="fas fa-power-off text-sm"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('combos.destroy', $combo) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this combo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200" 
                                                        title="Delete">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $combos->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-primary-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-primary-900 mb-2">No combos found</h3>
                    <p class="text-primary-600 mb-6">Create your first combo package to get started.</p>
                    <a href="{{ route('combos.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>Create New Combo
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit form on filter change
            document.getElementById('category_id').addEventListener('change', function() {
                this.form.submit();
            });
            
            document.getElementById('status').addEventListener('change', function() {
                this.form.submit();
            });
        </script>
    @endpush
</x-admin-layout>
