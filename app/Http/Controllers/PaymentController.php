<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['booking.customer', 'user']);
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by payment type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }
        
        // Search by reference number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('booking.customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        
        // Payment statistics
        $stats = [
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
            'today_payments' => Payment::whereDate('payment_date', today())->sum('amount'),
            'cash_payments' => Payment::where('payment_method', 'cash')
                                    ->where('status', 'completed')
                                    ->whereDate('payment_date', today())
                                    ->sum('amount'),
            'upi_payments' => Payment::where('payment_method', 'upi')
                                   ->where('status', 'completed')
                                   ->whereDate('payment_date', today())
                                   ->sum('amount'),
            'card_payments' => Payment::where('payment_method', 'card')
                                    ->where('status', 'completed')
                                    ->whereDate('payment_date', today())
                                    ->sum('amount'),
        ];
        
        return view('payments.index', compact('payments', 'stats'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        $booking = null;
        if ($request->filled('booking_id')) {
            $booking = Booking::with(['customer', 'equipment'])->find($request->booking_id);
        }
        
        return view('payments.create', compact('booking'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,upi,card,bank_transfer,cheque',
            'payment_date' => 'required|date',
            'type' => 'required|in:rent,deposit,refund,penalty',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            $payment = Payment::create([
                'booking_id' => $request->booking_id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'type' => $request->type,
                'reference_number' => $request->reference_number,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            // Update booking payment status if this is a deposit or rent payment
            $booking = Booking::find($request->booking_id);
            $totalPaid = $booking->payments()->where('status', 'completed')->sum('amount');
            
            if ($totalPaid >= $booking->total_rent) {
                $booking->update(['payment_status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $booking->update(['payment_status' => 'partial']);
            }

            DB::commit();
            
            return redirect()->route('payments.show', $payment)
                           ->with('success', 'Payment recorded successfully!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to record payment. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.equipment', 'user']);
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        // Only allow editing of pending payments
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                           ->with('error', 'Only pending payments can be edited.');
        }
        
        $payment->load(['booking.customer']);
        
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Only allow updating of pending payments
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                           ->with('error', 'Only pending payments can be updated.');
        }
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,upi,card,bank_transfer,cheque',
            'payment_date' => 'required|date',
            'type' => 'required|in:rent,deposit,refund,penalty',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment->update($request->only([
            'amount',
            'payment_method', 
            'payment_date',
            'type',
            'reference_number',
            'notes'
        ]));
        
        return redirect()->route('payments.show', $payment)
                       ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only allow deletion of pending payments
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.index')
                           ->with('error', 'Only pending payments can be deleted.');
        }
        
        $payment->delete();
        
        return redirect()->route('payments.index')
                       ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Mark payment as completed.
     */
    public function markCompleted(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                           ->with('error', 'Only pending payments can be marked as completed.');
        }
        
        DB::beginTransaction();
        
        try {
            $payment->update(['status' => 'completed']);
            
            // Update booking payment status
            $booking = $payment->booking;
            $totalPaid = $booking->payments()->where('status', 'completed')->sum('amount');
            
            if ($totalPaid >= $booking->total_rent) {
                $booking->update(['payment_status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $booking->update(['payment_status' => 'partial']);
            }
            
            DB::commit();
            
            return redirect()->route('payments.show', $payment)
                           ->with('success', 'Payment marked as completed!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('payments.show', $payment)
                           ->with('error', 'Failed to update payment status.');
        }
    }

    /**
     * Generate daily payment report.
     */
    public function dailyReport(Request $request)
    {
        $date = $request->input('date', today());
        
        $payments = Payment::with(['booking.customer'])
                          ->whereDate('payment_date', $date)
                          ->where('status', 'completed')
                          ->orderBy('payment_date')
                          ->get();
        
        $summary = [
            'total_amount' => $payments->sum('amount'),
            'cash_total' => $payments->where('payment_method', 'cash')->sum('amount'),
            'upi_total' => $payments->where('payment_method', 'upi')->sum('amount'),
            'card_total' => $payments->where('payment_method', 'card')->sum('amount'),
            'rent_payments' => $payments->where('type', 'rent')->sum('amount'),
            'deposit_payments' => $payments->where('type', 'deposit')->sum('amount'),
            'refund_payments' => $payments->where('type', 'refund')->sum('amount'),
        ];
        
        return view('payments.daily-report', compact('payments', 'summary', 'date'));
    }
}
