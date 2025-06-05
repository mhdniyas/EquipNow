@extends('layouts.marketplace')

@section('title', 'About EquipNow - Premium Equipment Rentals')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">About EquipNow</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Your trusted partner for premium equipment rentals and professional solutions
            </p>
        </div>
    </section>

    @include('partials.marketplace.about')

    <!-- Additional About Content -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Our Story</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Founded in 2020, EquipNow has grown to become a leading equipment rental marketplace
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Established 2020</h3>
                    <p class="text-gray-600">5+ years of excellence in equipment rentals</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">1000+ Customers</h3>
                    <p class="text-gray-600">Trusted by contractors and professionals</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tools text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">500+ Equipment</h3>
                    <p class="text-gray-600">Wide variety of professional equipment</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Quality Assured</h3>
                    <p class="text-gray-600">All equipment tested and maintained</p>
                </div>
            </div>
        </div>
    </section>

    @include('partials.marketplace.testimonials')
@endsection
