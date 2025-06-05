@extends('layouts.admin')

@section('title', $subcategory->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="{{ $subcategory->category->icon ?? 'fas fa-folder' }} fa-2x text-primary"></i>
            </div>
            <div>
                <div class="d-flex align-items-center mb-1">
                    <small class="text-muted me-2">{{ $subcategory->category->name }}</small>
                    <i class="fas fa-chevron-right fa-xs text-muted me-2"></i>
                    <h2 class="h3 mb-0 text-dark font-weight-bold">{{ $subcategory->name }}</h2>
                </div>
                <p class="text-muted mb-0">{{ $subcategory->description ?? 'No description provided' }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            @can('update', $subcategory)
            <a href="{{ route('subcategories.edit', $subcategory) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Subcategory
            </a>
            @endcan
            <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Subcategories
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
                            <h3 class="mb-0">{{ $subcategory->equipment_count ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Total Equipment</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-boxes fa-2x opacity-75"></i>
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
                            <h3 class="mb-0">{{ $subcategory->available_count ?? 0 }}</h3>
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
                            <h3 class="mb-0">{{ $subcategory->rented_count ?? 0 }}</h3>
                            <p class="mb-0 opacity-75">Currently Rented</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
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
                            <h3 class="mb-0">${{ number_format($subcategory->total_revenue ?? 0, 2) }}</h3>
                            <p class="mb-0 opacity-75">Total Revenue</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Equipment List -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-boxes me-2"></i>Equipment in this Subcategory
                    </h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('equipment.create', ['subcategory' => $subcategory->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Equipment
                        </a>
                        <a href="{{ route('equipment.index', ['subcategory' => $subcategory->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($subcategory->equipment && $subcategory->equipment->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Equipment</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Rental Price</th>
                                        <th class="border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subcategory->equipment->take(10) as $equipment)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                @if($equipment->image)
                                                    <img src="{{ asset('storage/' . $equipment->image) }}" 
                                                         alt="{{ $equipment->name }}" 
                                                         class="rounded me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $equipment->name }}</h6>
                                                    <small class="text-muted">{{ $equipment->model ?? 'No model' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $equipment->status == 'available' ? 'success' : ($equipment->status == 'rented' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($equipment->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-bold">${{ number_format($equipment->rental_price, 2) }}</span>
                                            <small class="text-muted">/day</small>
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('equipment.show', $equipment) }}" 
                                                   class="btn btn-outline-info" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('update', $equipment)
                                                <a href="{{ route('equipment.edit', $equipment) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($subcategory->equipment->count() > 10)
                        <div class="card-footer bg-light text-center">
                            <a href="{{ route('equipment.index', ['subcategory' => $subcategory->id]) }}" class="btn btn-outline-primary">
                                View All {{ $subcategory->equipment->count() }} Equipment Items
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-boxes fa-4x text-muted mb-3"></i>
                            <h6 class="text-muted mb-3">No Equipment Found</h6>
                            <p class="text-muted mb-4">This subcategory doesn't have any equipment yet.</p>
                            <a href="{{ route('equipment.create', ['subcategory' => $subcategory->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Equipment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subcategory Details and Actions -->
        <div class="col-md-4">
            <!-- Category Information -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-sitemap me-2"></i>Parent Category
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="{{ $subcategory->category->icon ?? 'fas fa-folder' }} fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $subcategory->category->name }}</h6>
                            <p class="text-muted small mb-0">{{ $subcategory->category->description ?? 'No description' }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('categories.show', $subcategory->category) }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-eye me-2"></i>View Category
                        </a>
                    </div>
                </div>
            </div>

            <!-- Subcategory Details -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-info-circle me-2"></i>Subcategory Details
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold text-muted">Name:</td>
                            <td>{{ $subcategory->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Description:</td>
                            <td>{{ $subcategory->description ?? 'No description' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Created:</td>
                            <td>{{ $subcategory->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Last Updated:</td>
                            <td>{{ $subcategory->updated_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">ID:</td>
                            <td><code>{{ $subcategory->id }}</code></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('equipment.create', ['subcategory' => $subcategory->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Equipment
                        </a>
                        @can('update', $subcategory)
                        <a href="{{ route('subcategories.edit', $subcategory) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Subcategory
                        </a>
                        @endcan
                        <a href="{{ route('equipment.index', ['subcategory' => $subcategory->id]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>View All Equipment
                        </a>
                        <a href="{{ route('subcategories.create', ['category' => $subcategory->category_id]) }}" class="btn btn-outline-info">
                            <i class="fas fa-plus me-2"></i>Add Another Subcategory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
