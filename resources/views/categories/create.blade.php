<x-admin-layout title="Create Category">
    <div class="min-h-screen bg-gray-50 p-4 lg:p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Category</h1>
                    <p class="text-gray-600 mt-1">Add a new equipment category to organize your inventory</p>
                </div>
                <a href="{{ route('categories.index') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Categories
                </a>
            </div>
        </div>

        <!-- Category Form -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                    @csrf
                    
                    <!-- Category Name -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2.5 rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter category name"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Description
                        </label>
                        <textarea 
                            class="w-full px-3 py-2.5 rounded-lg border @error('description') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm" 
                            id="description" 
                            name="description" 
                            rows="3" 
                            placeholder="Enter category description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon Selection -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Category Icon
                        </label>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-1.5">
                            @foreach($iconOptions as $value => $label)
                            <div>
                                <input type="radio" name="icon" id="icon_{{ Str::slug($label) }}" value="{{ $value }}" class="hidden peer">
                                <label for="icon_{{ Str::slug($label) }}" 
                                       class="flex items-center justify-center h-12 sm:h-10 w-full bg-white border border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all duration-200">
                                    <i class="{{ $value }} text-lg sm:text-base"></i>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-2 mt-6">
                        <a href="{{ route('categories.index') }}" 
                           class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm text-center">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-sm text-center">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Right Sidebar -->
            <div>
                <!-- Tips Card -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                        <h3 class="text-white font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Tips for Category Creation
                        </h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 bg-blue-100 text-blue-600 p-1 rounded-full mr-3 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Be Specific</h4>
                                    <p class="text-sm text-gray-600">Use clear and specific names that accurately describe the equipment type.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 bg-blue-100 text-blue-600 p-1 rounded-full mr-3 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Be Consistent</h4>
                                    <p class="text-sm text-gray-600">Maintain a consistent naming convention across all categories.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 bg-blue-100 text-blue-600 p-1 rounded-full mr-3 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Add Description</h4>
                                    <p class="text-sm text-gray-600">Include helpful descriptions to provide context for staff members.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 bg-blue-100 text-blue-600 p-1 rounded-full mr-3 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Choose an Icon</h4>
                                    <p class="text-sm text-gray-600">Select an icon that visually represents the category for easier identification.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Preview Card -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Preview
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="border border-gray-200 rounded-xl p-6 text-center">
                            <div class="mb-4">
                                <i class="fas fa-folder text-4xl text-gray-400" id="previewIcon"></i>
                            </div>
                            <h4 id="previewName" class="text-lg font-medium text-gray-900 mb-2">Category Name</h4>
                            <p id="previewDescription" class="text-sm text-gray-500">Category description will appear here...</p>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <span class="text-xs text-gray-500 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                This is how your category will appear in the system
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const iconInputs = document.querySelectorAll('input[name="icon"]');
        const previewName = document.getElementById('previewName');
        const previewDescription = document.getElementById('previewDescription');
        const previewIcon = document.getElementById('previewIcon');

        // Update preview on name change
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Category Name';
        });

        // Update preview on description change
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Category description will appear here...';
        });

        // Update preview on icon change
        iconInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                if (this.checked) {
                    previewIcon.className = this.value + ' text-4xl text-blue-600';
                }
            });
        });

        // Set default icon if one was previously selected
        const selectedIcon = document.querySelector('input[name="icon"]:checked');
        if (selectedIcon) {
            previewIcon.className = selectedIcon.value + ' text-4xl text-blue-600';
        }
    });
    </script>
    @endpush
</x-admin-layout>
