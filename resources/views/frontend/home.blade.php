@extends('layouts.app')

@section('title', 'Khati Bazar - Pure & Organic Agricultural & Farm Products')

@section('content')
<!-- HERO SECTION (Logo Centered + 2 Lines of Text) -->
<section class="py-8 sm:py-12 bg-gradient-to-b from-brand-800 via-brand-700 to-brand-800 text-white border-b border-brand-600/40 relative overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center justify-center">
        
        <!-- Logo in Hero Section -->
        <div class="bg-white px-8 py-5 sm:px-12 sm:py-7 rounded-3xl shadow-xl mb-5 sm:mb-6 inline-flex items-center justify-center">
            <img 
                src="{{ asset('images/hero-logo.png') }}" 
                alt="Khati Bazar BD Logo" 
                class="h-36 sm:h-48 md:h-56 lg:h-64 w-auto object-contain"
            >
        </div>

        <!-- 2 Lines of Text Below Logo -->
        <h1 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight max-w-2xl leading-snug">
            খাঁটি স্বাদ, বিশুদ্ধতার প্রতিশ্রুতি।
        </h1>
        <p class="text-xs sm:text-sm md:text-base text-brand-100 mt-1.5 max-w-xl leading-relaxed">
            প্রকৃতির সেরা উপাদান থেকে বেছে নেওয়া খাঁটি ও মানসম্মত খাবার—আপনার ঘরের জন্য।
        </p>

    </div>
</section>

<!-- COMBO OFFERS 1-BY-1 CAROUSEL SECTION (Directly Below Hero Section) -->
@if(isset($comboOffers) && count($comboOffers) > 0)
<section id="combo-offers" class="py-6 sm:py-8 bg-brand-50/60 border-b border-brand-100">
    <div class="max-w-[1000px] mx-auto px-3 sm:px-6 lg:px-8">
        <div 
            x-data="{
                activeSlide: 0,
                totalSlides: {{ count($comboOffers) }},
                timer: null,
                start() {
                    if (this.totalSlides > 1) {
                        this.timer = setInterval(() => { this.next(); }, 3500);
                    }
                },
                stop() {
                    if (this.timer) clearInterval(this.timer);
                },
                next() {
                    this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                },
                prev() {
                    this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                }
            }"
            x-init="start()"
            @mouseenter="stop()"
            @mouseleave="start()"
            class="relative"
        >
            <!-- Carousel Viewport -->
            <div class="overflow-hidden rounded-2xl sm:rounded-3xl bg-white border-2 border-brand-200 shadow-lg">
                <div 
                    class="flex transition-transform duration-500 ease-in-out"
                    :style="'transform: translateX(-' + (activeSlide * 100) + '%)'"
                >
                    @foreach($comboOffers as $offer)
                        @php
                            $offerUrl = $offer->product ? route('products.show', $offer->product->slug) : ($offer->link ?: route('products.index'));
                        @endphp
                        <!-- Single Combo Slide (1 by 1) -->
                        <div class="w-full shrink-0 p-4 sm:p-6 md:p-8">
                            <a href="{{ $offerUrl }}" class="flex flex-row items-center gap-4 sm:gap-8 group">
                                <!-- Combo Image -->
                                <div class="w-28 h-28 sm:w-40 sm:h-40 md:w-48 md:h-48 rounded-2xl bg-brand-50 border border-brand-100 p-2 sm:p-3 flex items-center justify-center shrink-0 overflow-hidden">
                                    <img 
                                        src="{{ $offer->image_url }}" 
                                        alt="{{ $offer->title }}" 
                                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
                                    >
                                </div>

                                <!-- Combo Details -->
                                <div class="flex-1 min-w-0 text-left">
                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                                        @if($offer->badge_text)
                                            <span class="bg-brand-700 text-white text-[10px] sm:text-xs font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wide">
                                                {{ $offer->badge_text }}
                                            </span>
                                        @endif
                                        @if($offer->offer_badge_text)
                                            <span class="bg-brand-100 text-brand-800 text-[10px] sm:text-xs font-bold px-2.5 py-0.5 rounded-full border border-brand-200">
                                                {{ $offer->offer_badge_text }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-base sm:text-xl md:text-2xl font-extrabold text-gray-900 group-hover:text-brand-700 transition truncate sm:whitespace-normal leading-snug">
                                        {{ $offer->title }}
                                    </h3>

                                    @if($offer->offer_text)
                                        <p class="text-xs sm:text-sm text-brand-700 font-semibold mt-1 line-clamp-2">
                                            {{ $offer->offer_text }}
                                        </p>
                                    @endif

                                    <div class="mt-3 sm:mt-4 flex flex-wrap items-center justify-between gap-2 pt-2 sm:pt-3 border-t border-brand-50">
                                        <div>
                                            <span class="text-[10px] sm:text-xs text-gray-500 block">অফার মূল্য:</span>
                                            <span class="text-lg sm:text-2xl md:text-3xl font-black text-brand-700">
                                                ৳ {{ number_format($offer->price) }}
                                            </span>
                                        </div>

                                        <span class="inline-flex items-center gap-1.5 bg-brand-600 group-hover:bg-brand-700 text-white text-xs sm:text-sm font-bold px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl shadow-sm transition">
                                            <span>অর্ডার করুন</span>
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Prev / Next Controls (if more than 1 combo offer) -->
            @if(count($comboOffers) > 1)
                <button 
                    type="button" 
                    @click="prev()" 
                    class="absolute -left-2 sm:-left-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-brand-700 hover:bg-brand-800 text-white shadow-lg flex items-center justify-center transition border-2 border-white z-10"
                    aria-label="Previous Combo"
                >
                    <i class="fa-solid fa-chevron-left text-xs sm:text-sm"></i>
                </button>
                <button 
                    type="button" 
                    @click="next()" 
                    class="absolute -right-2 sm:-right-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-brand-700 hover:bg-brand-800 text-white shadow-lg flex items-center justify-center transition border-2 border-white z-10"
                    aria-label="Next Combo"
                >
                    <i class="fa-solid fa-chevron-right text-xs sm:text-sm"></i>
                </button>

                <!-- Slide Indicators (Dots) -->
                <div class="flex items-center justify-center gap-2 mt-3.5">
                    @foreach($comboOffers as $index => $offer)
                        <button 
                            type="button" 
                            @click="activeSlide = {{ $index }}"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="activeSlide === {{ $index }} ? 'w-6 bg-brand-700' : 'w-2 bg-brand-300 hover:bg-brand-400'"
                            aria-label="Go to slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endif

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
