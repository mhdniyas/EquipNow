@extends('layouts.marketplace')

@section('title', 'Booking Confirmation - EquipNow')

@section('content')
    <!-- Confirmation Header -->
    <section class="bg-gradient-to-r from-green-600 to-emerald-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-6">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">Booking Confirmed!</h1>
            <p class="text-xl text-green-100 max-w-3xl mx-auto">
                Your equipment rental request has been successfully submitted
            </p>
        </div>
    </section>

    <!-- Booking Details -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Booking Details</h2>
                        <p class="text-gray-600">Booking ID: <span class="font-semibold">#{{ $booking->id }}</span></p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Customer Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Name:</span>
                                        <span class="font-semibold">{{ $booking->customer->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-semibold">{{ $booking->customer->email }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phone:</span>
                                        <span class="font-semibold">{{ $booking->customer->phone }}</span>
                                    </div>
                                    @if($booking->customer->address)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Address:</span>
                                            <span class="font-semibold text-right">{{ $booking->customer->address }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Rental Period -->
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Rental Period</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Start Date:</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">End Date:</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="font-semibold">
                                            {{ \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) + 1 }} day(s)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment and Pricing -->
                        <div class="space-y-6">
                            <!-- Equipment Details -->
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Equipment Details</h3>
                                @foreach($booking->equipment as $equipment)
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-start gap-4">
                                            @if($equipment->image_path)
                                                <img src="{{ asset('storage/' . $equipment->image_path) }}" 
                                                     alt="{{ $equipment->name }}" class="w-16 h-16 object-cover rounded-lg">
                                            @else
                                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-tools text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ $equipment->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $equipment->category->name }}</p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-sm text-gray-600">Quantity: {{ $equipment->pivot->quantity }}</span>
                                                    <span class="font-semibold text-blue-600">₹{{ number_format($equipment->pivot->daily_rate) }}/day</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pricing Breakdown -->
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Pricing Breakdown</h3>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                    @php
                                        $days = \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) + 1;
                                        $totalRent = $booking->total_amount - $booking->deposit_amount;
                                    @endphp
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Rental Cost ({{ $days }} day{{ $days > 1 ? 's' : '' }}):</span>
                                        <span class="font-semibold">₹{{ number_format($totalRent) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Security Deposit:</span>
                                        <span class="font-semibold">₹{{ number_format($booking->deposit_amount) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3">
                                        <div class="flex justify-between text-lg">
                                            <span class="font-bold text-gray-900">Total Amount:</span>
                                            <span class="font-bold text-blue-600">₹{{ number_format($booking->total_amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status and Notes -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Booking Status</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            @if($booking->notes)
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Notes</h3>
                                    <p class="text-gray-600">{{ $booking->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- What's Next -->
                    <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-bold text-blue-900 mb-3">What's Next?</h3>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-start">
                                <i class="fas fa-phone mt-1 mr-3 text-blue-600"></i>
                                <span>Our team will contact you within 2 hours to confirm the booking details</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-file-contract mt-1 mr-3 text-blue-600"></i>
                                <span>We'll arrange for equipment pickup or delivery as per your preference</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-credit-card mt-1 mr-3 text-blue-600"></i>
                                <span>Payment can be made at the time of equipment delivery</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('equipment-page.index') }}" 
                           class="btn-primary text-white px-8 py-3 rounded-lg font-semibold text-center">
                            Browse More Equipment
                        </a>
                        <a href="{{ route('home') }}" 
                           class="bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-semibold text-center hover:bg-gray-200 transition-colors">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
