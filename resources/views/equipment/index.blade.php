<x-admin-layout>
    <x-slot name="title">Equipment Inventory</x-slot>
    <x-slot name="subtitle">Manage your rental equipment inventory</x-slot>

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <div class="luxury-card text-center p-3 md:p-4">
            <div class="text-lg md:text-xl lg:text-2xl font-bold text-primary-900">{{ $totalEquipment ?? 0 }}</div>
            <div class="text-xs md:text-sm text-primary-600">Total Equipment</div>
        </div>
        
        <div class="luxury-card text-center p-3 md:p-4">
            <div class="text-lg md:text-xl lg:text-2xl font-bold text-green-600">{{ $availableEquipment ?? 0 }}</div>
            <div class="text-xs md:text-sm text-primary-600">Available</div>
        </div>
        
        <div class="luxury-card text-center p-3 md:p-4">
            <div class="text-lg md:text-xl lg:text-2xl font-bold text-blue-600">{{ $inUseEquipment ?? 0 }}</div>
            <div class="text-xs md:text-sm text-primary-600">In Use</div>
        </div>
        
        <div class="luxury-card text-center p-3 md:p-4">
            <div class="text-lg md:text-xl lg:text-2xl font-bold text-yellow-600">{{ $maintenanceEquipment ?? 0 }}</div>
            <div class="text-xs md:text-sm text-primary-600">Maintenance</div>
        </div>
    </div>

    <!-- Improved Search & Create Button -->
    <div class="flex flex-col space-y-3 md:flex-row md:justify-between md:items-center md:space-y-0 mb-6">
        <!-- Create Button - Above search on mobile, right side on desktop -->
        <div class="order-1 md:order-2">
            @can('equipment.create')
            <a href="{{ route('equipment.create') }}" class="inline-flex items-center justify-center px-4 md:px-6 py-2.5 md:py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 shadow-sm hover:shadow-md w-full md:w-auto">
                <i class="fas fa-plus mr-2"></i>
                <span>Add New Equipment</span>
            </a>
            @endcan
        </div>
        
        <!-- Search Form - Below create button on mobile, left side on desktop -->
        <div class="order-2 md:order-1 flex-1 md:max-w-md">
            <form method="GET" action="{{ route('equipment.index') }}" class="relative">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search equipment..." 
                       class="input-luxury pl-10 pr-20 w-full">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-primary-400"></i>
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary-600 text-white px-2 md:px-3 py-1.5 rounded-md text-xs md:text-sm hover:bg-primary-700">
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Hidden form for filters -->
    <form id="filter-form" method="GET" action="{{ route('equipment.index') }}" class="hidden">
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>

    <!-- Active Filters -->
    @if(request('search') || request('status') || request('sort', 'latest') != 'latest')
        <div class="mb-6 p-4 bg-primary-50 rounded-lg border border-primary-200">
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-sm text-primary-700 font-medium">Active filters:</span>
                
                @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                        Search: "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 text-primary-600 hover:text-primary-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                
                @if(request('status'))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                
                @if(request('sort', 'latest') != 'latest')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="ml-2 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                
                <a href="{{ route('equipment.index') }}" class="text-xs text-primary-600 hover:text-primary-800 underline ml-2">
                    Clear all filters
                </a>
            </div>
        </div>
    @endif

    <!-- Equipment Table Card -->
    <div class="luxury-card">
        <div class="p-3 md:p-6">
        <!-- Equipment Table -->
        <div class="overflow-x-auto">
            <!-- Mobile Card View (visible on small screens) -->
            <div class="block md:hidden">
                @forelse($equipment ?? [] as $item)
                    <div class="luxury-card mb-4 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-primary-900">{{ $item->name }}</h3>
                                <p class="text-xs text-primary-500">ID: #{{ $item->id }}</p>
                                <p class="text-xs text-primary-600 mt-1">{{ $item->category->name ?? 'N/A' }}</p>
                            </div>
                            <div class="ml-2">
                                @if($item->status === 'available')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @elseif($item->status === 'in_use')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        In Use
                                    </span>
                                @elseif($item->status === 'maintenance')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Maintenance
                                    </span>
                                @elseif($item->status === 'damaged')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Damaged
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <p class="text-xs text-primary-500">Daily Rate</p>
                                <p class="text-sm font-medium text-primary-900">${{ number_format($item->daily_rate, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-primary-500">Quantity</p>
                                <p class="text-sm font-medium text-primary-900">{{ $item->quantity_available }}/{{ $item->quantity }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="w-20 bg-primary-200 rounded-full h-1.5">
                                <div class="bg-accent-600 h-1.5 rounded-full" style="width: {{ ($item->quantity_available / $item->quantity) * 100 }}%"></div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('equipment.show', $item->id) }}" class="text-primary-600 hover:text-primary-900 p-1.5 hover:bg-primary-100 rounded transition-all duration-200" title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                @can('equipment.edit')
                                <a href="{{ route('equipment.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-100 rounded transition-all duration-200" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                @endcan
                                
                                @can('equipment.delete')
                                <form action="{{ route('equipment.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1.5 hover:bg-red-100 rounded transition-all duration-200" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        @if(request('search'))
                            <i class="fas fa-search text-4xl text-primary-300 mb-4"></i>
                            <p class="text-primary-500 mb-2">No equipment found matching "{{ request('search') }}"</p>
                            <a href="{{ route('equipment.index') }}" class="text-sm text-primary-600 hover:text-primary-800 underline">
                                View all equipment
                            </a>
                        @else
                            <i class="fas fa-tools text-4xl text-primary-300 mb-4"></i>
                            <p class="text-primary-500 mb-2">No equipment items found</p>
                            @can('equipment.create')
                            <a href="{{ route('equipment.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 shadow-sm hover:shadow-md mt-2">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Equipment
                            </a>
                            @endcan
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View (hidden on small screens) -->
            <table class="min-w-full divide-y divide-primary-200 hidden md:table">
                <thead class="bg-primary-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">
                            Equipment
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">
                            Daily Rate
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-primary-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-primary-200">
                    @forelse($equipment ?? [] as $item)
                        <tr class="hover:bg-primary-50 transition-colors duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-primary-900">
                                            {{ $item->name }}
                                        </div>
                                        <div class="text-xs text-primary-500">
                                            ID: #{{ $item->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-primary-900">{{ $item->category->name ?? 'N/A' }}</div>
                                @if($item->subcategory)
                                    <div class="text-xs text-primary-500">{{ $item->subcategory->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-primary-900">${{ number_format($item->daily_rate, 2) }}</div>
                                <div class="text-xs text-primary-500">
                                    <span class="text-xs">Deposit: ${{ number_format($item->deposit_amount, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm text-primary-900">{{ $item->quantity_available }}/{{ $item->quantity }}</span>
                                    <div class="w-24 bg-primary-200 rounded-full h-1.5 mt-1">
                                        <div class="bg-accent-600 h-1.5 rounded-full" style="width: {{ ($item->quantity_available / $item->quantity) * 100 }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'available')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @elseif($item->status === 'in_use')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        In Use
                                    </span>
                                @elseif($item->status === 'maintenance')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Maintenance
                                    </span>
                                @elseif($item->status === 'damaged')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Damaged
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('equipment.show', $item->id) }}" class="text-primary-600 hover:text-primary-900 p-1 hover:bg-primary-100 rounded transition-all duration-200" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @can('equipment.edit')
                                    <a href="{{ route('equipment.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-100 rounded transition-all duration-200" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('equipment.delete')
                                    <form action="{{ route('equipment.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-100 rounded transition-all duration-200" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-primary-500">
                                @if(request('search'))
                                    <div class="text-center py-8">
                                        <i class="fas fa-search text-4xl text-primary-300 mb-4"></i>
                                        <p class="text-primary-500 mb-2">No equipment found matching "{{ request('search') }}"</p>
                                        <a href="{{ route('equipment.index') }}" class="text-sm text-primary-600 hover:text-primary-800 underline">
                                            View all equipment
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-tools text-4xl text-primary-300 mb-4"></i>
                                        <p class="text-primary-500 mb-2">No equipment items found</p>
                                        @can('equipment.create')
                                        <a href="{{ route('equipment.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 shadow-sm hover:shadow-md mt-2">
                                            <i class="fas fa-plus mr-2"></i>
                                            Add New Equipment
                                        </a>
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($equipment) && method_exists($equipment, 'links'))
        <div class="mt-4">
            {{ $equipment->links() }}
        </div>
        @endif
        </div>
    </div>
</x-admin-layout>