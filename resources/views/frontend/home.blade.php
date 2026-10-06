@extends('layouts.app')

@section('title', 'Khati Bajar - Pure & Organic Agricultural & Farm Products')

@section('content')
<!-- TOP HERO SECTION (3-Column Layout: Categories Sidebar + Main Hero & Combo Slider + Welcome & Promo Widget) -->
<section class="py-4 bg-slate-100/70 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            
            <!-- LEFT COLUMN (3 Cols): Categories Sidebar -->
            <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4 flex flex-col justify-between">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-sm md:text-base pb-3 border-b border-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-bars text-brand-600"></i> Categories
                    </h3>
                    <div class="mt-3 space-y-1 overflow-y-auto max-h-[460px] pr-1">
                        @foreach($categories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-brand-50 hover:text-brand-700 transition group text-xs md:text-sm font-semibold text-gray-700">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-brand-600 group-hover:text-white transition">
                                        @if($cat->image)
                                            <img src="{{ asset('storage/' . $cat->image) }}" class="w-4 h-4 object-cover rounded">
                                        @else
                                            <i class="fa-solid fa-prescription-bottle-medical text-xs"></i>
                                        @endif
                                    </div>
                                    <span class="truncate">{{ $cat->name }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MIDDLE COLUMN (6 Cols): Main Hero Slider & Sub-Hero Combo Track -->
            <div class="lg:col-span-6 space-y-4">
                @php
                    $slides = isset($heroBanners) && count($heroBanners) > 0 ? $heroBanners : collect([
                        (object)[
                            'badge_text' => '100% Genuine Farm Products',
                            'title' => 'Khati Bajar — Pure Agricultural Supplies',
                            'subtitle' => 'Trusted digital marketplace for livestock medicines & feeds.',
                            'background_image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1000&auto=format&fit=crop',
                            'image' => null,
                            'button_text' => 'Shop Now',
                            'button_link' => route('products.index')
                        ],
                        (object)[
                            'badge_text' => 'Special Combo Package',
                            'title' => 'CFC Plus Combo Feed Concentrate',
                            'subtitle' => 'Boost animal immunity, milk production, and overall farm profitability.',
                            'background_image' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1000&auto=format&fit=crop',
                            'image' => null,
                            'button_text' => 'View Deals',
                            'button_link' => route('category.show', 'cfc-plus-combo')
                        ]
                    ]);
                @endphp

                <!-- Hero Banner Slider -->
                <div 
                    x-data="{ 
                        activeSlide: 0, 
                        slidesCount: {{ count($slides) }}, 
                        timer: null,
                        startTimer() {
                            if (this.slidesCount <= 1) return;
                            this.timer = setInterval(() => {
                                this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                            }, 3500);
                        },
                        stopTimer() { clearInterval(this.timer); }
                    }"
                    x-init="startTimer()"
                    @mouseenter="stopTimer()"
                    @mouseleave="startTimer()"
                    class="relative bg-gradient-to-r from-brand-900 via-brand-800 to-brand-900 rounded-2xl overflow-hidden shadow-sm text-white h-52 sm:h-60 flex items-center w-full"
                >
                    @foreach($slides as $slideIndex => $slide)
                        <div 
                            x-show="activeSlide === {{ $slideIndex }}" 
                            x-transition:enter="transition ease-out duration-500 transform" 
                            x-transition:enter-start="opacity-0 translate-x-8" 
                            x-transition:enter-end="opacity-100 translate-x-0" 
                            class="absolute inset-0 p-5 md:p-6 flex flex-col justify-center bg-cover bg-center" 
                            style="background-image: linear-gradient(to right, rgba(20, 83, 45, 0.92), rgba(20, 83, 45, 0.55)), url('{{ $slide->image ? asset('storage/' . $slide->image) : ($slide->background_image ?? 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1000&auto=format&fit=crop') }}');"
                        >
                            @if($slide->badge_text)
                                <span class="inline-block bg-amber-500 text-slate-900 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider mb-2 w-fit shadow-sm">
                                    <i class="fa-solid fa-star mr-1 text-[9px]"></i> {{ $slide->badge_text }}
                                </span>
                            @endif

                            <h2 class="text-lg sm:text-xl md:text-2xl font-extrabold leading-tight mb-1.5 max-w-md">
                                {{ $slide->title }}
                            </h2>

                            @if($slide->subtitle)
                                <p class="text-brand-100 text-xs mb-3 max-w-sm line-clamp-2">
                                    {{ $slide->subtitle }}
                                </p>
                            @endif

                            @if($slide->button_text)
                                <a href="{{ $slide->button_link ?? route('products.index') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold px-4 py-2 rounded-xl text-xs w-fit transition flex items-center gap-1.5 shadow-md">
                                    <span>{{ $slide->button_text }}</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach

                    <!-- Hero Navigation Dots -->
                    <div class="absolute bottom-3 right-4 flex gap-1.5 z-10">
                        <template x-for="i in slidesCount" :key="i">
                            <button @click="activeSlide = i-1" :class="activeSlide === i-1 ? 'bg-amber-400 w-5' : 'bg-white/50 w-2'" class="h-1.5 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (3 Cols): User Welcome Box & Mini Combo Offer Widget -->
            <div class="lg:col-span-3 space-y-4 flex flex-col justify-between">
                
                <!-- Welcome Widget -->
                <div class="bg-white rounded-2xl border border-gray-200/80 p-4 shadow-sm text-center">
                    <div class="w-14 h-14 bg-gradient-to-tr from-gray-100 to-gray-200 text-gray-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-2 border border-gray-100 shadow-inner">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 text-xs md:text-sm mb-3">Welcome</h4>
                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2 px-4 rounded-xl shadow-sm transition block text-center">
                            ড্যাশবোর্ডে যান
                        </a>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('register') }}" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2 rounded-xl transition text-center shadow-sm">
                                Join
                            </a>
                            <a href="{{ route('login') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs py-2 rounded-xl transition text-center border border-gray-200">
                                Sign in
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Side Combo Offer Mini Preview Cards -->
                <div class="bg-amber-500 p-2.5 rounded-2xl border border-amber-400 shadow-sm space-y-2">
                    <span class="bg-slate-900 text-amber-400 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider block w-fit mx-auto shadow-sm">
                        BIG COMBO OFFER
                    </span>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach($superDeals->take(2) as $dealCard)
                            <a href="{{ route('products.show', $dealCard->slug) }}" class="bg-white p-2 rounded-xl text-center border border-amber-200 shadow-2xl block hover:scale-105 transition-transform">
                                <img src="{{ asset('storage/' . $dealCard->image) }}" class="w-12 h-12 object-contain mx-auto mb-1">
                                <span class="text-[10px] font-extrabold text-gray-900 truncate block">{{ format_price($dealCard->effective_price) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

            <!-- SUB-HERO SLIDER (Horizontal Row of 6 Cards - 100% Admin Controlled via Admin -> Combo Offer Cards) -->
            @php
                $displayComboOffers = isset($comboOffers) && count($comboOffers) > 0 ? $comboOffers : $superDeals;
            @endphp
            <div 
                x-data="{ 
                    subIndex: 0, 
                    totalSlides: {{ ceil(count($displayComboOffers) / 6) > 0 ? ceil(count($displayComboOffers) / 6) : 1 }},
                    subTimer: null,
                    nextSlide() {
                        this.subIndex = (this.subIndex + 1) % this.totalSlides;
                    },
                    startSubTimer() {
                        if (this.totalSlides <= 1) return;
                        this.subTimer = setInterval(() => { this.nextSlide(); }, 3500);
                    },
                    stopSubTimer() { clearInterval(this.subTimer); }
                }"
                x-init="startSubTimer()"
                @mouseenter="stopSubTimer()"
                @mouseleave="startSubTimer()"
                class="bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-500 p-2.5 sm:p-3 rounded-2xl shadow-lg border border-amber-300 relative overflow-hidden"
            >
                <!-- Smooth Horizontal Sliding Track -->
                <div class="relative min-h-[185px] overflow-hidden">
                    @php
                        $dealChunks = $displayComboOffers->chunk(6);
                    @endphp

                    <div 
                        class="flex transition-transform duration-700 ease-in-out w-full"
                        :style="`transform: translateX(-${subIndex * 100}%);`"
                    >
                        @foreach($dealChunks as $chunkIndex => $dealChunk)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2.5 w-full shrink-0">
                                @foreach($dealChunk as $deal)
                                    @php
                                        $isModel = $deal instanceof \App\Models\ComboOffer;
                                        $link = $isModel ? ($deal->link ?? ($deal->product ? route('products.show', $deal->product->slug) : route('products.index'))) : route('products.show', $deal->slug);
                                        $imageSrc = $deal->image ? (filter_var($deal->image, FILTER_VALIDATE_URL) ? $deal->image : asset('storage/' . $deal->image)) : 'https://placehold.co/200x200?text=Khati+Bajar';
                                        $badgeText = $isModel ? ($deal->badge_text ?? 'BIG COMBO OFFER') : 'BIG COMBO OFFER';
                                        $offerText = $isModel ? ($deal->offer_text ?? ('৳ ' . number_format($deal->price))) : ($deal->short_description ?: format_price($deal->effective_price));
                                        $priceText = $isModel ? ('৳ ' . number_format($deal->price)) : format_price($deal->effective_price);
                                    @endphp
                                    <a href="{{ $link }}" class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-between text-center group border border-amber-100 transform hover:-translate-y-1 overflow-hidden relative pb-1.5 pt-0 px-1 min-h-[180px]">
                                        <!-- Top Red Offer Badge Banner -->
                                        <div class="bg-red-600 text-white text-[9px] font-black py-0.5 px-0.5 rounded-b-md w-full text-center uppercase tracking-tighter flex items-center justify-center gap-0.5 shadow-sm">
                                            <i class="fa-solid fa-fire text-yellow-300 text-[8px] animate-flame"></i> {{ $badgeText }} <i class="fa-solid fa-fire text-yellow-300 text-[8px] animate-flame"></i>
                                        </div>

                                        <!-- Product Image with Round BIG OFFER Badge overlay -->
                                        <div class="h-20 w-full flex items-center justify-center overflow-hidden my-1 relative px-1">
                                            <img src="{{ $imageSrc }}" alt="{{ $deal->title ?? $deal->name }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                            <!-- Round Red Big Offer Badge -->
                                            <div class="absolute bottom-0 right-0 bg-red-600 text-white text-[7px] font-black rounded-full w-6 h-6 flex flex-col items-center justify-center leading-none shadow-md border border-white animate-pulse-glow">
                                                <span>BIG</span>
                                                <span class="text-[5px]">OFFER</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Red Offer Text Bar -->
                                        <div class="w-full bg-red-600 text-white text-[9px] font-black py-0.5 px-1 rounded-sm my-1 truncate shadow-inner">
                                            {{ $offerText }}
                                        </div>

                                        <!-- Gradient Price Pill Tag -->
                                        <div class="w-full mt-auto">
                                            <span class="bg-gradient-to-r from-lime-500 via-emerald-500 to-amber-500 text-white font-extrabold text-xs py-1 px-2 rounded-full block shadow-sm group-hover:brightness-110 transition tracking-tight">
                                                {{ $priceText }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Active Dot Navigation at Bottom -->
                @if(ceil(count($displayComboOffers) / 6) > 1)
                <div class="flex justify-center items-center gap-1.5 mt-2.5">
                    <template x-for="i in totalSlides" :key="i">
                        <button @click="subIndex = i-1" :class="subIndex === i-1 ? 'bg-blue-600 scale-125' : 'bg-white/70 hover:bg-white'" class="w-2.5 h-2.5 rounded-full transition-all duration-300 shadow-sm"></button>
                    </template>
                </div>
                @endif
            </div>

        </div>

    </div>
</section>

<!-- BELOW HERO SECTION: Welcome User Card + One-by-One Item Carousel -->
<section class="py-6 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
        
        <!-- Welcome User Box (Moved Below Hero Section) -->
        <div class="md:col-span-4 bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 text-white rounded-2xl shadow-md p-6 flex flex-col items-center text-center relative overflow-hidden">
            <div class="w-16 h-16 bg-white/20 text-white rounded-full flex items-center justify-center text-3xl mb-3 shadow-inner backdrop-blur-sm">
                <i class="fa-solid fa-user-astronaut"></i>
            </div>
            
            @auth
                <span class="text-xs font-semibold text-amber-100 uppercase tracking-wider">Welcome back,</span>
                <h4 class="font-extrabold text-white text-lg mb-3">{{ auth()->user()->name }}</h4>
                <a href="{{ route('customer.dashboard') }}" class="w-full bg-white text-slate-900 hover:bg-amber-100 font-extrabold text-xs py-3 rounded-xl shadow-md transition">
                    My Account Dashboard
                </a>
            @else
                <span class="text-xs font-semibold text-amber-100 uppercase tracking-wider">Welcome to Khati Bajar</span>
                <h4 class="font-extrabold text-white text-lg mb-4">Farmer Digital Marketplace</h4>
                <div class="grid grid-cols-2 gap-3 w-full">
                    <a href="{{ route('register') }}" class="bg-white text-slate-900 hover:bg-amber-100 font-extrabold text-xs py-2.5 rounded-xl shadow-md transition text-center">
                        Join Now
                    </a>
                    <a href="{{ route('login') }}" class="bg-slate-900/40 hover:bg-slate-900/60 text-white border border-white/30 font-bold text-xs py-2.5 rounded-xl transition text-center">
                        Sign In
                    </a>
                </div>
            @endauth
        </div>

        <!-- Featured Products ONE-BY-ONE Single Item Carousel (Moved Below Hero Section) -->
        <div class="md:col-span-8 bg-slate-50 rounded-2xl border border-gray-200 p-5 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-brand-600 rounded-full"></span>
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-star text-amber-500"></i> Featured Spotlight (Single Item Slide)
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Slides 1 by 1</span>
                </div>
            </div>

            <!-- Single Item Step Carousel -->
            @php
                $carouselItems = count($featuredProducts) > 0 ? $featuredProducts : $popularProducts;
            @endphp
            <div 
                x-data="{ 
                    itemIndex: 0, 
                    totalItems: {{ count($carouselItems) }},
                    cTimer: null,
                    nextItem() {
                        this.itemIndex = (this.itemIndex + 1) % this.totalItems;
                    },
                    prevItem() {
                        this.itemIndex = (this.itemIndex - 1 + this.totalItems) % this.totalItems;
                    },
                    startCTimer() {
                        if (this.totalItems <= 1) return;
                        this.cTimer = setInterval(() => { this.nextItem(); }, 2500);
                    },
                    stopCTimer() { clearInterval(this.cTimer); }
                }"
                x-init="startCTimer()"
                @mouseenter="stopCTimer()"
                @mouseleave="startCTimer()"
                class="relative"
            >
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-4">
                    @foreach($carouselItems as $idx => $fp)
                        <div 
                            x-show="itemIndex === {{ $idx }}" 
                            x-transition:enter="transition ease-out duration-500 transform" 
                            x-transition:enter-start="opacity-0 translate-x-8" 
                            x-transition:enter-end="opacity-100 translate-x-0" 
                            class="flex flex-col sm:flex-row items-center gap-4"
                        >
                            <div class="w-32 h-32 shrink-0 bg-gray-50 rounded-xl overflow-hidden p-2 border border-gray-100 flex items-center justify-center">
                                <img src="{{ $fp->image ? (filter_var($fp->image, FILTER_VALIDATE_URL) ? $fp->image : asset('storage/' . $fp->image)) : 'https://placehold.co/200x200?text=Khati+Bajar' }}" alt="{{ $fp->name }}" class="max-h-full max-w-full object-contain">
                            </div>

                            <div class="flex-1 text-center sm:text-left space-y-1">
                                <span class="bg-brand-50 text-brand-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $fp->category->name ?? 'Farm Product' }}
                                </span>
                                <h4 class="font-extrabold text-gray-900 text-base line-clamp-1">
                                    {{ $fp->name }}
                                </h4>
                                <p class="text-xs text-gray-500 line-clamp-2">
                                    {{ $fp->short_description ?: '100% genuine quality product guaranteed directly from suppliers.' }}
                                </p>
                                <div class="flex items-center justify-center sm:justify-start gap-3 pt-2">
                                    <span class="text-lg font-black text-brand-700">
                                        {{ format_price($fp->effective_price) }}
                                    </span>
                                    @if($fp->sale_price && $fp->sale_price < $fp->regular_price)
                                        <span class="text-xs text-gray-400 line-through">
                                            {{ format_price($fp->regular_price) }}
                                        </span>
                                    @endif
                                    <a href="{{ route('products.show', $fp->slug) }}" class="ml-auto bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-md">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Left / Right Carousel Control Buttons -->
                <button @click="prevItem()" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 shadow-md border border-gray-200 rounded-full flex items-center justify-center text-gray-700 hover:bg-brand-600 hover:text-white transition">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button @click="nextItem()" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 shadow-md border border-gray-200 rounded-full flex items-center justify-center text-gray-700 hover:bg-brand-600 hover:text-white transition">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>

    </div>
</section>

<!-- Trust Badges -->
<section class="bg-white border-b border-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div class="flex items-center justify-center gap-3.5 p-3 group">
            <div class="w-12 h-12 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-gray-900 text-sm group-hover:text-brand-600 transition">Fastest Delivery</h4>
                <p class="text-xs text-gray-500">Home delivery nationwide</p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3.5 p-3 group">
            <div class="w-12 h-12 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-shield-check"></i>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-gray-900 text-sm group-hover:text-brand-600 transition">100% Genuine Products</h4>
                <p class="text-xs text-gray-500">Quality guaranteed</p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3.5 p-3 group">
            <div class="w-12 h-12 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-gray-900 text-sm group-hover:text-brand-600 transition">Cash on Delivery</h4>
                <p class="text-xs text-gray-500">Pay when you receive your order</p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3.5 p-3 group">
            <div class="w-12 h-12 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-gray-900 text-sm group-hover:text-brand-600 transition">24/7 Support</h4>
                <p class="text-xs text-gray-500">Call us for any inquiries</p>
            </div>
        </div>
    </div>
</section>

<!-- Super Offer Deals Section -->
@if(count($superDeals) > 0)
<section class="py-12 bg-amber-50/60 border-y border-amber-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-amber-500 text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-md animate-flame">
                    <i class="fa-solid fa-fire text-amber-100"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Super Offer Products</h2>
                    <p class="text-xs md:text-sm text-gray-600">Limited Time Special Discount</p>
                </div>
            </div>

            <!-- Ticking Live Countdown Component -->
            <div 
                x-data="{ 
                    hours: 4, 
                    minutes: 59, 
                    seconds: 59, 
                    ms: 99,
                    startTimer() {
                        setInterval(() => {
                            this.ms--;
                            if (this.ms < 0) {
                                this.ms = 99;
                                this.seconds--;
                                if (this.seconds < 0) {
                                    this.seconds = 59;
                                    this.minutes--;
                                    if (this.minutes < 0) {
                                        this.minutes = 59;
                                        this.hours--;
                                        if (this.hours < 0) {
                                            this.hours = 24;
                                        }
                                    }
                                }
                            }
                        }, 10);
                    }
                }"
                x-init="startTimer()"
                class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-2xl shadow-lg border border-amber-500/40 w-fit"
            >
                <i class="fa-solid fa-clock text-amber-400 text-sm animate-pulse"></i>
                <span class="text-xs font-bold text-amber-300 uppercase tracking-wider mr-1">Deals End In:</span>
                <div class="flex items-center gap-1 font-mono font-black text-xs md:text-sm">
                    <span class="bg-amber-500 text-slate-900 px-2 py-0.5 rounded-lg" x-text="String(hours).padStart(2, '0')">04</span>
                    <span class="text-amber-400 font-bold">:</span>
                    <span class="bg-amber-500 text-slate-900 px-2 py-0.5 rounded-lg" x-text="String(minutes).padStart(2, '0')">59</span>
                    <span class="text-amber-400 font-bold">:</span>
                    <span class="bg-amber-500 text-slate-900 px-2 py-0.5 rounded-lg" x-text="String(seconds).padStart(2, '0')">59</span>
                    <span class="text-amber-400 font-bold">:</span>
                    <span class="bg-red-600 text-white px-1.5 py-0.5 rounded-lg text-xs" x-text="String(ms).padStart(2, '0')">99</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($superDeals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
<section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Featured Products</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Top-rated items most loved by farmers</p>
        </div>
        <a href="{{ route('products.index') }}" class="group text-brand-600 font-bold text-sm hover:underline flex items-center gap-1.5">
            <span>View All Products</span>
            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>

<!-- Popular Products Grid -->
<section class="py-12 bg-gray-100/70 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Popular Products</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Best-selling farm supplies</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($popularProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div>
        <h2 class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3">Why Shop at Khati Bajar?</h2>
        <p class="text-sm text-gray-500 max-w-2xl mx-auto mb-12">We are committed to providing the highest service to farmers and delivering products of the right quality.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 text-center transform hover:-translate-y-1.5 group">
            <div class="w-16 h-16 bg-green-100 text-green-700 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 group-hover:scale-110 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-vial-circle-check"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-green-700 transition">100% Pure & Genuine</h3>
            <p class="text-xs md:text-sm text-gray-500 leading-relaxed">All our vitamins, minerals and medicines are sourced directly from manufacturers or approved sources.</p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 text-center transform hover:-translate-y-1.5 group">
            <div class="w-16 h-16 bg-amber-100 text-amber-700 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-amber-700 transition">Fair Prices</h3>
            <p class="text-xs md:text-sm text-gray-500 leading-relaxed">Considering the economic savings of farmers, we always offer the best prices in the market.</p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 text-center transform hover:-translate-y-1.5 group">
            <div class="w-16 h-16 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-blue-700 transition">Fast Delivery Service</h3>
            <p class="text-xs md:text-sm text-gray-500 leading-relaxed">After order confirmation, we guarantee fast delivery to your district or sub-district.</p>
        </div>
    </div>
</section>
@endsection
