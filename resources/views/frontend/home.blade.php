@extends('layouts.app')

@section('title', 'Khati Bajar - Pure & Organic Agricultural & Farm Products')

@section('content')
<!-- HERO SECTION (Logo Centered + 2 Lines of Text) -->
<section class="py-8 sm:py-12 bg-gradient-to-b from-brand-800 via-brand-700 to-brand-800 text-white border-b border-brand-600/40 relative overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center justify-center">
        
        <!-- Logo in Hero Section -->
        <div class="bg-white px-6 py-3 sm:px-8 sm:py-4 rounded-2xl shadow-lg mb-4 sm:mb-5 inline-flex items-center justify-center">
            <img 
                src="{{ site_setting('site_logo') ? asset('storage/' . site_setting('site_logo')) : asset('images/logo.png') }}" 
                alt="Khati Bajar Logo" 
                class="h-14 sm:h-20 md:h-24 w-auto object-contain"
            >
        </div>

        <!-- 2 Lines of Text Below Logo -->
        <h1 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight max-w-2xl leading-snug">
            100% Pure &amp; Natural Agricultural and Farm Products
        </h1>
        <p class="text-xs sm:text-sm md:text-base text-brand-100 mt-1.5 max-w-xl leading-relaxed">
            Your trusted digital marketplace for authentic livestock medicines, fish supplements &amp; organic farm supplies.
        </p>

    </div>
</section>

<!-- ALL PRODUCTS SECTION (Single Clean Product Grid) -->
<section class="py-8 sm:py-12 max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6 sm:mb-8 pb-3 border-b border-brand-100">
        <div class="flex items-center gap-2.5">
            <span class="w-2 h-6 sm:h-7 bg-brand-600 rounded-full"></span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-brand-900">All Products</h2>
        </div>
        <span class="text-xs sm:text-sm font-semibold text-brand-700 bg-brand-50 px-3 py-1 rounded-full border border-brand-100">
            {{ count($products) }} Products
        </span>
    </div>

    @if(count($products) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-brand-100 p-12 text-center">
            <i class="fa-solid fa-box-open text-4xl text-brand-500 mb-3"></i>
            <h3 class="text-base font-bold text-gray-800">No products uploaded yet</h3>
            <p class="text-xs text-gray-500 mt-1">Upload products from the Admin Panel to display them here.</p>
        </div>
    @endif
</section>
@endsection
