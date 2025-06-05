<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the equipment.
     */
    public function index(Request $request)
    {
        // Check if user has permission to view equipment
        if (!auth()->user()->can('equipment.view')) {
            abort(403, 'Unauthorized action.');
        }

        // Apply filters
        $query = \App\Models\Equipment::with(['category', 'subcategory']);
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Fetch equipment
        $equipment = $query->latest()->paginate(10);
        
        // Get counts for stats
        $totalEquipment = \App\Models\Equipment::count();
        $availableEquipment = \App\Models\Equipment::where('status', \App\Models\Equipment::STATUS_AVAILABLE)->sum('quantity_available');
        $inUseEquipment = \App\Models\Equipment::where('status', \App\Models\Equipment::STATUS_IN_USE)->count();
        $maintenanceEquipment = \App\Models\Equipment::where('status', \App\Models\Equipment::STATUS_MAINTENANCE)->count();
        
        return view('equipment.index', compact(
            'equipment', 
            'totalEquipment', 
            'availableEquipment', 
            'inUseEquipment', 
            'maintenanceEquipment'
        ));
    }

    /**
     * Show the form for creating a new equipment.
     */
    public function create()
    {
        // Check if user has permission to create equipment
        if (!auth()->user()->can('equipment.create')) {
            abort(403, 'Unauthorized action.');
        }

        // Get categories and subcategories for the form
        $categories = \App\Models\Category::orderBy('name')->get();
        $subcategories = \App\Models\Subcategory::orderBy('name')->get();
        
        return view('equipment.create', compact('categories', 'subcategories'));
    }

    /**
     * Store a newly created equipment in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to create equipment
        if (!auth()->user()->can('equipment.create')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate and store logic

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment created successfully.');
    }

    /**
     * Display the specified equipment.
     */
    public function show($id)
    {
        // Check if user has permission to view equipment
        if (!auth()->user()->can('equipment.view')) {
            abort(403, 'Unauthorized action.');
        }

        // Find the equipment by ID
        $equipment = \App\Models\Equipment::with(['category', 'subcategory', 'maintenanceRecords.user'])
            ->findOrFail($id);
        
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function edit($id)
    {
        // Check if user has permission to edit equipment
        if (!auth()->user()->can('equipment.edit')) {
            abort(403, 'Unauthorized action.');
        }

        // Logic to fetch specific equipment for editing

        return view('equipment.edit', compact('equipment'));
    }

    /**
     * Update the specified equipment in storage.
     */
    public function update(Request $request, $id)
    {
        // Check if user has permission to edit equipment
        if (!auth()->user()->can('equipment.edit')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate and update logic

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified equipment from storage.
     */
    public function destroy($id)
    {
        // Check if user has permission to delete equipment
        if (!auth()->user()->can('equipment.delete')) {
            abort(403, 'Unauthorized action.');
        }

        // Delete logic

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }

    /**
     * Mark equipment for maintenance.
     */
    public function maintenance(Request $request, $id)
    {
        // Check if user has permission for equipment maintenance
        if (!auth()->user()->can('equipment.maintenance')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|string|in:completed,in progress,scheduled',
        ]);
        
        // Find equipment
        $equipment = \App\Models\Equipment::findOrFail($id);
        
        // Create maintenance record
        $record = new \App\Models\MaintenanceRecord();
        $record->equipment_id = $equipment->id;
        $record->reported_by = auth()->id();
        $record->issue = $validated['title'];
        $record->status = $validated['status'] === 'in progress' ? 'in_progress' : $validated['status'];
        $record->start_date = $validated['date'];
        $record->resolution_notes = $validated['description'];
        $record->save();
        
        // Update equipment status if needed
        if ($validated['status'] === 'in progress') {
            $equipment->status = \App\Models\Equipment::STATUS_MAINTENANCE;
            $equipment->save();
        } else if ($validated['status'] === 'completed' && $equipment->status === \App\Models\Equipment::STATUS_MAINTENANCE) {
            $equipment->status = \App\Models\Equipment::STATUS_AVAILABLE;
            $equipment->save();
        }

        return redirect()->route('equipment.show', $id)
            ->with('success', 'Maintenance record added successfully.');
    }
}
