@extends('layouts.marketplace')

@section('title', $equipment->name . ' - EquipNow')

@push('scripts')
<script>
function bookingForm() {
    return {
        // Form data
        startDate: '',
        endDate: '',
        quantity: 1,
        customerName: '',
        customerEmail: '',
        customerPhone: '',
        customerAddress: '',
        notes: '',
        
        // State
        loading: false,
        submitting: false,
        priceData: null,
        errors: [],
        
        // Computed
        get today() {
            return new Date().toISOString().split('T')[0];
        },
        
        // Methods
        async checkAvailability() {
            if (!this.startDate || !this.endDate || !this.quantity) {
                this.errors = ['Please fill in all required fields'];
                return;
            }
            
            this.loading = true;
            this.errors = [];
            
            try {
                const response = await fetch('{{ route("booking.check-availability") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        equipment_id: {{ $equipment->id }},
                        start_date: this.startDate,
                        end_date: this.endDate,
                        quantity: this.quantity
                    })
                });
                
                const data = await response.json();
                this.priceData = data;
                
            } catch (error) {
                this.errors = ['Failed to check availability. Please try again.'];
            } finally {
                this.loading = false;
            }
        },
        
        async submitBooking() {
            if (!this.customerName || !this.customerEmail || !this.customerPhone) {
                this.errors = ['Please fill in all required customer information'];
                return;
            }
            
            this.submitting = true;
            this.errors = [];
            
            try {
                const response = await fetch('{{ route("booking.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        equipment_id: {{ $equipment->id }},
                        start_date: this.startDate,
                        end_date: this.endDate,
                        quantity: this.quantity,
                        customer_name: this.customerName,
                        customer_email: this.customerEmail,
                        customer_phone: this.customerPhone,
                        customer_address: this.customerAddress,
                        notes: this.notes
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    if (data.errors) {
                        this.errors = Object.values(data.errors).flat();
                    } else {
                        this.errors = [data.message || 'An error occurred'];
                    }
                }
                
            } catch (error) {
                this.errors = ['Failed to submit booking. Please try again.'];
            } finally {
                this.submitting = false;
            }
        }
    }
}
</script>
@endpush

@section('content')
    <!-- Equipment Detail -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Equipment Image -->
                <div>
                    @if($equipment->image_path)
                        <img src="{{ asset('storage/' . $equipment->image_path) }}" 
                             alt="{{ $equipment->name }}" class="w-full rounded-2xl shadow-lg">
                    @else
                        <div class="w-full h-96 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-tools text-gray-400 text-8xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Equipment Info -->
                <div>
                    <div class="mb-4">
                        <a href="{{ route('categories.show', $equipment->category) }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            {{ $equipment->category->name }}
                        </a>
                    </div>

                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $equipment->name }}</h1>
                    
                    <div class="flex items-center mb-6">
                        <div class="flex items-center mr-6">
                            <div class="text-yellow-400 mr-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-600">{{ $equipment->rating ?? '4.5' }} ({{ $equipment->review_count ?? '10' }} reviews)</span>
                        </div>
                        
                        <div class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($equipment->quantity_available > 5) bg-green-100 text-green-800
                            @elseif($equipment->quantity_available > 0) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($equipment->quantity_available > 0)
                                {{ $equipment->quantity_available }} Available
                            @else
                                Out of Stock
                            @endif
                        </div>
                    </div>

                    <div class="text-4xl font-bold text-blue-600 mb-6">
                        ₹{{ number_format($equipment->daily_rate) }}
                        <span class="text-lg text-gray-500">/day</span>
                    </div>

                    <p class="text-gray-600 text-lg mb-8">{{ $equipment->description }}</p>

                    <!-- Specifications -->
                    @if($equipment->specifications)
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Specifications</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">{{ $equipment->specifications }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Rental Options -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Rental Options</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">₹{{ number_format($equipment->daily_rate) }}</div>
                                <div class="text-gray-600">Per Day</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">₹{{ number_format($equipment->daily_rate * 7 * 0.9) }}</div>
                                <div class="text-gray-600">Per Week</div>
                                <div class="text-green-600 text-sm">10% off</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">₹{{ number_format($equipment->daily_rate * 30 * 0.8) }}</div>
                                <div class="text-gray-600">Per Month</div>
                                <div class="text-green-600 text-sm">20% off</div>
                            </div>
                        </div>
                    </div>

                    <!-- Rental Form -->
                    <div class="bg-gray-50 p-6 rounded-2xl" x-data="bookingForm()">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Rent This Equipment</h3>
                        
                        <!-- Availability Check Form -->
                        <form @submit.prevent="checkAvailability" class="space-y-4 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" x-model="startDate" :min="today" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" x-model="endDate" :min="startDate" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <select x-model="quantity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @for($i = 1; $i <= min(10, $equipment->quantity_available); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                                    :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''">
                                <span x-show="!loading">Check Availability & Price</span>
                                <span x-show="loading">Checking...</span>
                            </button>
                        </form>

                        <!-- Price Preview -->
                        <div x-show="priceData" x-transition class="mb-6 p-4 bg-white rounded-lg border">
                            <h4 class="font-semibold text-gray-900 mb-3">Price Breakdown</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Daily Rate:</span>
                                    <span x-text="priceData?.formatted?.daily_rate"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Duration:</span>
                                    <span x-text="priceData?.days + ' day(s)'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Rental Cost:</span>
                                    <span x-text="priceData?.formatted?.total_rent"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Security Deposit:</span>
                                    <span x-text="priceData?.formatted?.deposit_amount"></span>
                                </div>
                                <div class="border-t pt-2 flex justify-between font-semibold">
                                    <span>Total Amount:</span>
                                    <span x-text="priceData?.formatted?.total_amount" class="text-blue-600"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Form -->
                        <form x-show="priceData && priceData.available" x-transition 
                              @submit.prevent="submitBooking" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" x-model="customerName" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" x-model="customerEmail" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                    <input type="tel" x-model="customerPhone" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea x-model="customerAddress" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Notes</label>
                                <textarea x-model="notes" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                          placeholder="Any special requirements or delivery instructions..."></textarea>
                            </div>
                            
                            <button type="submit" class="w-full btn-primary text-white py-4 rounded-lg text-lg font-semibold"
                                    :disabled="submitting" :class="submitting ? 'opacity-50 cursor-not-allowed' : ''">
                                <span x-show="!submitting">Confirm Booking</span>
                                <span x-show="submitting">Processing...</span>
                            </button>
                        </form>

                        <!-- Not Available Message -->
                        <div x-show="priceData && !priceData.available" x-transition 
                             class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Not enough quantity available. Only <span x-text="priceData?.available_quantity"></span> items in stock.
                            </p>
                        </div>
                        
                        <!-- Error Messages -->
                        <div x-show="errors.length > 0" x-transition class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="text-red-800 text-sm space-y-1">
                                <template x-for="error in errors" :key="error">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Equipment -->
    @if($relatedEquipment->count() > 0)
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Related Equipment</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Other equipment in the {{ $equipment->category->name }} category
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedEquipment as $item)
                        <div class="equipment-card card-hover">
                            <div class="relative">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" 
                                         alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-tools text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 right-4 px-2 py-1 rounded-full text-xs font-semibold
                                    @if($item->quantity_available > 0) bg-green-500 text-white
                                    @else bg-red-500 text-white @endif">
                                    @if($item->quantity_available > 0) Available @else Out of Stock @endif
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($item->description, 80) }}</p>
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-blue-600">
                                        ₹{{ number_format($item->daily_rate) }}
                                        <span class="text-sm text-gray-500">/day</span>
                                    </span>
                                </div>
                                
                                <a href="{{ route('equipment-page.show', $item) }}" 
                                   class="w-full btn-primary text-white py-3 rounded-lg font-semibold text-center block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
