@extends('layouts.admin')

@section('title', 'Edit Subcategory')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-dark font-weight-bold">Edit Subcategory</h2>
            <p class="text-muted mb-0">Update the details for {{ $subcategory->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('subcategories.show', $subcategory) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Subcategory
            </a>
            <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Subcategories
            </a>
        </div>
    </div>

    <!-- Subcategory Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('subcategories.update', $subcategory) }}" method="POST" id="subcategoryForm">
                        @csrf
                        @method('PUT')
                        
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
                                        {{ old('category_id', $subcategory->category_id) == $category->id ? 'selected' : '' }}
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
                                   value="{{ old('name', $subcategory->name) }}" 
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
                                      placeholder="Enter subcategory description">{{ old('description', $subcategory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this subcategory? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Delete Subcategory
                                </button>
                            </form>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('subcategories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Subcategory
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Preview and Stats -->
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
                            <i class="{{ $subcategory->category->icon ?? 'fas fa-folder' }} fa-3x text-primary" id="previewIcon"></i>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted" id="previewCategory">{{ $subcategory->category->name }}</small>
                        </div>
                        <h5 id="previewName" class="text-dark mb-2">{{ $subcategory->name }}</h5>
                        <p id="previewDescription" class="text-muted small mb-0">{{ $subcategory->description ?? 'No description provided' }}</p>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            This is how your subcategory will appear in the system
                        </small>
                    </div>
                </div>
            </div>

            <!-- Subcategory Stats -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-bar me-2"></i>Subcategory Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary">{{ $subcategory->equipment_count ?? 0 }}</h4>
                                <small class="text-muted">Equipment Items</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1 text-success">{{ $subcategory->available_count ?? 0 }}</h4>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Equipment -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-boxes me-2"></i>Recent Equipment
                    </h6>
                </div>
                <div class="card-body">
                    @if($subcategory->equipment && $subcategory->equipment->count() > 0)
                        @foreach($subcategory->equipment->take(3) as $equipment)
                        <div class="d-flex align-items-center mb-3">
                            @if($equipment->image)
                                <img src="{{ asset('storage/' . $equipment->image) }}" 
                                     alt="{{ $equipment->name }}" 
                                     class="rounded me-3" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-0 small">{{ $equipment->name }}</h6>
                                <small class="text-muted">${{ number_format($equipment->rental_price, 2) }}/day</small>
                            </div>
                            <span class="badge bg-{{ $equipment->status == 'available' ? 'success' : ($equipment->status == 'rented' ? 'warning' : 'danger') }} small">
                                {{ ucfirst($equipment->status) }}
                            </span>
                        </div>
                        @endforeach
                        
                        @if($subcategory->equipment->count() > 3)
                        <div class="text-center">
                            <a href="{{ route('equipment.index', ['subcategory' => $subcategory->id]) }}" class="btn btn-sm btn-outline-primary">
                                View All {{ $subcategory->equipment->count() }} Items
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No equipment in this subcategory yet</p>
                        </div>
                    @endif
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
        previewDescription.textContent = this.value || 'No description provided';
    });
});
</script>
@endpush
@endsection
