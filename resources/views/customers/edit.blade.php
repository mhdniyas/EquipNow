<x-admin-layout title="Edit Customer" subtitle="Update customer information">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Edit Customer Information</h3>
                        <p class="text-sm text-gray-600 mt-1">Update customer details below</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Joined: {{ $customer->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('customers.update', $customer) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Customer Stats -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-lg font-bold text-blue-600">{{ $customer->bookings->count() }}</div>
                            <div class="text-sm text-gray-600">Total Bookings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">{{ $customer->bookings->where('status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-600">Active Bookings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-purple-600">${{ number_format($customer->bookings->sum('total_amount'), 2) }}</div>
                            <div class="text-sm text-gray-600">Total Revenue</div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Personal Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $customer->name) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('name') border-red-500 @enderror"
                                   placeholder="Enter full name"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $customer->email) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('email') border-red-500 @enderror"
                                   placeholder="Enter email address"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $customer->phone) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('phone') border-red-500 @enderror"
                                   placeholder="Enter phone number"
                                   required>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">
                                ID Number
                            </label>
                            <input type="text" 
                                   name="id_number" 
                                   id="id_number" 
                                   value="{{ old('id_number', $customer->id_number) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('id_number') border-red-500 @enderror"
                                   placeholder="Enter ID number">
                            @error('id_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Company Information (Optional)</h4>
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name
                        </label>
                        <input type="text" 
                               name="company" 
                               id="company" 
                               value="{{ old('company', $customer->company) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('company') border-red-500 @enderror"
                               placeholder="Enter company name">
                        @error('company')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address Information -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Address Information</h4>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" 
                                  id="address" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-accent-500 focus:border-accent-500 @error('address') border-red-500 @enderror"
                                  placeholder="Enter full address including street, city, state, and postal code"
                                  required>{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('customers.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Customers
                    </a>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('customers.show', $customer) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                            <i class="fas fa-save mr-2"></i>
                            Update Customer
                        </button>
                        
                        @if($customer->bookings->count() == 0)
                            <button type="button" 
                                    onclick="confirmDelete()"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Bookings -->
        @if($customer->bookings->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($customer->bookings->take(5) as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-accent-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-calendar text-accent-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Booking #{{ $booking->id }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $booking->start_date->format('M j, Y') }} - {{ $booking->end_date->format('M j, Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-gray-900">${{ number_format($booking->total_amount, 2) }}</div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : ($booking->status == 'returned' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($customer->bookings->count() == 0)
        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Delete Customer</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete this customer? This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-red-700">
                            Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</x-admin-layout>
