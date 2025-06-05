<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    /**
     * Display the categories page.
     */
    public function index(): View
    {
        // Get all categories with equipment count and available equipment count
        $categories = Category::withCount(['equipment' => function ($query) {
                $query->where('status', Equipment::STATUS_AVAILABLE)
                      ->where('quantity_available', '>', 0);
            }])
            ->having('equipment_count', '>', 0)
            ->orderBy('equipment_count', 'desc')
            ->get();

        return view('pages.categories', compact('categories'));
    }

    /**
     * Display equipment for a specific category.
     */
    public function show(Category $category): View
    {
        // Get equipment for this category
        $equipment = Equipment::with(['category'])
            ->where('category_id', $category->id)
            ->where('status', Equipment::STATUS_AVAILABLE)
            ->where('quantity_available', '>', 0)
            ->paginate(12);

        return view('pages.category-equipment', compact('category', 'equipment'));
    }
}
