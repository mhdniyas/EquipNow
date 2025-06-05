<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComboController extends Controller
{
    /**
     * Display a listing of combos.
     */
    public function index(Request $request)
    {
        $query = Combo::with(['items.equipment', 'category']);
        
        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $combos = $query->orderBy('name')->paginate(15);
        $categories = Category::orderBy('name')->get();
        
        // Statistics
        $stats = [
            'total_combos' => Combo::count(),
            'active_combos' => Combo::where('status', 'active')->count(),
            'inactive_combos' => Combo::where('status', 'inactive')->count(),
            'most_popular' => Combo::withCount('bookings')->orderBy('bookings_count', 'desc')->first(),
        ];
        
        return view('combos.index', compact('combos', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new combo.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $equipment = Equipment::where('status', 'available')->orderBy('name')->get();
        
        return view('combos.create', compact('categories', 'equipment'));
    }

    /**
     * Store a newly created combo in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:combos',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'combo_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'items' => 'required|array|min:1',
            'items.*.equipment_id' => 'required|exists:equipment,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.is_free' => 'boolean',
        ]);

        DB::beginTransaction();
        
        try {
            // Create the combo
            $combo = Combo::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'combo_price' => $request->combo_price,
                'status' => $request->status,
            ]);

            // Add combo items
            foreach ($request->items as $item) {
                $combo->items()->create([
                    'equipment_id' => $item['equipment_id'],
                    'quantity' => $item['quantity'],
                    'is_free' => $item['is_free'] ?? false,
                ]);
            }

            DB::commit();
            
            return redirect()->route('combos.show', $combo)
                           ->with('success', 'Combo created successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create combo. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Display the specified combo.
     */
    public function show(Combo $combo)
    {
        $combo->load(['items.equipment', 'category', 'bookings.customer']);
        
        // Calculate total individual price vs combo price savings
        $individualTotal = $combo->items->sum(function ($item) {
            return $item->equipment->daily_rate * $item->quantity;
        });
        $savings = $individualTotal - $combo->combo_price;
        $savingsPercentage = $individualTotal > 0 ? ($savings / $individualTotal) * 100 : 0;
        
        return view('combos.show', compact('combo', 'individualTotal', 'savings', 'savingsPercentage'));
    }

    /**
     * Show the form for editing the specified combo.
     */
    public function edit(Combo $combo)
    {
        $combo->load(['items.equipment']);
        $categories = Category::orderBy('name')->get();
        $equipment = Equipment::where('status', 'available')->orderBy('name')->get();
        
        return view('combos.edit', compact('combo', 'categories', 'equipment'));
    }

    /**
     * Update the specified combo in storage.
     */
    public function update(Request $request, Combo $combo)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:combos,name,' . $combo->id,
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'combo_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'items' => 'required|array|min:1',
            'items.*.equipment_id' => 'required|exists:equipment,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.is_free' => 'boolean',
        ]);

        DB::beginTransaction();
        
        try {
            // Update the combo
            $combo->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'combo_price' => $request->combo_price,
                'status' => $request->status,
            ]);

            // Delete existing items and create new ones
            $combo->items()->delete();
            
            foreach ($request->items as $item) {
                $combo->items()->create([
                    'equipment_id' => $item['equipment_id'],
                    'quantity' => $item['quantity'],
                    'is_free' => $item['is_free'] ?? false,
                ]);
            }

            DB::commit();
            
            return redirect()->route('combos.show', $combo)
                           ->with('success', 'Combo updated successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update combo. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Remove the specified combo from storage.
     */
    public function destroy(Combo $combo)
    {
        // Check if combo is being used in active bookings
        $activeBookings = $combo->bookings()->whereIn('status', ['pending', 'confirmed', 'active'])->count();
        
        if ($activeBookings > 0) {
            return redirect()->route('combos.index')
                           ->with('error', 'Cannot delete combo with active bookings.');
        }
        
        $combo->items()->delete();
        $combo->delete();
        
        return redirect()->route('combos.index')
                       ->with('success', 'Combo deleted successfully!');
    }

    /**
     * Toggle combo status between active and inactive.
     */
    public function toggleStatus(Combo $combo)
    {
        $newStatus = $combo->status === 'active' ? 'inactive' : 'active';
        $combo->update(['status' => $newStatus]);
        
        return redirect()->route('combos.show', $combo)
                       ->with('success', 'Combo status updated to ' . $newStatus . '!');
    }

    /**
     * Get combo items for API/AJAX requests.
     */
    public function getItems(Combo $combo)
    {
        $combo->load(['items.equipment']);
        
        return response()->json([
            'combo' => $combo,
            'items' => $combo->items->map(function ($item) {
                return [
                    'equipment_id' => $item->equipment_id,
                    'equipment_name' => $item->equipment->name,
                    'quantity' => $item->quantity,
                    'daily_rate' => $item->equipment->daily_rate,
                    'is_free' => $item->is_free,
                    'subtotal' => $item->is_free ? 0 : ($item->equipment->daily_rate * $item->quantity),
                ];
            }),
            'combo_price' => $combo->combo_price,
        ]);
    }
}
