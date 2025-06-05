<x-admin-layout>
    <x-slot name="title">{{ $equipment->name }}</x-slot>
    <x-slot name="subtitle">Equipment Details and Management</x-slot>

    <!-- Equipment Details Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <div class="luxury-card mb-6">
                <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
                    <h2 class="text-2xl font-serif font-semibold text-primary-800">Equipment Details</h2>
                    <div>
                        @if($equipment->status === 'available')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Available
                            </span>
                        @elseif($equipment->status === 'in_use')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-clock mr-1"></i> In Use
                            </span>
                        @elseif($equipment->status === 'maintenance')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-wrench mr-1"></i> Maintenance
                            </span>
                        @elseif($equipment->status === 'damaged')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Damaged
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Equipment ID:</span>
                        <span class="text-primary-900">#{{ $equipment->id }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Serial Number:</span>
                        <span class="text-primary-900">{{ $equipment->serial_number ?: 'N/A' }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Category:</span>
                        <span class="text-primary-900">{{ $equipment->category->name }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Subcategory:</span>
                        <span class="text-primary-900">{{ $equipment->subcategory->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Daily Rate:</span>
                        <span class="text-primary-900">
                            <x-price :amount="$equipment->daily_rate" />
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Deposit Amount:</span>
                        <span class="text-primary-900">
                            <x-price :amount="$equipment->deposit_amount" />
                        </span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-start py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600 sm:w-1/3">Description:</span>
                        <span class="text-primary-900">{{ $equipment->description ?: 'No description available' }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-start py-3">
                        <span class="font-medium text-primary-600 sm:w-1/3">Condition Notes:</span>
                        <span class="text-primary-900">{{ $equipment->condition_notes ?: 'No condition notes available' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Maintenance History -->
            <div class="luxury-card">
                <h2 class="text-xl font-serif font-semibold text-primary-800 mb-4">Maintenance History</h2>
                
                @if(count($equipment->maintenanceRecords ?? []) > 0)
                    <div class="space-y-4">
                        @foreach($equipment->maintenanceRecords as $record)
                            <div class="bg-primary-50 rounded-lg p-4">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium text-primary-900">{{ $record->title }}</span>
                                    <span class="text-xs text-primary-600">{{ $record->date->format('M d, Y') }}</span>
                                </div>
                                <p class="text-sm text-primary-600 mb-2">{{ $record->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-primary-500">By: {{ $record->user->name }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $record->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-primary-500 py-6">No maintenance records found</p>
                @endif
                
                @can('equipment.maintenance')
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-primary-800 mb-3">Add Maintenance Record</h3>
                        
                        <form action="{{ route('equipment.maintenance', $equipment->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="title" class="label-luxury">Title</label>
                                    <input type="text" name="title" id="title" class="input-luxury" required>
                                </div>
                                
                                <div>
                                    <label for="date" class="label-luxury">Date</label>
                                    <input type="date" name="date" id="date" class="input-luxury" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="label-luxury">Description</label>
                                <textarea name="description" id="description" rows="3" class="input-luxury" required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="status" class="label-luxury">Status</label>
                                <select name="status" id="status" class="input-luxury">
                                    <option value="completed">Completed</option>
                                    <option value="in progress">In Progress</option>
                                    <option value="scheduled">Scheduled</option>
                                </select>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">Add Record</button>
                            </div>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
        
        <!-- Side Panel -->
        <div class="lg:col-span-1">
            <!-- Availability Card -->
            <div class="luxury-card mb-6">
                <h2 class="text-xl font-serif font-semibold text-primary-800 mb-4">Availability</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600">Total Quantity:</span>
                        <span class="text-xl font-bold text-primary-900">{{ $equipment->quantity }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-primary-100">
                        <span class="font-medium text-primary-600">Available:</span>
                        <span class="text-xl font-bold {{ $equipment->quantity_available > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $equipment->quantity_available }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3">
                        <span class="font-medium text-primary-600">In Use:</span>
                        <span class="text-xl font-bold text-blue-600">
                            {{ $equipment->quantity - $equipment->quantity_available }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-6">
                    <div class="bg-primary-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-primary-800 mb-2">Availability Rate</h3>
                        <div class="w-full bg-primary-200 rounded-full h-2.5 mb-2">
                            <div class="bg-accent-600 h-2.5 rounded-full" style="width: {{ ($equipment->quantity_available / $equipment->quantity) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-primary-600">
                            <span>{{ ($equipment->quantity_available / $equipment->quantity) * 100 }}% Available</span>
                            <span>{{ $equipment->quantity }} Total</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="luxury-card">
                <h2 class="text-xl font-serif font-semibold text-primary-800 mb-4">Actions</h2>
                
                <div class="space-y-3">
                    @can('equipment.edit')
                        <a href="{{ route('equipment.edit', $equipment->id) }}" class="btn-primary w-full flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Equipment
                        </a>
                    @endcan
                    
                    @can('booking.create')
                        <a href="{{ route('bookings.create', ['equipment_id' => $equipment->id]) }}" class="btn-secondary w-full flex items-center justify-center">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Create Booking
                        </a>
                    @endcan
                    
                    @can('equipment.maintenance')
                        <button type="button" class="btn-secondary w-full flex items-center justify-center bg-yellow-50 border-yellow-200 text-yellow-700 hover:bg-yellow-100">
                            <i class="fas fa-wrench mr-2"></i>
                            Mark for Maintenance
                        </button>
                    @endcan
                    
                    @can('equipment.delete')
                        <form action="{{ route('equipment.destroy', $equipment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this equipment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary w-full flex items-center justify-center bg-red-50 border-red-200 text-red-700 hover:bg-red-100">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Delete Equipment
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
