<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the welcome/homepage with dynamic data.
     */
    public function index(): View
    {
        // Get statistics for the stats section
        $stats = [
            'equipment_count' => Equipment::count(),
            'category_count' => Category::count(),
            'customer_count' => Customer::count(),
            'booking_count' => Booking::count(),
        ];

        // Get featured categories (limit to 6 for the grid)
        $categories = Category::withCount('equipment')
            ->orderBy('equipment_count', 'desc')
            ->limit(6)
            ->get();

        // Get featured equipment (available equipment with images, limit to 6)
        $featuredEquipment = Equipment::with(['category'])
            ->where('status', Equipment::STATUS_AVAILABLE)
            ->where('quantity_available', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact('stats', 'categories', 'featuredEquipment'));
    }
}
