<x-admin-layout>
    <x-slot name="title">Add New Equipment</x-slot>
    <x-slot name="subtitle">Create a new equipment item in the inventory</x-slot>

    <div class="luxury-card">
        <form action="{{ route('equipment.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Equipment Name -->
                <div>
                    <label for="name" class="label-luxury">Equipment Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="input-luxury" required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="label-luxury">Serial Number</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" class="input-luxury">
                    @error('serial_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category_id" class="label-luxury">Category</label>
                    <select name="category_id" id="category_id" class="input-luxury" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Subcategory -->
                <div>
                    <label for="subcategory_id" class="label-luxury">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="input-luxury">
                        <option value="">Select Subcategory</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily Rate -->
                <div>
                    <label for="daily_rate" class="label-luxury">Daily Rate ($)</label>
                    <input type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate') }}" class="input-luxury" min="0" step="0.01" required>
                    @error('daily_rate')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Deposit Amount -->
                <div>
                    <label for="deposit_amount" class="label-luxury">Deposit Amount ($)</label>
                    <input type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount') }}" class="input-luxury" min="0" step="0.01" required>
                    @error('deposit_amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="label-luxury">Status</label>
                    <select name="status" id="status" class="input-luxury" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quantity -->
                <div>
                    <label for="quantity" class="label-luxury">Total Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" class="input-luxury" min="1" required>
                    @error('quantity')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Quantity Available -->
                <div>
                    <label for="quantity_available" class="label-luxury">Quantity Available</label>
                    <input type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available', 1) }}" class="input-luxury" min="0" required>
                    @error('quantity_available')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="label-luxury">Description</label>
                <textarea name="description" id="description" rows="3" class="input-luxury">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Condition Notes -->
            <div>
                <label for="condition_notes" class="label-luxury">Condition Notes</label>
                <textarea name="condition_notes" id="condition_notes" rows="3" class="input-luxury">{{ old('condition_notes') }}</textarea>
                @error('condition_notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Form Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('equipment.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create Equipment</button>
            </div>
        </form>
    </div>
</x-admin-layout>
