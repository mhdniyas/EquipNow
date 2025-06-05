<x-admin-layout title="Add Customer" subtitle="Create a new customer profile">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                <p class="text-sm text-gray-600 mt-1">Enter the customer details below</p>
            </div>

            <form action="{{ route('customers.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
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
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
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
                                   value="{{ old('phone') }}"
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
                                   value="{{ old('id_number') }}"
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
                               value="{{ old('company') }}"
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
                                  required>{{ old('address') }}</textarea>
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
                        <button type="button" 
                                onclick="resetForm()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition ease-in-out duration-150">
                            <i class="fas fa-save mr-2"></i>
                            Create Customer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Preview (Optional Enhancement) -->
    <div class="max-w-4xl mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-accent-100 flex items-center justify-center">
                        <span class="text-accent-600 font-medium" id="preview-initials">--</span>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-gray-900" id="preview-name">Customer Name</div>
                        <div class="text-sm text-gray-500" id="preview-company">Individual</div>
                        <div class="text-sm text-gray-500" id="preview-email">email@example.com</div>
                        <div class="text-sm text-gray-500" id="preview-phone">Phone Number</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const companyInput = document.getElementById('company');
            
            const previewName = document.getElementById('preview-name');
            const previewEmail = document.getElementById('preview-email');
            const previewPhone = document.getElementById('preview-phone');
            const previewCompany = document.getElementById('preview-company');
            const previewInitials = document.getElementById('preview-initials');

            function updatePreview() {
                const name = nameInput.value || 'Customer Name';
                const email = emailInput.value || 'email@example.com';
                const phone = phoneInput.value || 'Phone Number';
                const company = companyInput.value || 'Individual';
                
                previewName.textContent = name;
                previewEmail.textContent = email;
                previewPhone.textContent = phone;
                previewCompany.textContent = company;
                
                // Update initials
                const initials = name.split(' ').map(word => word.charAt(0)).join('').substring(0, 2).toUpperCase() || '--';
                previewInitials.textContent = initials;
            }

            nameInput.addEventListener('input', updatePreview);
            emailInput.addEventListener('input', updatePreview);
            phoneInput.addEventListener('input', updatePreview);
            companyInput.addEventListener('input', updatePreview);

            // Initialize preview
            updatePreview();
        });

        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                document.querySelector('form').reset();
                // Update preview after reset
                setTimeout(() => {
                    document.dispatchEvent(new Event('DOMContentLoaded'));
                }, 100);
            }
        }
    </script>
</x-admin-layout>
