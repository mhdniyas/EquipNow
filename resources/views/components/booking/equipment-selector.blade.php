@props(['equipment', 'categories'])

<div id="step-2-content" class="bg-white rounded-xl shadow-sm border border-gray-100 hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Equipment Selection</h3>
        <p class="text-sm text-gray-600 mt-1">Choose equipment for this booking</p>
    </div>
    
    <div class="p-4 sm:p-6">
        <!-- Equipment Search and Filter -->
        <div class="mb-6 space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="equipment-search"
                               placeholder="Search equipment..."
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="sm:w-48">
                    <select id="category-filter" 
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $categoryName => $items)
                            <option value="{{ $categoryName }}">{{ $categoryName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Equipment Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6" id="equipment-grid">
            @foreach($equipment as $item)
                <div class="equipment-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                     data-equipment-id="{{ $item->id }}"
                     data-equipment-name="{{ $item->name }}"
                     data-equipment-category="{{ $item->category->name ?? 'Uncategorized' }}"
                     onclick="toggleEquipmentSelection({{ $item->id }}, '{{ $item->name }}', {{ $item->daily_rate }})">
                    
                    <!-- Equipment Image -->
                    <div class="aspect-square mb-3 bg-gray-100 rounded-lg overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" 
                                 alt="{{ $item->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-tools text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Equipment Info -->
                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900 text-sm line-clamp-2">{{ $item->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $item->category->name ?? 'Uncategorized' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-indigo-600">₹{{ number_format($item->daily_rate) }}/day</span>
                            <span class="text-xs text-gray-500">Available: {{ $item->quantity_available ?? 1 }}</span>
                        </div>
                    </div>
                    
                    <!-- Selection Controls -->
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="equipment-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   id="equipment-{{ $item->id }}">
                            <label for="equipment-{{ $item->id }}" class="ml-2 text-sm text-gray-700">Select</label>
                        </div>
                        
                        <div class="quantity-selector hidden">
                            <select name="quantities[]" 
                                    class="quantity-input text-xs border border-gray-300 rounded px-2 py-1 w-16 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @for($i = 1; $i <= min(10, $item->quantity_available ?? 1); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Selected Equipment Summary -->
        <div id="selected-equipment-summary" class="hidden mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Selected Equipment</h4>
            <div id="selected-equipment-list" class="space-y-2">
                <!-- Selected items will be populated here -->
            </div>
            <div class="mt-3 pt-3 border-t border-gray-300">
                <div class="flex justify-between text-sm font-medium text-gray-900">
                    <span>Total Equipment Cost:</span>
                    <span id="equipment-total">₹0</span>
                </div>
            </div>
        </div>
    </div>
</div>
