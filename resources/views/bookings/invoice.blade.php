@extends('layouts.app')

@section('title', 'Invoice - Booking #' . $booking->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-print-none">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">Invoice - Booking #{{ $booking->id }}</h3>
                        </div>
                        <div class="col-auto">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print"></i> Print Invoice
                            </button>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Booking
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h2 class="text-primary">{{ config('app.name') }}</h2>
                            <p class="mb-1">Equipment Rental Services</p>
                            <p class="mb-1">123 Business Street</p>
                            <p class="mb-1">City, State 12345</p>
                            <p class="mb-0">Phone: (555) 123-4567</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h3>INVOICE</h3>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Invoice #:</strong></td>
                                    <td>INV-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Booking #:</strong></td>
                                    <td>{{ $booking->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Date:</strong></td>
                                    <td>{{ $invoiceDate->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Date:</strong></td>
                                    <td>{{ $dueDate->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Bill To:</h5>
                            <strong>{{ $booking->customer->name }}</strong><br>
                            @if($booking->customer->company)
                                {{ $booking->customer->company }}<br>
                            @endif
                            {{ $booking->customer->address }}<br>
                            {{ $booking->customer->city }}, {{ $booking->customer->state }} {{ $booking->customer->zip_code }}<br>
                            Phone: {{ $booking->customer->phone }}<br>
                            Email: {{ $booking->customer->email }}
                        </div>
                        <div class="col-md-6">
                            <h5>Rental Period:</h5>
                            <strong>Start Date:</strong> {{ $booking->start_date->format('M d, Y g:i A') }}<br>
                            <strong>End Date:</strong> {{ $booking->end_date->format('M d, Y g:i A') }}<br>
                            <strong>Duration:</strong> {{ $booking->duration }} day(s)<br>
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $booking->status_color }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Equipment Items -->
                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Daily Rate</th>
                                    <th class="text-center">Days</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->equipment as $equipment)
                                <tr>
                                    <td>
                                        <strong>{{ $equipment->name }}</strong>
                                        @if($equipment->model)
                                            <br><small class="text-muted">Model: {{ $equipment->model }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $equipment->category->name }}</td>
                                    <td class="text-center">{{ $equipment->pivot->quantity }}</td>
                                    <td class="text-end">${{ number_format($equipment->pivot->daily_rate, 2) }}</td>
                                    <td class="text-center">{{ $booking->duration }}</td>
                                    <td class="text-end">
                                        ${{ number_format($equipment->pivot->daily_rate * $equipment->pivot->quantity * $booking->duration, 2) }}
                                    </td>
                                </tr>
                                @endforeach

                                @foreach($booking->combos as $combo)
                                <tr>
                                    <td>
                                        <strong>{{ $combo->name }} (Combo)</strong>
                                        <br><small class="text-muted">{{ $combo->description }}</small>
                                    </td>
                                    <td>Combo Package</td>
                                    <td class="text-center">{{ $combo->pivot->quantity }}</td>
                                    <td class="text-end">${{ number_format($combo->pivot->daily_rate, 2) }}</td>
                                    <td class="text-center">{{ $booking->duration }}</td>
                                    <td class="text-end">
                                        ${{ number_format($combo->pivot->daily_rate * $combo->pivot->quantity * $booking->duration, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Additional Services -->
                    @if($booking->delivery_required || $booking->setup_required || $booking->insurance_required)
                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead class="table-secondary">
                                <tr>
                                    <th colspan="5">Additional Services</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($booking->delivery_required && $booking->delivery_fee > 0)
                                <tr>
                                    <td colspan="5">
                                        <strong>Delivery Service</strong>
                                        @if($booking->delivery_address)
                                            <br><small class="text-muted">To: {{ $booking->delivery_address }}</small>
                                        @endif
                                        @if($booking->delivery_date)
                                            <br><small class="text-muted">Date: {{ \Carbon\Carbon::parse($booking->delivery_date)->format('M d, Y') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end">${{ number_format($booking->delivery_fee, 2) }}</td>
                                </tr>
                                @endif

                                @if($booking->setup_required && $booking->setup_fee > 0)
                                <tr>
                                    <td colspan="5"><strong>Setup Service</strong></td>
                                    <td class="text-end">${{ number_format($booking->setup_fee, 2) }}</td>
                                </tr>
                                @endif

                                @if($booking->insurance_required && $booking->insurance_fee > 0)
                                <tr>
                                    <td colspan="5"><strong>Insurance Coverage</strong></td>
                                    <td class="text-end">${{ number_format($booking->insurance_fee, 2) }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Invoice Totals -->
                    <div class="row">
                        <div class="col-md-6">
                            @if($booking->notes)
                            <div class="mb-3">
                                <h6>Notes:</h6>
                                <p class="text-muted">{{ $booking->notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                @if($fees > 0)
                                <tr>
                                    <td><strong>Additional Services:</strong></td>
                                    <td class="text-end">${{ number_format($fees, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Deposit Required:</strong></td>
                                    <td class="text-end">${{ number_format($booking->deposit_amount, 2) }}</td>
                                </tr>
                                <tr class="table-dark">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($total, 2) }}</strong></td>
                                </tr>
                                @if($totalPaid > 0)
                                <tr class="table-success">
                                    <td><strong>Amount Paid:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($totalPaid, 2) }}</strong></td>
                                </tr>
                                @endif
                                @if($balance > 0)
                                <tr class="table-warning">
                                    <td><strong>Balance Due:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($balance, 2) }}</strong></td>
                                </tr>
                                @elseif($balance < 0)
                                <tr class="table-info">
                                    <td><strong>Credit Balance:</strong></td>
                                    <td class="text-end"><strong>${{ number_format(abs($balance), 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Payment History -->
                    @if($booking->payments->count() > 0)
                    <div class="mt-4">
                        <h5>Payment History</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Reference</th>
                                        <th class="text-end">Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>{{ $payment->reference_number ?? '-' }}</td>
                                        <td class="text-end">${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Terms and Conditions -->
                    <div class="mt-4 pt-3 border-top">
                        <h6>Terms and Conditions</h6>
                        <small class="text-muted">
                            <p>1. Equipment must be returned in the same condition as received.</p>
                            <p>2. Late returns may incur additional charges.</p>
                            <p>3. Customer is responsible for any damage or loss during rental period.</p>
                            <p>4. Payment is due upon invoice date unless otherwise agreed.</p>
                            <p>5. A security deposit may be required and will be refunded upon satisfactory return of equipment.</p>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .table {
        font-size: 11px;
    }
}
</style>
@endsection
