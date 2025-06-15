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
        $query = Booking::with(['customer', 'user', 'equipment', 'payments']);
        
        // Enhanced filtering
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', 'active')
                      ->whereDate('end_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Date range filtering
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }
        
        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        // Enhanced search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('equipment', function ($equipQuery) use ($search) {
                      $equipQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        $bookings = $query->paginate($request->get('per_page', 20));
        
        // Enhanced statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'active_bookings' => Booking::where('status', 'active')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'overdue_bookings' => Booking::where('status', 'active')
                                        ->whereDate('end_date', '<', now())
                                        ->count(),
            'due_today' => Booking::where('status', 'active')
                                 ->whereDate('end_date', now())
                                 ->count(),
            'revenue_today' => Booking::whereDate('created_at', now())
                                    ->sum('total_rent'),
            'revenue_this_month' => Booking::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->sum('total_rent'),
            'revenue_this_year' => Booking::whereYear('created_at', now()->year)
                                        ->sum('total_rent'),
            'new_this_month' => Booking::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
            'avg_booking_value' => Booking::avg('total_rent') ?? 0,
            'equipment_utilization' => $this->calculateEquipmentUtilization(),
        ];
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'bookings' => $bookings,
                'stats' => $stats
            ]);
        }
        
        // Get customers for filter dropdown
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        
        return view('bookings.redesigned-index', compact('bookings', 'stats', 'customers'));
    }
    
    /**
     * Calculate equipment utilization percentage
     */
    private function calculateEquipmentUtilization()
    {
        $totalEquipment = Equipment::count();
        $rentedEquipment = Equipment::where('status', 'rented')->count();
        
        return $totalEquipment > 0 ? round(($rentedEquipment / $totalEquipment) * 100, 1) : 0;
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        // Load only first 4 customers initially for better UX
        $customers = Customer::orderBy('name')
                           ->limit(4)
                           ->get();
                           
        $equipment = Equipment::with('category')
                            ->where('status', 'available')
                            ->orderBy('name')
                            ->get();
        $categories = Equipment::with('category')
                             ->where('status', 'available')
                             ->get()
                             ->groupBy('category.name');
        
        // Pre-select customer if provided
        $selectedCustomer = null;
        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
        }
        
        // Pre-select equipment if provided
        $selectedEquipment = [];
        if ($request->filled('equipment_id')) {
            $selectedEquipment = Equipment::whereIn('id', (array)$request->equipment_id)->get();
        }
        
        return view('bookings.redesigned-create', compact(
            'customers', 
            'equipment', 
            'categories',
            'selectedCustomer', 
            'selectedEquipment'
        ));
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
            'payment_method' => 'nullable|string',
            'delivery_required' => 'nullable|boolean',
            'setup_required' => 'nullable|boolean',
            'insurance_required' => 'nullable|boolean',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable|string',
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
            
            // Bulk fetch equipment instead of individual queries
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

            // Create the booking with additional fields
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
                'payment_method' => $request->payment_method,
                'delivery_required' => $request->delivery_required ?? false,
                'setup_required' => $request->setup_required ?? false,
                'insurance_required' => $request->insurance_required ?? false,
                'delivery_address' => $request->delivery_address,
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
            ]);

            // Attach equipment to booking
            $booking->equipment()->attach($equipmentData);

            DB::commit();
            
            // For AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking created successfully!',
                    'booking_id' => $booking->id,
                    'redirect' => route('bookings.show', $booking)
                ]);
            }
            
            return redirect()->route('bookings.show', $booking)
                           ->with('success', 'Booking created successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create booking: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Failed to create booking: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load(['customer', 'user', 'equipment.category', 'payments.user']);
        
        // Calculate days and totals
        $days = $booking->start_date->diffInDays($booking->end_date) + 1;
        $equipmentTotal = $booking->equipment->sum(function ($equipment) use ($days) {
            return $equipment->pivot->quantity * $equipment->pivot->daily_rate * $days;
        });
        
        // Get additional fees
        $deliveryFee = $booking->delivery_required ? 50 : 0;
        $setupFee = $booking->setup_required ? 75 : 0;
        $insuranceFee = $booking->insurance_required ? ($equipmentTotal * 0.05) : 0;
        $totalFees = $deliveryFee + $setupFee + $insuranceFee;
        
        // Payment summary
        $totalPaid = $booking->payments->where('status', 'completed')->sum('amount');
        $remainingBalance = ($booking->total_rent + $totalFees) - $totalPaid;
        
        // Booking timeline/history
        $timeline = collect([
            [
                'event' => 'Booking Created',
                'date' => $booking->created_at,
                'user' => $booking->user->name ?? 'System',
                'description' => 'Initial booking created',
                'type' => 'created'
            ]
        ]);
        
        // Add status changes to timeline (this would require a status_history table in a real app)
        if ($booking->status !== 'pending') {
            $timeline->push([
                'event' => 'Status Changed',
                'date' => $booking->updated_at,
                'user' => 'System',
                'description' => "Status changed to {$booking->status}",
                'type' => 'status_change'
            ]);
        }
        
        // Add payments to timeline
        foreach ($booking->payments as $payment) {
            $timeline->push([
                'event' => 'Payment ' . ucfirst($payment->status),
                'date' => $payment->created_at,
                'user' => $payment->user->name ?? 'System',
                'description' => "Payment of $" . number_format($payment->amount, 2) . " via {$payment->payment_method}",
                'type' => 'payment'
            ]);
        }
        
        $timeline = $timeline->sortByDesc('date');
        
        // Customer statistics
        $customerStats = [
            'total_bookings' => $booking->customer->bookings()->count(),
            'active_bookings' => $booking->customer->bookings()->where('status', 'active')->count(),
            'total_spent' => $booking->customer->bookings()->sum('total_rent'),
            'avg_booking_value' => $booking->customer->bookings()->avg('total_rent') ?? 0,
            'last_booking' => $booking->customer->bookings()->latest()->first()?->created_at,
        ];
        
        return view('bookings.redesigned-show', compact(
            'booking', 
            'days', 
            'equipmentTotal',
            'deliveryFee',
            'setupFee', 
            'insuranceFee',
            'totalFees',
            'totalPaid',
            'remainingBalance',
            'timeline',
            'customerStats'
        ));
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

    /**
     * AJAX: Quick confirm booking
     */
    public function quickConfirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending bookings can be confirmed.'
            ], 400);
        }
        
        $booking->update(['status' => 'confirmed']);
        
        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully!',
            'status' => 'confirmed'
        ]);
    }

    /**
     * AJAX: Quick activate booking
     */
    public function quickActivate(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Only confirmed bookings can be activated.'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            Equipment::whereIn('id', $booking->equipment->pluck('id'))
                ->update(['status' => 'rented']);
            
            $booking->update(['status' => 'active']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking activated successfully!',
                'status' => 'active'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate booking. Please try again.'
            ], 500);
        }
    }

    /**
     * AJAX: Quick cancel booking
     */
    public function quickCancel(Booking $booking)
    {
        if (in_array($booking->status, ['returned', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'This booking is already ' . $booking->status . '.'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            if ($booking->status === 'active') {
                Equipment::whereIn('id', $booking->equipment->pluck('id'))
                    ->update(['status' => 'available']);
            }
            
            $booking->update(['status' => 'cancelled']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully!',
                'status' => 'cancelled'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking. Please try again.'
            ], 500);
        }
    }

    /**
     * AJAX: Equipment search for create form
     */
    public function searchEquipment(Request $request)
    {
        $query = Equipment::where('status', 'available');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $equipment = $query->with('category')->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'equipment' => $equipment->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'daily_rate' => $item->daily_rate,
                    'category' => $item->category->name ?? 'Uncategorized',
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'description' => $item->description,
                    'available_quantity' => $item->quantity_available ?? 1
                ];
            })
        ]);
    }

    /**
     * AJAX: Customer search for create form
     */
    public function searchCustomers(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|min:1|max:50',
            'offset' => 'nullable|integer|min:0'
        ]);
        
        $query = Customer::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $limit = $request->get('limit', 4);
        $offset = $request->get('offset', 0);
        $customers = $query->orderBy('name')->skip($offset)->limit($limit)->get();
        
        return response()->json([
            'success' => true,
            'customers' => $customers->map(function ($customer) {
                                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone ?? '',
                    'initials' => strtoupper(substr($customer->name, 0, 2)),
                    'booking_count' => $customer->bookings()->count()
                ];
            })
        ]);
    }

    /**
     * AJAX: Calculate pricing for equipment selection
     */
    public function calculatePricing(Request $request)
    {
        $request->validate([
            'equipment' => 'required|array',
            'equipment.*.id' => 'required|exists:equipment,id',
            'equipment.*.quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'delivery_required' => 'nullable|boolean',
            'setup_required' => 'nullable|boolean',
            'insurance_required' => 'nullable|boolean',
        ]);
        
        $startDate = now()->parse($request->start_date);
        $endDate = now()->parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        
        $equipmentItems = Equipment::whereIn('id', collect($request->equipment)->pluck('id'))->get()->keyBy('id');
        
        $subtotal = 0;
        $breakdown = [];
        
        foreach ($request->equipment as $item) {
            $equipment = $equipmentItems->get($item['id']);
            if ($equipment) {
                $itemTotal = $equipment->daily_rate * $item['quantity'] * $days;
                $subtotal += $itemTotal;
                
                $breakdown[] = [
                    'name' => $equipment->name,
                    'quantity' => $item['quantity'],
                    'daily_rate' => $equipment->daily_rate,
                    'days' => $days,
                    'total' => $itemTotal
                ];
            }
        }
        
        // Additional fees
        $deliveryFee = $request->delivery_required ? 50 : 0;
        $setupFee = $request->setup_required ? 75 : 0;
        $insuranceFee = $request->insurance_required ? ($subtotal * 0.05) : 0;
        
        $fees = $deliveryFee + $setupFee + $insuranceFee;
        $totalBeforeTax = $subtotal + $fees;
        $tax = $totalBeforeTax * 0.08; // 8% tax
        $totalAmount = $totalBeforeTax + $tax;
        
        return response()->json([
            'success' => true,
            'breakdown' => $breakdown,
            'subtotal' => $subtotal,
            'fees' => [
                'delivery' => $deliveryFee,
                'setup' => $setupFee,
                'insurance' => $insuranceFee,
                'total' => $fees
            ],
            'tax' => $tax,
            'total' => $totalAmount,
            'days' => $days,
            'formatted' => [
                'subtotal' => '$' . number_format($subtotal, 2),
                'fees' => '$' . number_format($fees, 2),
                'tax' => '$' . number_format($tax, 2),
                'total' => '$' . number_format($totalAmount, 2)
            ]
        ]);
    }

    /**
     * AJAX: Get booking statistics for dashboard
     */
    public function getStats(Request $request)
    {
        $dateRange = $request->get('range', 'month');
        
        $query = Booking::query();
        
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        $stats = [
            'total_bookings' => Booking::count(),
            'active_bookings' => Booking::where('status', 'active')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'due_today' => Booking::whereDate('end_date', today())->where('status', 'active')->count(),
            'overdue' => Booking::whereDate('end_date', '<', today())->where('status', 'active')->count(),
            'revenue_period' => $query->sum('total_rent'),
            'bookings_period' => $query->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'period' => $dateRange
        ]);
    }

    /**
     * Bulk operations for bookings
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:confirm,cancel,delete,export',
            'booking_ids' => 'required|array|min:1',
            'booking_ids.*' => 'exists:bookings,id'
        ]);
        
        $bookings = Booking::whereIn('id', $request->booking_ids)->get();
        $results = ['success' => 0, 'failed' => 0, 'messages' => []];
        
        DB::beginTransaction();
        
        try {
            foreach ($bookings as $booking) {
                switch ($request->action) {
                    case 'confirm':
                        if ($booking->status === 'pending') {
                            $booking->update(['status' => 'confirmed']);
                            $results['success']++;
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Booking #{$booking->id} cannot be confirmed (status: {$booking->status})";
                        }
                        break;
                        
                    case 'cancel':
                        if (!in_array($booking->status, ['returned', 'cancelled'])) {
                            if ($booking->status === 'active') {
                                Equipment::whereIn('id', $booking->equipment->pluck('id'))
                                    ->update(['status' => 'available']);
                            }
                            $booking->update(['status' => 'cancelled']);
                            $results['success']++;
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Booking #{$booking->id} is already {$booking->status}";
                        }
                        break;
                        
                    case 'delete':
                        if (in_array($booking->status, ['pending', 'cancelled'])) {
                            $booking->equipment()->detach();
                            $booking->delete();
                            $results['success']++;
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Booking #{$booking->id} cannot be deleted (status: {$booking->status})";
                        }
                        break;
                        
                    case 'export':
                        // Handle export separately
                        $results['success']++;
                        break;
                }
            }
            
            DB::commit();
            
            if ($request->action === 'export') {
                return $this->exportBookings($bookings);
            }
            
            return response()->json([
                'success' => true,
                'message' => "Bulk {$request->action} completed: {$results['success']} successful, {$results['failed']} failed",
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export bookings to CSV
     */
    private function exportBookings($bookings)
    {
        $filename = 'bookings_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Booking ID',
                'Customer Name',
                'Customer Email',
                'Status',
                'Start Date',
                'End Date',
                'Total Amount',
                'Deposit Amount',
                'Payment Status',
                'Equipment Count',
                'Created At'
            ]);
            
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->customer->name,
                    $booking->customer->email,
                    $booking->status,
                    $booking->start_date->format('Y-m-d'),
                    $booking->end_date->format('Y-m-d'),
                    $booking->total_rent,
                    $booking->deposit_amount,
                    $booking->payment_status,
                    $booking->equipment->count(),
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calendar view for bookings
     */
    public function calendar(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $startDate = now()->create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $bookings = Booking::with(['customer', 'equipment'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->get();
        
        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => "#{$booking->id} - {$booking->customer->name}",
                'start' => $booking->start_date->format('Y-m-d'),
                'end' => $booking->end_date->addDay()->format('Y-m-d'), // FullCalendar expects exclusive end date
                'color' => $this->getStatusColor($booking->status),
                'status' => $booking->status,
                'customer' => $booking->customer->name,
                'total' => $booking->total_rent,
                'equipment_count' => $booking->equipment->count()
            ];
        });
        
        if ($request->ajax()) {
            return response()->json($events);
        }
        
        return view('bookings.calendar', compact('events', 'year', 'month'));
    }

    /**
     * Get status color for calendar events
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => '#fbbf24',
            'confirmed' => '#3b82f6',
            'active' => '#10b981',
            'returned' => '#6b7280',
            'cancelled' => '#ef4444',
            default => '#9ca3af'
        };
    }

    /**
     * Duplicate a booking
     */
    public function duplicate(Booking $booking)
    {
        $newBooking = $booking->replicate();
        $newBooking->status = 'pending';
        $newBooking->payment_status = 'pending';
        $newBooking->start_date = now()->addDay();
        $newBooking->end_date = now()->addDays(1 + $booking->start_date->diffInDays($booking->end_date));
        $newBooking->save();
        
        // Copy equipment relationships
        $equipmentData = [];
        foreach ($booking->equipment as $equipment) {
            $equipmentData[$equipment->id] = [
                'quantity' => $equipment->pivot->quantity,
                'daily_rate' => $equipment->pivot->daily_rate,
                'is_free' => $equipment->pivot->is_free
            ];
        }
        $newBooking->equipment()->attach($equipmentData);
        
        return redirect()->route('bookings.edit', $newBooking)
                       ->with('success', 'Booking duplicated successfully! Please review and update the dates.');
    }

    /**
     * Generate invoice for a booking.
     */
    public function invoice(Booking $booking)
    {
        try {
            $booking->load([
                'customer',
                'equipment.category',
                'combos',
                'payments',
                'user'
            ]);

            // Calculate invoice details
            $subtotal = $booking->total_rent;
            $fees = $booking->delivery_fee + $booking->setup_fee + $booking->insurance_fee;
            $total = $subtotal + $fees;
            $totalPaid = $booking->payments->sum('amount');
            $balance = $total - $totalPaid;

            $invoiceData = [
                'booking' => $booking,
                'subtotal' => $subtotal,
                'fees' => $fees,
                'total' => $total,
                'totalPaid' => $totalPaid,
                'balance' => $balance,
                'invoiceDate' => now(),
                'dueDate' => $booking->start_date,
            ];

            return view('bookings.invoice', $invoiceData);
        } catch (\Exception $e) {
            Log::error('Error generating invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate invoice.');
        }
    }
}
