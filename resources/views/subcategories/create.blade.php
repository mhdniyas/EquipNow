@extends('layouts.admin')

@section('title', 'Create Subcategory')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-dark font-weight-bold">Create Subcategory</h2>
            <p class="text-muted mb-0">Add a new subcategory to organize your equipment more specifically</p>
        </div>
        <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Subcategories
        </a>
    </div>

    <!-- Subcategory Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('subcategories.store') }}" method="POST" id="subcategoryForm">
                        @csrf
                        
                        <!-- Parent Category Selection -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-bold text-dark">
                                Parent Category <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Select a parent category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', request('category')) == $category->id ? 'selected' : '' }}
                                        data-icon="{{ $category->icon ?? 'fas fa-folder' }}">
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Choose the main category that this subcategory belongs to.
                            </small>
                        </div>

                        <!-- Subcategory Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-dark">
                                Subcategory Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Enter subcategory name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-dark">
                                Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter subcategory description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('subcategories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Subcategory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Preview Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-eye me-2"></i>Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center p-3 border rounded" id="subcategoryPreview">
                        <div class="mb-3">
                            <i class="fas fa-folder fa-3x text-muted" id="previewIcon"></i>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted" id="previewCategory">Select a category</small>
                        </div>
                        <h5 id="previewName" class="text-dark mb-2">Subcategory Name</h5>
                        <p id="previewDescription" class="text-muted small mb-0">Subcategory description will appear here...</p>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            This is how your subcategory will appear in the system
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('categories.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Create New Category
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-2"></i>Manage Categories
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-lightbulb me-2"></i>Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Use specific names for better organization
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Add clear descriptions to help staff
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Group similar equipment types together
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Subcategories inherit parent category icons
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const previewCategory = document.getElementById('previewCategory');
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const previewIcon = document.getElementById('previewIcon');

    // Update preview on category change
    categorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            previewCategory.textContent = selectedOption.text;
            const icon = selectedOption.dataset.icon || 'fas fa-folder';
            previewIcon.className = icon + ' fa-3x text-primary';
        } else {
            previewCategory.textContent = 'Select a category';
            previewIcon.className = 'fas fa-folder fa-3x text-muted';
        }
    });

    // Update preview on name change
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Subcategory Name';
    });

    // Update preview on description change
    descriptionInput.addEventListener('input', function() {
        previewDescription.textContent = this.value || 'Subcategory description will appear here...';
    });

    // Initialize preview if category is pre-selected
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
