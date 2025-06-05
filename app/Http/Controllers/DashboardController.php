<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index()
    {
        // Calculate overall statistics
        $stats = [
            'total_equipment' => Equipment::count(),
            'total_bookings' => Booking::count(),
            'total_customers' => Customer::count(),
            'total_categories' => Category::count(),
            
            // Equipment status counts
            'available_equipment' => Equipment::where('status', Equipment::STATUS_AVAILABLE)->sum('quantity_available'),
            'in_use_equipment' => Equipment::where('status', Equipment::STATUS_IN_USE)->count(),
            'maintenance_equipment' => Equipment::where('status', Equipment::STATUS_MAINTENANCE)->count(),
            
            // Recent bookings
            'recent_bookings' => Booking::with('customer')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
                
            // Equipment by category
            'equipment_by_category' => Category::withCount('equipment')
                ->orderBy('equipment_count', 'desc')
                ->take(5)
                ->get(),
                
            // Monthly booking trends (for the last 6 months)
            'monthly_bookings' => $this->getMonthlyBookings(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
    
    /**
     * Get monthly booking counts for the last 6 months.
     */
    private function getMonthlyBookings()
    {
        $months = collect([]);
        
        // Get the last 6 months
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'month' => $date->format('M'),
                'count' => 0
            ]);
        }
        
        // Get booking counts by month
        $bookingsByMonth = Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%b") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        // Merge the data
        return $months->map(function ($item) use ($bookingsByMonth) {
            if (isset($bookingsByMonth[$item['month']])) {
                $item['count'] = $bookingsByMonth[$item['month']]->count;
            }
            return $item;
        })->reverse()->values();
    }
}
