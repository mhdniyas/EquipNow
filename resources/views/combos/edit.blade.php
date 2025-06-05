<x-admin-layout>
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Combo: {{ $combo->name }}</h1>
                <p class="text-gray-600">Update combo package details and equipment items</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('combos.show', $combo) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Details
                </a>
                <a href="{{ route('combos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-list mr-2"></i>All Combos
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Update Combo Details</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('combos.update', $combo) }}" method="POST" id="comboForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Combo Name *</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name', $combo->name) }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $combo->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('category_id') border-red-500 @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $combo->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing and Status -->
                        <div class="space-y-6">
                            <div>
                                <label for="combo_price" class="block text-sm font-medium text-gray-700 mb-2">Combo Price (₹) *</label>
                                <input type="number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('combo_price') border-red-500 @enderror" 
                                       id="combo_price" name="combo_price" value="{{ old('combo_price', $combo->combo_price) }}" 
                                       step="0.01" min="0" required>
                                @error('combo_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('status') border-red-500 @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status', $combo->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $combo->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price Summary -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Price Summary</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Individual Total:</span>
                                        <span id="individualTotal" class="font-semibold text-gray-900">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Combo Price:</span>
                                        <span id="comboTotalDisplay" class="font-semibold text-green-600">₹{{ number_format($combo->combo_price, 2) }}</span>
                                    </div>
                                    <hr class="border-green-200">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-gray-900">Savings:</span>
                                        <span id="savingsAmount" class="font-semibold text-green-600">₹0.00</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Savings: <span id="savingsPercentage" class="ml-1">0%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Items Section -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">Combo Items</h3>
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors" id="addItemBtn">
                                <i class="fas fa-plus mr-2"></i>Add Equipment
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            <!-- Existing items -->
                            @if(old('items'))
                                @foreach(old('items') as $index => $item)
                                    <div class="item-row">
                                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                            <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 items-center">
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
                                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 equipment-select" name="items[{{ $index }}][equipment_id]" required>
                                                        <option value="">Select Equipment</option>
                                                        @foreach($equipment as $equip)
                                                            <option value="{{ $equip->id }}" 
                                                                data-rate="{{ $equip->daily_rate }}"
                                                                {{ $item['equipment_id'] == $equip->id ? 'selected' : '' }}>
                                                                {{ $equip->name }} (₹{{ $equip->daily_rate }}/day)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 quantity-input" 
                                                           name="items[{{ $index }}][quantity]" 
                                                           placeholder="Qty" min="1" 
                                                           value="{{ $item['quantity'] }}" required>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex items-center h-5">
                                                        <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" type="checkbox" 
                                                               name="items[{{ $index }}][is_free]" 
                                                               value="1" {{ isset($item['is_free']) && $item['is_free'] ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="ml-2 text-sm">
                                                        <label class="text-gray-700">Free Item</label>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                                    <span class="item-subtotal text-lg font-semibold text-gray-900">₹0.00</span>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" class="inline-flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach($combo->items as $index => $item)
                                    <div class="item-row">
                                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                            <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 items-center">
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
                                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 equipment-select" name="items[{{ $index }}][equipment_id]" required>
                                                        <option value="">Select Equipment</option>
                                                        @foreach($equipment as $equip)
                                                            <option value="{{ $equip->id }}" 
                                                                data-rate="{{ $equip->daily_rate }}"
                                                                {{ $item->equipment_id == $equip->id ? 'selected' : '' }}>
                                                                {{ $equip->name }} (₹{{ $equip->daily_rate }}/day)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 quantity-input" 
                                                           name="items[{{ $index }}][quantity]" 
                                                           placeholder="Qty" min="1" 
                                                           value="{{ $item->quantity }}" required>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex items-center h-5">
                                                        <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" type="checkbox" 
                                                               name="items[{{ $index }}][is_free]" 
                                                               value="1" {{ $item->is_free ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="ml-2 text-sm">
                                                        <label class="text-gray-700">Free Item</label>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                                    <span class="item-subtotal text-lg font-semibold text-gray-900">
                                                        ₹{{ $item->is_free ? '0.00' : number_format($item->equipment->daily_rate * $item->quantity, 2) }}
                                                    </span>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" class="inline-flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        @error('items')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div id="noItemsMessage" class="text-center py-12 hidden">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-box-open text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">No equipment added yet. Click "Add Equipment" to start building your combo.</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('combos.show', $combo) }}" class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Combo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let itemIndex = {{ old('items') ? count(old('items')) : $combo->items->count() }};

            // Equipment data for quick access
            const equipmentData = {
                @foreach($equipment as $equip)
                    {{ $equip->id }}: {
                        name: "{{ $equip->name }}",
                        rate: {{ $equip->daily_rate }}
                    },
                @endforeach
            };

            document.addEventListener('DOMContentLoaded', function() {
                const addItemBtn = document.getElementById('addItemBtn');
                const itemsContainer = document.getElementById('itemsContainer');
                const noItemsMessage = document.getElementById('noItemsMessage');

                // Add item button click
                addItemBtn.addEventListener('click', function() {
                    addItemRow();
                });

                // Remove item handler
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                        e.target.closest('.item-row').remove();
                        updatePriceSummary();
                        toggleNoItemsMessage();
                    }
                });

                // Update calculations when inputs change
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('equipment-select') || 
                        e.target.classList.contains('quantity-input') || 
                        e.target.type === 'checkbox') {
                        updateItemSubtotal(e.target.closest('.item-row'));
                        updatePriceSummary();
                    }
                });

                // Update combo price display
                document.getElementById('combo_price').addEventListener('input', updatePriceSummary);

                // Initial setup
                updatePriceSummary();
                toggleNoItemsMessage();
            });

            function addItemRow() {
                const itemsContainer = document.getElementById('itemsContainer');
                
                const itemRow = document.createElement('div');
                itemRow.className = 'item-row';
                itemRow.innerHTML = `
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 items-center">
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 equipment-select" name="items[${itemIndex}][equipment_id]" required>
                                    <option value="">Select Equipment</option>
                                    @foreach($equipment as $equip)
                                        <option value="{{ $equip->id }}" data-rate="{{ $equip->daily_rate }}">
                                            {{ $equip->name }} (₹{{ $equip->daily_rate }}/day)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 quantity-input" 
                                       name="items[${itemIndex}][quantity]" 
                                       placeholder="Qty" min="1" value="1" required>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center h-5">
                                    <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" type="checkbox" 
                                           name="items[${itemIndex}][is_free]" value="1">
                                </div>
                                <div class="ml-2 text-sm">
                                    <label class="text-gray-700">Free Item</label>
                                </div>
                            </div>
                            <div class="text-center">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                <span class="item-subtotal text-lg font-semibold text-gray-900">₹0.00</span>
                            </div>
                            <div class="text-center">
                                <button type="button" class="inline-flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                itemsContainer.appendChild(itemRow);
                itemIndex++;
                toggleNoItemsMessage();
            }

            function updateItemSubtotal(itemRow) {
                const equipmentSelect = itemRow.querySelector('.equipment-select');
                const quantityInput = itemRow.querySelector('.quantity-input');
                const isFreeCheckbox = itemRow.querySelector('input[type="checkbox"]');
                const subtotalSpan = itemRow.querySelector('.item-subtotal');

                if (equipmentSelect.value && quantityInput.value) {
                    const rate = parseFloat(equipmentSelect.options[equipmentSelect.selectedIndex].dataset.rate) || 0;
                    const quantity = parseInt(quantityInput.value) || 0;
                    const isFree = isFreeCheckbox.checked;
                    
                    const subtotal = isFree ? 0 : (rate * quantity);
                    subtotalSpan.textContent = `₹${subtotal.toFixed(2)}`;
                } else {
                    subtotalSpan.textContent = '₹0.00';
                }
            }

            function updatePriceSummary() {
                let individualTotal = 0;
                
                // Calculate individual total
                document.querySelectorAll('.item-row').forEach(function(row) {
                    const equipmentSelect = row.querySelector('.equipment-select');
                    const quantityInput = row.querySelector('.quantity-input');
                    
                    if (equipmentSelect.value && quantityInput.value) {
                        const rate = parseFloat(equipmentSelect.options[equipmentSelect.selectedIndex].dataset.rate) || 0;
                        const quantity = parseInt(quantityInput.value) || 0;
                        individualTotal += rate * quantity;
                    }
                });

                const comboPrice = parseFloat(document.getElementById('combo_price').value) || 0;
                const savings = individualTotal - comboPrice;
                const savingsPercentage = individualTotal > 0 ? (savings / individualTotal) * 100 : 0;

                // Update display
                document.getElementById('individualTotal').textContent = `₹${individualTotal.toFixed(2)}`;
                document.getElementById('comboTotalDisplay').textContent = `₹${comboPrice.toFixed(2)}`;
                document.getElementById('savingsAmount').textContent = `₹${Math.max(0, savings).toFixed(2)}`;
                document.getElementById('savingsPercentage').textContent = `${Math.max(0, savingsPercentage).toFixed(1)}%`;
                
                // Update color based on savings
                const savingsElement = document.getElementById('savingsAmount');
                if (savings > 0) {
                    savingsElement.className = 'font-semibold text-green-600';
                } else if (savings < 0) {
                    savingsElement.className = 'font-semibold text-red-600';
                } else {
                    savingsElement.className = 'font-semibold text-gray-600';
                }
            }

            function toggleNoItemsMessage() {
                const itemRows = document.querySelectorAll('.item-row');
                const noItemsMessage = document.getElementById('noItemsMessage');
                
                if (itemRows.length === 0) {
                    noItemsMessage.classList.remove('hidden');
                } else {
                    noItemsMessage.classList.add('hidden');
                }
            }
        </script>
    @endpush
</x-admin-layout>
