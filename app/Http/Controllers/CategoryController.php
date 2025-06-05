<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        // Apply filters and search with proper eager loading
        $query = Category::with(['equipment', 'subcategories'])
                        ->withCount(['equipment', 'subcategories']);
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Sort - Handle combined sort parameter (field_direction)
        $sortParam = $request->get('sort', 'name_asc');
        
        // Parse the sort parameter to extract field and direction
        if (str_contains($sortParam, '_desc')) {
            $sort = str_replace('_desc', '', $sortParam);
            $direction = 'desc';
        } else {
            $sort = str_replace('_asc', '', $sortParam);
            $direction = 'asc';
        }
        
        // Apply sorting based on the extracted field and direction
        switch ($sort) {
            case 'name':
                $query->orderBy('name', $direction);
                break;
            case 'equipment_count':
                $query->orderBy('equipment_count', $direction);
                break;
            case 'subcategory_count':
                $query->orderBy('subcategories_count', $direction);
                break;
            case 'newest':
            case 'created_at':
                $query->orderBy('created_at', $direction === 'asc' ? 'desc' : 'asc'); // Invert for "newest" logic
                break;
            case 'oldest':
                $query->orderBy('created_at', $direction);
                break;
            default:
                $query->orderBy('name', 'asc'); // Default sorting
                break;
        }
        
        // Fetch categories with pagination
        $categories = $query->paginate(10)->withQueryString();
        
        // Get counts for stats
        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();
        $categoriesWithEquipment = Category::has('equipment')->count();
        
        return view('categories.index', compact(
            'categories', 
            'totalCategories', 
            'totalSubcategories', 
            'categoriesWithEquipment'
        ));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        // Check authorization manually since we removed middleware
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get icon options
        $iconOptions = $this->getIconOptions();
        
        return view('categories.create', compact('iconOptions'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        // Check authorization manually
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        // Create category
        $category = Category::create($validated);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        // Load relationships
        $category->load(['subcategories', 'equipment' => function ($query) {
            $query->with('subcategory')->latest()->take(5);
        }]);
        
        // Get counts
        $equipmentCount = $category->equipment()->count();
        $subcategoriesCount = $category->subcategories()->count();
        
        // Get equipment status distribution
        $equipmentByStatus = $category->equipment()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        return view('categories.show', compact(
            'category', 
            'equipmentCount', 
            'subcategoriesCount', 
            'equipmentByStatus'
        ));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Check authorization manually
        if (!auth()->user()->can('category.edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get icon options
        $iconOptions = $this->getIconOptions();
        
        return view('categories.edit', compact('category', 'iconOptions'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Check authorization manually
        if (!auth()->user()->can('category.edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validate request
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        // Update category
        $category->update($validated);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check authorization manually
        if (!auth()->user()->can('category.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if category has equipment
        if ($category->equipment()->exists()) {
            return back()->with('error', 'Cannot delete category with equipment assigned. Please reassign or delete the equipment first.');
        }

        // Delete category and its subcategories
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    
    /**
     * Get available icon options for categories.
     */
    private function getIconOptions()
    {
        return [
            'fas fa-tools' => 'Tools',
            'fas fa-hammer' => 'Hammer',
            'fas fa-screwdriver' => 'Screwdriver',
            'fas fa-hard-hat' => 'Hard Hat',
            'fas fa-truck' => 'Truck',
            'fas fa-dolly' => 'Dolly',
            'fas fa-cogs' => 'Cogs/Gears',
            'fas fa-snowplow' => 'Snow Equipment',
            'fas fa-drafting-compass' => 'Measuring Tools',
            'fas fa-paint-roller' => 'Painting Tools',
            'fas fa-ruler-combined' => 'Measuring',
            'fas fa-tractor' => 'Tractor/Farm',
            'fas fa-wrench' => 'Wrench',
            'fas fa-truck-pickup' => 'Pickup Truck',
            'fas fa-tape' => 'Tape Measure',
            'fas fa-home' => 'Home',
            'fas fa-laptop' => 'Electronics',
            'fas fa-camera' => 'Photography',
            'fas fa-chair' => 'Furniture',
            'fas fa-lightbulb' => 'Lighting',
            'fas fa-music' => 'Audio',
            'fas fa-video' => 'Video',
            'fas fa-cube' => 'Generic',
        ];
    }
}
