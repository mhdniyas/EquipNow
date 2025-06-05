@extends('layouts.admin')

@section('title', $category->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-2x text-primary"></i>
            </div>
            <div>
                <h2 class="h3 mb-0 text-dark font-weight-bold">{{ $category->name }}</h2>
                <p class="text-muted mb-0">{{ $category->description ?? 'No description provided' }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            @can('update', $category)
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Category
            </a>
            @endcan
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $category->subcategories_count ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Subcategories</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-layer-group fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $category->equipment_count ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Equipment Items</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-boxes fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $category->available_equipment_count ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Available</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $category->rented_equipment_count ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Currently Rented</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Subcategories Section -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-layer-group me-2"></i>Subcategories
                    </h6>
                    @can('create', App\Models\Subcategory::class)
                    <a href="{{ route('subcategories.create', ['category' => $category->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Subcategory
                    </a>
                    @endcan
                </div>
                <div class="card-body p-0">
                    @if($category->subcategories && $category->subcategories->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($category->subcategories as $subcategory)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $subcategory->icon ?? 'fas fa-folder' }} me-3 text-primary"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $subcategory->name }}</h6>
                                        @if($subcategory->description)
                                        <small class="text-muted">{{ Str::limit($subcategory->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary">{{ $subcategory->equipment_count ?? 0 }} items</span>
                                    @can('update', $subcategory)
                                    <a href="{{ route('subcategories.edit', $subcategory) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No subcategories found</h6>
                            <p class="text-muted small">Create your first subcategory to better organize your equipment.</p>
                            @can('create', App\Models\Subcategory::class)
                            <a href="{{ route('subcategories.create', ['category' => $category->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Subcategory
                            </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Equipment Section -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-boxes me-2"></i>Recent Equipment
                    </h6>
                    <a href="{{ route('equipment.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($category->equipment && $category->equipment->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($category->equipment->take(5) as $equipment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
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
                                    <div>
                                        <h6 class="mb-0">{{ $equipment->name }}</h6>
                                        <small class="text-muted">{{ $equipment->subcategory->name ?? 'No subcategory' }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $equipment->status == 'available' ? 'success' : ($equipment->status == 'rented' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($equipment->status) }}
                                    </span>
                                    <small class="text-muted">${{ number_format($equipment->rental_price, 2) }}/day</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No equipment found</h6>
                            <p class="text-muted small">Add equipment items to this category to get started.</p>
                            <a href="{{ route('equipment.create', ['category' => $category->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Equipment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Category Details -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-info-circle me-2"></i>Category Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" style="width: 150px;">Name:</td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Description:</td>
                                    <td>{{ $category->description ?? 'No description provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Icon:</td>
                                    <td>
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} me-2"></i>{{ $category->icon }}
                                        @else
                                            <span class="text-muted">No icon selected</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" style="width: 150px;">Created:</td>
                                    <td>{{ $category->created_at->format('M d, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Last Updated:</td>
                                    <td>{{ $category->updated_at->format('M d, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">ID:</td>
                                    <td><code>{{ $category->id }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
