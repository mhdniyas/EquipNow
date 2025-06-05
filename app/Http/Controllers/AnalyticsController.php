<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Get date range
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::now()->subDays(30);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::now();
        
        // Analytics data
        $analytics = [
            'visitor_stats' => [
                'today' => rand(10, 50), // Placeholder for actual visitor tracking
                'yesterday' => rand(10, 50),
                'week' => rand(100, 300),
                'month' => rand(500, 1500),
            ],
            
            'booking_stats' => [
                'total' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
                'active' => Booking::where('status', Booking::STATUS_ACTIVE)->count(),
                'returned' => Booking::where('status', Booking::STATUS_RETURNED)->count(),
                'cancelled' => Booking::where('status', Booking::STATUS_CANCELLED)->count(),
            ],
            
            'equipment_usage' => $this->getEquipmentUsage(),
            'category_distribution' => $this->getCategoryDistribution(),
            'daily_bookings' => $this->getDailyBookings($startDate, $endDate),
            'customer_growth' => $this->getCustomerGrowth(),
            'recent_activities' => $this->getRecentActivities(),
        ];
        
        return view('admin.analytics', compact('analytics', 'startDate', 'endDate'));
    }
    
    /**
     * Get equipment usage statistics.
     */
    private function getEquipmentUsage()
    {
        $totalEquipment = Equipment::sum('quantity');
        $availableCount = Equipment::where('status', Equipment::STATUS_AVAILABLE)->sum('quantity_available');
        $inUseCount = $totalEquipment - $availableCount;
        
        return [
            'total' => $totalEquipment,
            'available' => $availableCount,
            'in_use' => $inUseCount,
            'availability_rate' => $totalEquipment > 0 ? round(($availableCount / $totalEquipment) * 100) : 0,
            'utilization_rate' => $totalEquipment > 0 ? round(($inUseCount / $totalEquipment) * 100) : 0,
        ];
    }
    
    /**
     * Get category distribution data.
     */
    private function getCategoryDistribution()
    {
        return Category::withCount('equipment')
            ->orderBy('equipment_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->equipment_count,
                ];
            });
    }
    
    /**
     * Get daily bookings for date range.
     */
    private function getDailyBookings($startDate, $endDate)
    {
        // Create date range
        $dateRange = collect();
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            $dateRange->push([
                'date' => $currentDate->format('Y-m-d'),
                'label' => $currentDate->format('M d'),
                'count' => 0
            ]);
            
            $currentDate->addDay();
        }
        
        // Get booking counts by date
        $bookingsByDate = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->keyBy('date');
        
        // Merge the data
        return $dateRange->map(function ($item) use ($bookingsByDate) {
            if (isset($bookingsByDate[$item['date']])) {
                $item['count'] = $bookingsByDate[$item['date']]->count;
            }
            return $item;
        })->values();
    }
    
    /**
     * Get customer growth data.
     */
    private function getCustomerGrowth()
    {
        $months = collect();
        
        // Get the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'count' => 0
            ]);
        }
        
        // Get customer counts by month
        $customersByMonth = Customer::select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        // Merge the data
        return $months->map(function ($item) use ($customersByMonth) {
            if (isset($customersByMonth[$item['month']])) {
                $item['count'] = $customersByMonth[$item['month']]->count;
            }
            return $item;
        })->values();
    }
    
    /**
     * Get recent activities.
     */
    private function getRecentActivities()
    {
        // This is a placeholder - in a real application, you'd have an activity log
        return collect([
            [
                'type' => 'booking',
                'message' => 'New booking created by John Smith',
                'time' => Carbon::now()->subHours(2),
            ],
            [
                'type' => 'equipment',
                'message' => 'Equipment "Power Drill XL" marked as maintenance',
                'time' => Carbon::now()->subHours(5),
            ],
            [
                'type' => 'return',
                'message' => 'Booking #1043 items returned',
                'time' => Carbon::now()->subHours(8),
            ],
            [
                'type' => 'customer',
                'message' => 'New customer "Acme Corp" registered',
                'time' => Carbon::now()->subHours(12),
            ],
            [
                'type' => 'maintenance',
                'message' => 'Equipment "Generator 5000W" maintenance completed',
                'time' => Carbon::now()->subDays(1),
            ],
        ]);
    }
}
