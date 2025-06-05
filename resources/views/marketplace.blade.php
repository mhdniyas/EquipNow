@extends('layouts.guest')

@section('content')
    @include('partials.marketplace.hero')
    @include('partials.marketplace.stats')
    @include('partials.marketplace.categories')
    @include('partials.marketplace.equipment')
    @include('partials.marketplace.about')
    @include('partials.marketplace.testimonials')
    @include('partials.marketplace.contact')
    @include('partials.marketplace.footer')
    @include('partials.marketplace.scroll-to-top')
@endsection
