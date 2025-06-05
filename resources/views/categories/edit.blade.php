@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-dark font-weight-bold">Edit Category</h2>
            <p class="text-muted mb-0">Update the details for {{ $category->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Category
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <!-- Category Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('categories.update', $category) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-dark">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   placeholder="Enter category name"
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
                                      placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Icon Selection -->
                        <div class="mb-4">
                            <label for="icon" class="form-label fw-bold text-dark">
                                Category Icon
                            </label>
                            <div class="row g-2" id="iconSelection">
                                <!-- Popular icons for equipment categories -->
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_tools" value="fas fa-tools" 
                                           {{ old('icon', $category->icon) == 'fas fa-tools' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_tools" style="height: 60px;">
                                        <i class="fas fa-tools fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_hammer" value="fas fa-hammer"
                                           {{ old('icon', $category->icon) == 'fas fa-hammer' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_hammer" style="height: 60px;">
                                        <i class="fas fa-hammer fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_car" value="fas fa-car"
                                           {{ old('icon', $category->icon) == 'fas fa-car' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_car" style="height: 60px;">
                                        <i class="fas fa-car fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_cogs" value="fas fa-cogs"
                                           {{ old('icon', $category->icon) == 'fas fa-cogs' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_cogs" style="height: 60px;">
                                        <i class="fas fa-cogs fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_wrench" value="fas fa-wrench"
                                           {{ old('icon', $category->icon) == 'fas fa-wrench' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_wrench" style="height: 60px;">
                                        <i class="fas fa-wrench fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_truck" value="fas fa-truck"
                                           {{ old('icon', $category->icon) == 'fas fa-truck' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_truck" style="height: 60px;">
                                        <i class="fas fa-truck fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_laptop" value="fas fa-laptop"
                                           {{ old('icon', $category->icon) == 'fas fa-laptop' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_laptop" style="height: 60px;">
                                        <i class="fas fa-laptop fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_camera" value="fas fa-camera"
                                           {{ old('icon', $category->icon) == 'fas fa-camera' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_camera" style="height: 60px;">
                                        <i class="fas fa-camera fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_music" value="fas fa-music"
                                           {{ old('icon', $category->icon) == 'fas fa-music' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_music" style="height: 60px;">
                                        <i class="fas fa-music fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_home" value="fas fa-home"
                                           {{ old('icon', $category->icon) == 'fas fa-home' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_home" style="height: 60px;">
                                        <i class="fas fa-home fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_paint" value="fas fa-paint-brush"
                                           {{ old('icon', $category->icon) == 'fas fa-paint-brush' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_paint" style="height: 60px;">
                                        <i class="fas fa-paint-brush fa-lg"></i>
                                    </label>
                                </div>
                                <div class="col-2 col-md-1">
                                    <input type="radio" class="btn-check" name="icon" id="icon_leaf" value="fas fa-leaf"
                                           {{ old('icon', $category->icon) == 'fas fa-leaf' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center" for="icon_leaf" style="height: 60px;">
                                        <i class="fas fa-leaf fa-lg"></i>
                                    </label>
                                </div>
                            </div>
                            @error('icon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Delete Category
                                </button>
                            </form>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Category
                                </button>
                            </div>
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
                    <div class="text-center p-3 border rounded" id="categoryPreview">
                        <div class="mb-3">
                            <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-3x text-primary" id="previewIcon"></i>
                        </div>
                        <h5 id="previewName" class="text-dark mb-2">{{ $category->name }}</h5>
                        <p id="previewDescription" class="text-muted small mb-0">{{ $category->description ?? 'No description provided' }}</p>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            This is how your category will appear in the system
                        </small>
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-bar me-2"></i>Category Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary">{{ $category->subcategories_count ?? 0 }}</h4>
                                <small class="text-muted">Subcategories</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1 text-success">{{ $category->equipment_count ?? 0 }}</h4>
                            <small class="text-muted">Equipment Items</small>
                        </div>
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
        previewDescription.textContent = this.value || 'No description provided';
    });

    // Update preview on icon change
    iconInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            if (this.checked) {
                previewIcon.className = this.value + ' fa-3x text-primary';
            }
        });
    });
});
</script>
@endpush
@endsection
