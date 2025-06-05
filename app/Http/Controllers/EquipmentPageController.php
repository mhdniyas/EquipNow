<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentPageController extends Controller
{
    /**
     * Display the equipment page with search and filters.
     */
    public function index(Request $request): View
    {
        $query = Equipment::with(['category'])
            ->where('status', Equipment::STATUS_AVAILABLE);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $categoryId = $request->get('category');
            $query->where('category_id', $categoryId);
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('daily_rate', '>=', $request->get('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('daily_rate', '<=', $request->get('price_max'));
        }

        // Availability filter
        if ($request->filled('availability')) {
            $availability = $request->get('availability');
            if ($availability === 'available') {
                $query->where('quantity_available', '>', 0);
            } elseif ($availability === 'low_stock') {
                $query->where('quantity_available', '>', 0)
                      ->where('quantity_available', '<=', 5);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('daily_rate', 'asc');
                break;
            case 'price_high':
                $query->orderBy('daily_rate', 'desc');
                break;
            case 'popular':
                $query->orderBy('total_rentals', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $equipment = $query->paginate(12)->withQueryString();
        
        // Get categories for filter dropdown
        $categories = Category::withCount(['equipment' => function ($q) {
                $q->where('status', Equipment::STATUS_AVAILABLE);
            }])
            ->having('equipment_count', '>', 0)
            ->orderBy('name')
            ->get();

        // Get filter statistics
        $filterStats = [
            'total_equipment' => Equipment::where('status', Equipment::STATUS_AVAILABLE)->count(),
            'available_equipment' => Equipment::where('status', Equipment::STATUS_AVAILABLE)
                                            ->where('quantity_available', '>', 0)->count(),
            'categories_count' => $categories->count(),
        ];

        return view('pages.equipment', compact('equipment', 'categories', 'filterStats'));
    }

    /**
     * Display a specific equipment item.
     */
    public function show(Equipment $equipment): View
    {
        // Get related equipment from the same category
        $relatedEquipment = Equipment::with(['category'])
            ->where('category_id', $equipment->category_id)
            ->where('id', '!=', $equipment->id)
            ->where('status', Equipment::STATUS_AVAILABLE)
            ->limit(3)
            ->get();

        return view('pages.equipment-detail', compact('equipment', 'relatedEquipment'));
    }
}
