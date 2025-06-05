<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'user', 'equipment']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        
        // Search by customer name or booking ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics for dashboard cards
        $stats = [
            'total_bookings' => Booking::count(),
            'active_bookings' => Booking::where('status', 'active')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'due_today' => Booking::dueToday()->count(),
            'revenue_this_month' => Booking::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->sum('total_rent'),
            'new_this_month' => Booking::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
        ];
        
        return view('bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $equipment = Equipment::where('status', 'available')->orderBy('name')->get();
        
        // Pre-select customer if provided
        $selectedCustomer = null;
        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
        }
        
        return view('bookings.create', compact('customers', 'equipment', 'selectedCustomer'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'equipment' => 'required|array|min:1',
            'equipment.*' => 'exists:equipment,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'total_rent' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            // Prepare equipment data
            $equipmentData = [];
            
            if (!is_array($request->equipment) || !is_array($request->quantities)) {
                throw new \Exception("Equipment and quantities must be arrays");
            }
            
            if (count($request->equipment) !== count($request->quantities)) {
                throw new \Exception("Equipment and quantities arrays must have the same length");
            }
            
            // Fix N+1 Query: Bulk fetch equipment instead of individual queries
            $equipmentIds = array_filter($request->equipment);
            if (empty($equipmentIds)) {
                throw new \Exception("At least one equipment item must be selected");
            }
            
            $equipmentItems = Equipment::whereIn('id', $equipmentIds)
                ->where('status', Equipment::STATUS_AVAILABLE)
                ->lockForUpdate() // Prevent race conditions
                ->get()
                ->keyBy('id');
            
            foreach ($request->equipment as $index => $equipmentId) {
                if (empty($equipmentId)) {
                    continue; // Skip empty equipment selections
                }
                
                if (!isset($request->quantities[$index])) {
                    throw new \Exception("Quantity is missing for equipment #".($index+1));
                }
                
                $equipment = $equipmentItems->get($equipmentId);
                if (!$equipment) {
                    throw new \Exception("Equipment with ID {$equipmentId} is not available for booking");
                }
                
                $quantity = (int) $request->quantities[$index];
                
                if ($quantity <= 0) {
                    throw new \Exception("Invalid quantity for equipment '{$equipment->name}'");
                }
                
                // Validate quantity (if equipment has quantity tracking)
                if (isset($equipment->quantity_available) && $equipment->quantity_available < $quantity) {
                    throw new \Exception("Only {$equipment->quantity_available} units of '{$equipment->name}' are available");
                }
                
                $equipmentData[$equipmentId] = [
                    'quantity' => $quantity,
                    'daily_rate' => $equipment->daily_rate,
                    'is_free' => false,
                ];
            }
            
            if (empty($equipmentData)) {
                throw new \Exception("At least one equipment item must be selected");
            }

            // Create the booking
            $booking = Booking::create([
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'booking_date' => now(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_rent' => $request->total_rent,
                'deposit_amount' => $request->deposit_amount ?? 0,
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Attach equipment to booking
            $booking->equipment()->attach($equipmentData);

            DB::commit();
            
            return redirect()->route('bookings.show', $booking)
                           ->with('success', 'Booking created successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            
            return back()->withErrors(['error' => 'Failed to create booking: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load(['customer', 'user', 'equipment', 'payments']);
        
        // Calculate days and totals
        $days = $booking->start_date->diffInDays($booking->end_date) + 1;
        $equipmentTotal = $booking->equipment->sum(function ($equipment) use ($days) {
            return $equipment->pivot->quantity * $equipment->pivot->daily_rate * $days;
        });
        
        return view('bookings.show', compact('booking', 'days', 'equipmentTotal'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        // Load relationships
        $booking->load(['customer', 'equipment']);
        
        $customers = Customer::orderBy('name')->get();
        $equipment_list = Equipment::where('status', 'available')->orderBy('name')->get();
        
        return view('bookings.edit', compact('booking', 'customers', 'equipment_list'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'equipment' => 'required|array|min:1',
            'equipment.*.id' => 'required|exists:equipment,id',
            'equipment.*.quantity' => 'required|integer|min:1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'total_rent' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            // Prepare equipment data for sync
            $equipmentData = [];
            $totalRent = 0;
            
            // Fix N+1 Query: Bulk fetch equipment instead of individual queries
            $equipmentIds = collect($request->equipment)->pluck('id')->filter();
            $equipmentItems = Equipment::whereIn('id', $equipmentIds)
                ->get()
                ->keyBy('id');
            
            foreach ($request->equipment as $equipmentItem) {
                $equipment = $equipmentItems->get($equipmentItem['id']);
                if (!$equipment) {
                    throw new \Exception("Equipment with ID {$equipmentItem['id']} not found");
                }
                
                $quantity = $equipmentItem['quantity'];
                $days = now()->parse($request->start_date)->diffInDays(now()->parse($request->end_date)) + 1;
                
                $itemTotal = $equipment->daily_rate * $quantity * $days;
                $totalRent += $itemTotal;
                
                $equipmentData[$equipmentItem['id']] = [
                    'quantity' => $quantity,
                    'daily_rate' => $equipment->daily_rate,
                    'is_free' => false,
                ];
            }

            // Update the booking
            $booking->update([
                'customer_id' => $request->customer_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_rent' => $request->total_rent,
                'deposit_amount' => $request->deposit_amount ?? 0,
                'notes' => $request->notes,
            ]);

            // Sync equipment
            $booking->equipment()->sync($equipmentData);

            DB::commit();
            
            return redirect()->route('bookings.show', $booking)
                           ->with('success', 'Booking updated successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update booking. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        // Only allow deletion of pending or cancelled bookings
        if (!in_array($booking->status, ['pending', 'cancelled'])) {
            return redirect()->route('bookings.index')
                           ->with('error', 'Only pending or cancelled bookings can be deleted.');
        }
        
        $booking->equipment()->detach();
        $booking->delete();
        
        return redirect()->route('bookings.index')
                       ->with('success', 'Booking deleted successfully!');
    }

    /**
     * Confirm a pending booking.
     */
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Only pending bookings can be confirmed.');
        }
        
        $booking->update(['status' => 'confirmed']);
        
        return redirect()->route('bookings.show', $booking)
                       ->with('success', 'Booking confirmed successfully!');
    }

    /**
     * Activate a confirmed booking (mark as active when equipment is picked up).
     */
    public function activate(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Only confirmed bookings can be activated.');
        }
        
        DB::beginTransaction();
        
        try {
            // Fix bulk update: Update equipment status in single query instead of loop
            Equipment::whereIn('id', $booking->equipment->pluck('id'))
                ->update(['status' => 'rented']);
            
            $booking->update(['status' => 'active']);
            
            DB::commit();
            
            return redirect()->route('bookings.show', $booking)
                           ->with('success', 'Booking activated successfully! Equipment marked as rented.');
                           
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking activation failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'exception' => $e
            ]);
            
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Failed to activate booking. Please try again.');
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        if (in_array($booking->status, ['returned', 'cancelled'])) {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'This booking is already ' . $booking->status . '.');
        }
        
        DB::beginTransaction();
        
        try {
            // If booking was active, mark equipment as available again (bulk update)
            if ($booking->status === 'active') {
                Equipment::whereIn('id', $booking->equipment->pluck('id'))
                    ->update(['status' => 'available']);
            }
            
            $booking->update(['status' => 'cancelled']);
            
            DB::commit();
            
            return redirect()->route('bookings.show', $booking)
                           ->with('success', 'Booking cancelled successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking cancellation failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'exception' => $e
            ]);
            
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    /**
     * Process return of equipment.
     */
    public function processReturn(Booking $booking)
    {
        if ($booking->status !== 'active') {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Only active bookings can be returned.');
        }
        
        DB::beginTransaction();
        
        try {
            // Fix bulk update: Mark equipment as available again in single query
            Equipment::whereIn('id', $booking->equipment->pluck('id'))
                ->update(['status' => 'available']);
            
            $booking->update(['status' => 'returned']);
            
            DB::commit();
            
            return redirect()->route('bookings.show', $booking)
                           ->with('success', 'Equipment returned successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Equipment return failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'exception' => $e
            ]);
            
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Failed to process equipment return. Please try again.');
        }
    }
}
