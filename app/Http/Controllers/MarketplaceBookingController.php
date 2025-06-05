<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class MarketplaceBookingController extends Controller
{
    /**
     * Show the booking form for specific equipment.
     */
    public function create(Equipment $equipment)
    {
        // Check if equipment is available
        if ($equipment->status !== Equipment::STATUS_AVAILABLE || $equipment->quantity_available <= 0) {
            return redirect()->back()->with('error', 'This equipment is not available for booking.');
        }

        return view('pages.booking-form', compact('equipment'));
    }

    /**
     * Store a new booking from the marketplace.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required_unless:user_authenticated,true|string|max:255',
            'customer_email' => 'required_unless:user_authenticated,true|email|max:255',
            'customer_phone' => 'required_unless:user_authenticated,true|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Get equipment
            $equipment = Equipment::findOrFail($validated['equipment_id']);

            // Check availability
            if ($equipment->quantity_available < $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Only ' . $equipment->quantity_available . ' items available.'
                ]);
            }

            // Get or create customer
            if (Auth::check()) {
                // For authenticated users, get or create customer record linked to user
                $customer = Customer::firstOrCreate(
                    ['email' => Auth::user()->email],
                    [
                        'name' => Auth::user()->name,
                        'phone' => $validated['customer_phone'] ?? '',
                        'address' => $validated['customer_address'] ?? '',
                        'user_id' => Auth::id(),
                    ]
                );

                // Update customer info if provided
                if (!empty($validated['customer_phone'])) {
                    $customer->phone = $validated['customer_phone'];
                }
                if (!empty($validated['customer_address'])) {
                    $customer->address = $validated['customer_address'];
                }
                $customer->save();
            } else {
                // For guest users, find or create customer record
                $customer = Customer::where('email', $validated['customer_email'])
                    ->orWhere('phone', $validated['customer_phone'])
                    ->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $validated['customer_name'],
                        'email' => $validated['customer_email'],
                        'phone' => $validated['customer_phone'],
                        'address' => $validated['customer_address'] ?? '',
                    ]);
                }
            }

            // Calculate dates and pricing
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $days = $startDate->diffInDays($endDate) + 1; // Include both start and end days
            
            $dailyRate = $equipment->daily_rate;
            $totalRent = $dailyRate * $validated['quantity'] * $days;
            $depositAmount = $equipment->deposit_amount * $validated['quantity'];
            $totalAmount = $totalRent + $depositAmount;

            // Create booking
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_amount' => $totalAmount,
                'deposit_amount' => $depositAmount,
                'status' => Booking::STATUS_PENDING,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Attach equipment to booking
            $booking->equipment()->attach($equipment->id, [
                'quantity' => $validated['quantity'],
                'daily_rate' => $dailyRate,
                'is_free' => false,
            ]);

            // Update equipment availability
            $equipment->decrement('quantity_available', $validated['quantity']);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'message' => 'Booking created successfully! We will contact you shortly to confirm.',
                'redirect' => route('marketplace.booking.confirmation', $booking->id)
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking. Please try again.'
            ], 500);
        }
    }

    /**
     * Show booking confirmation page.
     */
    public function confirmation(Booking $booking)
    {
        // Check if user can view this booking
        if (Auth::check() && $booking->customer->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        $booking->load(['customer', 'equipment']);
        
        return view('pages.booking-confirmation', compact('booking'));
    }

    /**
     * Quick booking with authentication check.
     */
    public function quickBook(Request $request, Equipment $equipment)
    {
        if (!Auth::check()) {
            return response()->json([
                'redirect' => route('login') . '?redirect=' . urlencode(route('equipment-page.show', $equipment)),
                'message' => 'Please log in to make a quick booking.'
            ]);
        }

        // For authenticated users, redirect to booking form with pre-filled data
        return response()->json([
            'redirect' => route('marketplace.booking.create', $equipment),
            'message' => 'Redirecting to booking form...'
        ]);
    }

    /**
     * Check equipment availability for given dates.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::find($request->equipment_id);
        
        // Check current availability
        $available = $equipment->quantity_available >= $request->quantity;
        
        // TODO: Add logic to check for overlapping bookings
        
        return response()->json([
            'available' => $available,
            'quantity_available' => $equipment->quantity_available,
            'daily_rate' => $equipment->daily_rate,
            'deposit_amount' => $equipment->deposit_amount,
        ]);
    }
}
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);
        
        // Check if enough quantity is available
        $available = $equipment->quantity_available >= $validated['quantity'];
        
        // Calculate pricing
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;
        
        $dailyRate = $equipment->daily_rate;
        $totalRent = $dailyRate * $validated['quantity'] * $days;
        $depositAmount = $equipment->deposit_amount * $validated['quantity'];
        $totalAmount = $totalRent + $depositAmount;

        return response()->json([
            'available' => $available,
            'available_quantity' => $equipment->quantity_available,
            'days' => $days,
            'daily_rate' => $dailyRate,
            'total_rent' => $totalRent,
            'deposit_amount' => $depositAmount,
            'total_amount' => $totalAmount,
            'formatted' => [
                'daily_rate' => '₹' . number_format($dailyRate),
                'total_rent' => '₹' . number_format($totalRent),
                'deposit_amount' => '₹' . number_format($depositAmount),
                'total_amount' => '₹' . number_format($totalAmount),
            ]
        ]);
    }
}
