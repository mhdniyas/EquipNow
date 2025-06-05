<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the subcategories.
     */
    public function index(Request $request)
    {
        // Apply filters and search
        $query = Subcategory::with(['category', 'equipment']);
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $subcategories = $query->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('subcategories.index', compact('subcategories', 'categories'));
    }

    /**
     * Show the form for creating a new subcategory.
     */
    public function create(Category $category)
    {
        // Check if user has permission
        if (!auth()->user()->can('subcategories.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('subcategories.create', compact('category'));
    }

    /**
     * Store a newly created subcategory in storage.
     */
    public function store(Request $request, Category $category)
    {
        // Check if user has permission
        if (!auth()->user()->can('subcategories.create')) {
            abort(403, 'Unauthorized action.');
        }

        // Validate request
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories')->where(function ($query) use ($category) {
                    return $query->where('category_id', $category->id);
                }),
            ],
            'description' => 'nullable|string',
        ]);

        // Add category ID to validated data
        $validated['category_id'] = $category->id;

        // Create subcategory
        $subcategory = Subcategory::create($validated);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Subcategory created successfully.');
    }

    /**
     * Display the specified subcategory.
     */
    public function show(Category $category, Subcategory $subcategory)
    {
        // Ensure subcategory belongs to category
        if ($subcategory->category_id !== $category->id) {
            abort(404);
        }
        
        // Load relationships
        $subcategory->load(['equipment' => function ($query) {
            $query->latest()->take(10);
        }]);
        
        // Get equipment count and distribution
        $equipmentCount = $subcategory->equipment()->count();
        $equipmentByStatus = $subcategory->equipment()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        return view('subcategories.show', compact(
            'category',
            'subcategory', 
            'equipmentCount', 
            'equipmentByStatus'
        ));
    }

    /**
     * Show the form for editing the specified subcategory.
     */
    public function edit(Category $category, Subcategory $subcategory)
    {
        // Check if user has permission
        if (!auth()->user()->can('subcategories.edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Ensure subcategory belongs to category
        if ($subcategory->category_id !== $category->id) {
            abort(404);
        }
        
        // Get all categories for potential reassignment
        $categories = Category::orderBy('name')->get();
        
        return view('subcategories.edit', compact('category', 'subcategory', 'categories'));
    }

    /**
     * Update the specified subcategory in storage.
     */
    public function update(Request $request, Category $category, Subcategory $subcategory)
    {
        // Check if user has permission
        if (!auth()->user()->can('subcategories.edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Ensure subcategory belongs to category
        if ($subcategory->category_id !== $category->id) {
            abort(404);
        }

        // Validate request
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories')->ignore($subcategory->id)->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                }),
            ],
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Update subcategory
        $subcategory->update($validated);
        
        // If category was changed, redirect to new category
        $redirectCategory = $subcategory->category;

        return redirect()->route('categories.show', $redirectCategory)
            ->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified subcategory from storage.
     */
    public function destroy(Category $category, Subcategory $subcategory)
    {
        // Check if user has permission
        if (!auth()->user()->can('subcategories.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Ensure subcategory belongs to category
        if ($subcategory->category_id !== $category->id) {
            abort(404);
        }
        
        // Check if subcategory has equipment
        if ($subcategory->equipment()->exists()) {
            return back()->with('error', 'Cannot delete subcategory with equipment assigned. Please reassign or delete the equipment first.');
        }

        // Delete subcategory
        $subcategory->delete();

        return redirect()->route('categories.show', $category)
            ->with('success', 'Subcategory deleted successfully.');
    }
}
