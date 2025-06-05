@extends('layouts.marketplace')

@section('title', 'Equipment Categories - EquipNow')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">Equipment Categories</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Discover our comprehensive range of equipment categories for all your project needs
            </p>
        </div>
    </section>

    @include('partials.marketplace.categories')
@endsection
