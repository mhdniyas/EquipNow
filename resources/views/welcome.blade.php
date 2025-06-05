@extends('layouts.marketplace')

@section('title', 'EquipNow - Premium Equipment Rental Marketplace')

@section('content')
    @include('partials.marketplace.hero')
    @include('partials.marketplace.stats')
    
    <!-- Quick Overview Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 font-serif">Explore Our Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Discover everything we offer for your equipment rental needs
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <a href="{{ route('categories.index') }}" class="group">
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 p-8 rounded-2xl text-center hover:shadow-lg transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-th-large text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Browse Categories</h3>
                        <p class="text-gray-600">Explore our wide range of equipment categories</p>
                    </div>
                </a>
                
                <a href="{{ route('equipment-page.index') }}" class="group">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl text-center hover:shadow-lg transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-tools text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">All Equipment</h3>
                        <p class="text-gray-600">Browse our complete equipment inventory</p>
                    </div>
                </a>
                
                <a href="{{ route('about.index') }}" class="group">
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl text-center hover:shadow-lg transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-info-circle text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">About Us</h3>
                        <p class="text-gray-600">Learn more about our company and services</p>
                    </div>
                </a>
                
                <a href="{{ route('contact.index') }}" class="group">
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 p-8 rounded-2xl text-center hover:shadow-lg transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-phone text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Contact Us</h3>
                        <p class="text-gray-600">Get in touch with our support team</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection