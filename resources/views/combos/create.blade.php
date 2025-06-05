<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="section-title mb-2">Create New Combo</h1>
                <p class="text-primary-600 text-lg">Create a new equipment combo package</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('combos.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Combos
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <div class="luxury-card">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-semibold text-primary-900">Combo Details</h3>
                <i class="fas fa-box-open text-accent-600 text-xl"></i>
            </div>
                <form action="{{ route('combos.store') }}" method="POST" id="comboForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="label-luxury">Combo Name *</label>
                                <input type="text" class="input-luxury @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="label-luxury">Description</label>
                                <textarea class="input-luxury @error('description') border-red-500 @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="label-luxury">Category *</label>
                                <select class="input-luxury @error('category_id') border-red-500 @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing and Status -->
                        <div class="space-y-6">
                            <div>
                                <label for="combo_price" class="label-luxury">Combo Price (₹) *</label>
                                <input type="number" class="input-luxury @error('combo_price') border-red-500 @enderror" 
                                       id="combo_price" name="combo_price" value="{{ old('combo_price') }}" 
                                       step="0.01" min="0" required>
                                @error('combo_price')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="label-luxury">Status *</label>
                                <select class="input-luxury @error('status') border-red-500 @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price Summary -->
                            <div class="luxury-card bg-primary-50 border border-primary-200">
                                <h4 class="font-semibold text-primary-900 mb-4">Price Summary</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-primary-700">Individual Total:</span>
                                        <span id="individualTotal" class="font-semibold text-primary-900">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-primary-700">Combo Price:</span>
                                        <span id="comboTotalDisplay" class="font-semibold text-primary-900">₹0.00</span>
                                    </div>
                                    <hr class="border-primary-200">
                                    <div class="flex justify-between text-lg">
                                        <span class="font-semibold text-primary-900">Savings:</span>
                                        <span id="savingsAmount" class="font-bold text-green-600">₹0.00</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-sm text-primary-600">Savings: <span id="savingsPercentage">0%</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Items Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Combo Items</h5>
                                <button type="button" class="btn btn-outline-primary" id="addItemBtn">
                                    <i class="fas fa-plus me-2"></i>Add Equipment
                                </button>
                            </div>

                            <div id="itemsContainer">
                                <!-- Items will be added here dynamically -->
                                @if(old('items'))
                                    @foreach(old('items') as $index => $item)
                                        <div class="item-row mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4">
                                                            <select class="form-select equipment-select" name="items[{{ $index }}][equipment_id]" required>
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
                                                        <div class="col-md-2">
                                                            <input type="number" class="form-control quantity-input" 
                                                                   name="items[{{ $index }}][quantity]" 
                                                                   placeholder="Qty" min="1" 
                                                                   value="{{ $item['quantity'] }}" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="items[{{ $index }}][is_free]" 
                                                                       value="1" {{ isset($item['is_free']) && $item['is_free'] ? 'checked' : '' }}>
                                                                <label class="form-check-label">Free Item</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="item-subtotal font-weight-bold">₹0.00</span>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @error('items')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <div id="noItemsMessage" class="text-center py-4" style="display: none;">
                                <i class="fas fa-box-open fa-2x text-gray-300 mb-2"></i>
                                <p class="text-muted">No equipment added yet. Click "Add Equipment" to start building your combo.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('combos.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Combo
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let itemIndex = {{ old('items') ? count(old('items')) : 0 }};

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
                itemRow.className = 'item-row mb-3';
                itemRow.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <select class="form-select equipment-select" name="items[${itemIndex}][equipment_id]" required>
                                        <option value="">Select Equipment</option>
                                        @foreach($equipment as $equip)
                                            <option value="{{ $equip->id }}" data-rate="{{ $equip->daily_rate }}">
                                                {{ $equip->name }} (₹{{ $equip->daily_rate }}/day)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control quantity-input" 
                                           name="items[${itemIndex}][quantity]" 
                                           placeholder="Qty" min="1" value="1" required>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="items[${itemIndex}][is_free]" value="1">
                                        <label class="form-check-label">Free Item</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="item-subtotal font-weight-bold">₹0.00</span>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
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
                    savingsElement.className = 'text-success';
                } else if (savings < 0) {
                    savingsElement.className = 'text-danger';
                } else {
                    savingsElement.className = 'text-muted';
                }
            }

            function toggleNoItemsMessage() {
                const itemRows = document.querySelectorAll('.item-row');
                const noItemsMessage = document.getElementById('noItemsMessage');
                
                if (itemRows.length === 0) {
                    noItemsMessage.style.display = 'block';
                } else {
                    noItemsMessage.style.display = 'none';
                }
            }
        </script>
    @endpush
</x-admin-layout>
