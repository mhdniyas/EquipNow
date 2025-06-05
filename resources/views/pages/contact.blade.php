@extends('layouts.marketplace')

@section('title', 'Contact Us - EquipNow')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">Contact Us</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Get in touch with us for all your equipment rental needs
            </p>
        </div>
    </section>

    @include('partials.marketplace.contact')

    <!-- Additional Contact Information -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Visit Our Locations</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Find us at our physical locations for in-person consultations
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Mumbai Branch</h3>
                    </div>
                    <div class="space-y-3 text-gray-600">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>123 Equipment Street, Business District, Mumbai 400001</p>
                        <p><i class="fas fa-phone mr-2"></i>+91 98765 43210</p>
                        <p><i class="fas fa-envelope mr-2"></i>mumbai@equipnow.com</p>
                        <p><i class="fas fa-clock mr-2"></i>Mon-Sat: 9AM-8PM</p>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Delhi Branch</h3>
                    </div>
                    <div class="space-y-3 text-gray-600">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>456 Rental Road, CP, New Delhi 110001</p>
                        <p><i class="fas fa-phone mr-2"></i>+91 98765 43211</p>
                        <p><i class="fas fa-envelope mr-2"></i>delhi@equipnow.com</p>
                        <p><i class="fas fa-clock mr-2"></i>Mon-Sat: 9AM-8PM</p>
                    </div>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Bangalore Branch</h3>
                    </div>
                    <div class="space-y-3 text-gray-600">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>789 Tech Avenue, Koramangala, Bangalore 560034</p>
                        <p><i class="fas fa-phone mr-2"></i>+91 98765 43212</p>
                        <p><i class="fas fa-envelope mr-2"></i>bangalore@equipnow.com</p>
                        <p><i class="fas fa-clock mr-2"></i>Mon-Sat: 9AM-8PM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600">
                    Quick answers to common questions about our rental services
                </p>
            </div>
            
            <div class="space-y-6" x-data="{ openFaq: null }">
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 1 ? null : 1" 
                            class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold text-gray-900">How do I rent equipment?</span>
                        <i class="fas fa-chevron-down transform transition-transform" 
                           :class="{ 'rotate-180': openFaq === 1 }"></i>
                    </button>
                    <div x-show="openFaq === 1" x-transition class="px-6 pb-4">
                        <p class="text-gray-600">Simply browse our equipment, select your rental dates, and complete the booking process online. We'll deliver the equipment to your location.</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 2 ? null : 2" 
                            class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold text-gray-900">What are your rental rates?</span>
                        <i class="fas fa-chevron-down transform transition-transform" 
                           :class="{ 'rotate-180': openFaq === 2 }"></i>
                    </button>
                    <div x-show="openFaq === 2" x-transition class="px-6 pb-4">
                        <p class="text-gray-600">Our rates vary by equipment type and rental duration. You can view pricing on each equipment page. We offer competitive daily, weekly, and monthly rates.</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 3 ? null : 3" 
                            class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold text-gray-900">Do you provide delivery and pickup?</span>
                        <i class="fas fa-chevron-down transform transition-transform" 
                           :class="{ 'rotate-180': openFaq === 3 }"></i>
                    </button>
                    <div x-show="openFaq === 3" x-transition class="px-6 pb-4">
                        <p class="text-gray-600">Yes, we provide delivery and pickup services within our service areas. Delivery charges may apply based on distance and equipment size.</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 4 ? null : 4" 
                            class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-semibold text-gray-900">What if equipment gets damaged?</span>
                        <i class="fas fa-chevron-down transform transition-transform" 
                           :class="{ 'rotate-180': openFaq === 4 }"></i>
                    </button>
                    <div x-show="openFaq === 4" x-transition class="px-6 pb-4">
                        <p class="text-gray-600">Normal wear and tear is expected. For significant damage, repair costs will be assessed and charged accordingly. We recommend reviewing our terms and conditions before rental.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
