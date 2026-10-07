@extends('layouts.app')

@section('title', 'Khati Bajar - Pure & Organic Agricultural & Farm Products')

@section('content')
<!-- HERO & CATEGORY SECTION (100% Natural Green & White Theme) -->
<section class="pt-3 pb-4 sm:py-6 bg-brand-50/40 border-b border-brand-100">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            
            <!-- LEFT COLUMN (Desktop Only - 3 Cols): Clean Categories Sidebar -->
            <div class="hidden lg:flex lg:col-span-3 bg-white rounded-2xl border border-brand-100 shadow-xs p-4 flex-col justify-between h-[360px]">
                <div class="flex flex-col h-full">
                    <h3 class="font-extrabold text-brand-900 text-sm pb-3 border-b border-brand-100 flex items-center gap-2 shrink-0">
                        <i class="fa-solid fa-leaf text-brand-600"></i> Browse Categories
                    </h3>
                    <div class="mt-2.5 space-y-1 overflow-y-auto flex-1 pr-1">
                        @foreach($categories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-brand-50 hover:text-brand-700 transition group text-xs font-semibold text-gray-700">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-brand-600 group-hover:text-white transition">
                                        @if($cat->image)
                                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="w-4 h-4 object-cover rounded">
                                        @else
                                            <i class="fa-solid fa-seedling text-xs"></i>
                                        @endif
                                    </div>
                                    <span class="truncate">{{ $cat->name }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] text-brand-500/60 group-hover:translate-x-1 transition-transform shrink-0"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MAIN HERO SLIDER (Full width on mobile, 9 Cols on Desktop) -->
            <div class="lg:col-span-9">
                @php
                    $slides = isset($heroBanners) && count($heroBanners) > 0 ? $heroBanners : collect([
                        (object)[
                            'badge_text' => '100% Pure & Natural',
                            'title' => 'Khati Bajar — Pure Agricultural Supplies',
                            'subtitle' => 'Trusted digital marketplace for livestock medicines, fish supplements & organic farm feeds.',
                            'background_image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1200&auto=format&fit=crop',
                            'image' => null,
                            'button_text' => 'Shop Now',
                            'button_link' => route('products.index')
                        ],
                        (object)[
                            'badge_text' => 'Special Combo Package',
                            'title' => 'CFC Plus Combo Feed Concentrate',
                            'subtitle' => 'Boost animal immunity, milk production, and overall farm profitability.',
                            'background_image' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1200&auto=format&fit=crop',
                            'image' => null,
                            'button_text' => 'View Deals',
                            'button_link' => route('category.show', 'cfc-plus-combo')
                        ]
                    ]);
                @endphp

                <div 
                    x-data="{ 
                        activeSlide: 0, 
                        slidesCount: {{ count($slides) }}, 
                        timer: null,
                        next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount; },
                        prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount; },
                        startTimer() {
                            if (this.slidesCount <= 1) return;
                            this.timer = setInterval(() => { this.next(); }, 4500);
                        },
                        stopTimer() { clearInterval(this.timer); }
                    }"
                    x-init="startTimer()"
                    @mouseenter="stopTimer()"
                    @mouseleave="startTimer()"
                    class="relative bg-brand-900 rounded-2xl overflow-hidden shadow-sm text-white h-[210px] sm:h-[290px] lg:h-[360px] flex items-center w-full group"
                >
                    @foreach($slides as $slideIndex => $slide)
                        <div 
                            x-show="activeSlide === {{ $slideIndex }}" 
                            x-transition:enter="transition ease-out duration-500 transform" 
                            x-transition:enter-start="opacity-0 scale-105" 
                            x-transition:enter-end="opacity-100 scale-100" 
                            class="absolute inset-0 p-5 sm:p-8 lg:p-12 flex flex-col justify-center bg-cover bg-center" 
                            style="background-image: linear-gradient(to right, rgba(20, 83, 45, 0.94) 0%, rgba(22, 101, 52, 0.78) 55%, rgba(20, 83, 45, 0.30) 100%), url('{{ $slide->image ? asset('storage/' . $slide->image) : ($slide->background_image ?? 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1200&auto=format&fit=crop') }}');"
                        >
                            @if($slide->badge_text)
                                <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-xs border border-white/30 text-white text-[10px] sm:text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider mb-2 sm:mb-3 w-fit">
                                    <i class="fa-solid fa-leaf text-[9px] sm:text-[10px]"></i> {{ $slide->badge_text }}
                                </span>
                            @endif

                            <h2 class="text-lg sm:text-2xl lg:text-4xl font-extrabold leading-tight mb-1.5 sm:mb-3 max-w-xl text-white">
                                {{ $slide->title }}
                            </h2>

                            @if($slide->subtitle)
                                <p class="text-brand-50 text-xs sm:text-sm lg:text-base mb-3.5 sm:mb-6 max-w-lg line-clamp-2 leading-relaxed">
                                    {{ $slide->subtitle }}
                                </p>
                            @endif

                            @if($slide->button_text)
                                <a href="{{ $slide->button_link ?? route('products.index') }}" class="bg-white hover:bg-brand-50 text-brand-800 font-extrabold px-4 sm:px-6 py-2 sm:py-3 rounded-xl text-xs sm:text-sm w-fit transition flex items-center gap-2 shadow-md">
                                    <span>{{ $slide->button_text }}</span>
                                    <i class="fa-solid fa-arrow-right text-xs text-brand-600"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach

                    <!-- Slider Left/Right Arrows (Desktop Hover) -->
                    @if(count($slides) > 1)
                        <button type="button" @click="prev()" class="hidden sm:flex absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-brand-900/50 hover:bg-brand-800 text-white items-center justify-center opacity-0 group-hover:opacity-100 transition z-10 border border-white/20">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" @click="next()" class="hidden sm:flex absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-brand-900/50 hover:bg-brand-800 text-white items-center justify-center opacity-0 group-hover:opacity-100 transition z-10 border border-white/20">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>

                        <!-- Hero Navigation Dots -->
                        <div class="absolute bottom-3 right-4 sm:bottom-4 sm:right-6 flex gap-1.5 z-10">
                            <template x-for="i in slidesCount" :key="i">
                                <button type="button" @click="activeSlide = i-1" :class="activeSlide === i-1 ? 'bg-white w-6' : 'bg-white/40 w-2'" class="h-1.5 sm:h-2 rounded-full transition-all duration-300"></button>
                            </template>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- MOBILE & TABLET HORIZONTAL CATEGORY BAR -->
        <div class="lg:hidden mt-3.5">
            <div class="flex items-center gap-2 overflow-x-auto pb-1.5 no-scrollbar">
                <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 bg-brand-700 text-white px-3 py-2 rounded-xl text-xs font-bold shrink-0 shadow-xs">
                    <i class="fa-solid fa-leaf text-[11px]"></i>
                    <span>All Items</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center gap-2 bg-white border border-brand-100 hover:border-brand-600 text-brand-900 px-3 py-1.5 rounded-xl text-xs font-semibold shrink-0 shadow-2xs transition">
                        @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="w-4 h-4 object-cover rounded">
                        @else
                            <i class="fa-solid fa-seedling text-brand-600 text-[11px]"></i>
                        @endif
                        <span class="whitespace-nowrap">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
</section>

<!-- CLEAN NATURAL TRUST FEATURES BAR -->
<section class="bg-white border-b border-brand-100/70 py-4 sm:py-6">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
            <div class="flex items-center gap-2.5 sm:gap-3.5 p-2.5 sm:p-3.5 rounded-xl bg-brand-50/40 border border-brand-100">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-600 text-white rounded-xl flex items-center justify-center text-base sm:text-lg shrink-0">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="font-bold text-brand-900 text-xs sm:text-sm truncate">Fastest Delivery</h4>
                    <p class="text-[10px] sm:text-xs text-gray-500 truncate">Nationwide home delivery</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3.5 p-2.5 sm:p-3.5 rounded-xl bg-brand-50/40 border border-brand-100">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-600 text-white rounded-xl flex items-center justify-center text-base sm:text-lg shrink-0">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="font-bold text-brand-900 text-xs sm:text-sm truncate">100% Pure &amp; Natural</h4>
                    <p class="text-[10px] sm:text-xs text-gray-500 truncate">Quality guaranteed</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3.5 p-2.5 sm:p-3.5 rounded-xl bg-brand-50/40 border border-brand-100">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-600 text-white rounded-xl flex items-center justify-center text-base sm:text-lg shrink-0">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="font-bold text-brand-900 text-xs sm:text-sm truncate">Cash on Delivery</h4>
                    <p class="text-[10px] sm:text-xs text-gray-500 truncate">Pay upon receiving</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3.5 p-2.5 sm:p-3.5 rounded-xl bg-brand-50/40 border border-brand-100">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-600 text-white rounded-xl flex items-center justify-center text-base sm:text-lg shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="font-bold text-brand-900 text-xs sm:text-sm truncate">24/7 Helpline</h4>
                    <p class="text-[10px] sm:text-xs text-gray-500 truncate">Dedicated farmer support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CLEAN COMBO & SPECIAL PACKAGES STRIP (Natural Green & White) -->
@php
    $displayComboOffers = isset($comboOffers) && count($comboOffers) > 0 ? $comboOffers : $superDeals;
@endphp
@if(count($displayComboOffers) > 0)
<section class="py-7 sm:py-10 bg-white border-b border-brand-100/70">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-6 sm:h-7 bg-brand-600 rounded-full"></span>
                <div>
                    <h2 class="text-base sm:text-xl md:text-2xl font-extrabold text-brand-900 flex items-center gap-2">
                        <span>Combo &amp; Special Packages</span>
                        <span class="bg-brand-100 text-brand-800 text-[10px] sm:text-xs font-bold px-2.5 py-0.5 rounded-full">Farm Value</span>
                    </h2>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="text-brand-700 hover:text-brand-800 font-bold text-xs sm:text-sm flex items-center gap-1">
                <span>See All</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-4">
            @foreach($displayComboOffers->take(6) as $deal)
                @php
                    $isModel = $deal instanceof \App\Models\ComboOffer;
                    $link = $isModel ? ($deal->link ?? ($deal->product ? route('products.show', $deal->product->slug) : route('products.index'))) : route('products.show', $deal->slug);
                    $imageSrc = $deal->image_url;
                    $titleText = $isModel ? ($deal->title ?? ($deal->product->name ?? 'Combo Package')) : $deal->name;
                    $badgeText = $isModel ? ($deal->badge_text ?? 'Combo Offer') : 'Combo Pack';
                    $priceText = $isModel ? ('৳ ' . number_format($deal->price)) : format_price($deal->effective_price);
                @endphp
                <a href="{{ $link }}" class="bg-brand-50/30 hover:bg-white rounded-xl sm:rounded-2xl p-2.5 sm:p-3.5 border border-brand-100 hover:border-brand-600 hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="bg-brand-600 text-white text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-tight truncate">
                                {{ $badgeText }}
                            </span>
                        </div>
                        <div class="h-24 sm:h-28 w-full bg-white rounded-xl flex items-center justify-center p-2 mb-2.5 border border-brand-100/80 overflow-hidden">
                            <img src="{{ $imageSrc }}" alt="{{ $titleText }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                        <h3 class="text-xs font-bold text-gray-800 line-clamp-2 group-hover:text-brand-700 transition mb-2 leading-snug">
                            {{ $titleText }}
                        </h3>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-brand-100">
                        <span class="text-xs sm:text-sm font-extrabold text-brand-700">{{ $priceText }}</span>
                        <span class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 group-hover:bg-brand-600 group-hover:text-white flex items-center justify-center text-[10px] transition">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- SUPER OFFER DEALS SECTION (Natural Mint Green Background) -->
@if(count($superDeals) > 0)
<section class="py-8 sm:py-12 bg-brand-50/50 border-b border-brand-100">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 sm:mb-8">
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-600 text-white rounded-xl sm:rounded-2xl flex items-center justify-center font-bold text-base sm:text-xl shadow-xs">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-brand-900">Special Farm Offers</h2>
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-600">Pure &amp; organic products at special discounted prices</p>
                </div>
            </div>

            <!-- Clean Natural Green Live Countdown -->
            <div 
                x-data="{ 
                    hours: 4, 
                    minutes: 59, 
                    seconds: 59,
                    startTimer() {
                        setInterval(() => {
                            this.seconds--;
                            if (this.seconds < 0) {
                                this.seconds = 59;
                                this.minutes--;
                                if (this.minutes < 0) {
                                    this.minutes = 59;
                                    this.hours = this.hours > 0 ? this.hours - 1 : 23;
                                }
                            }
                        }, 1000);
                    }
                }"
                x-init="startTimer()"
                class="flex items-center gap-2 bg-brand-800 text-white px-3.5 py-2 rounded-xl shadow-xs w-fit"
            >
                <i class="fa-regular fa-clock text-brand-100 text-xs"></i>
                <span class="text-[11px] font-bold text-brand-100 uppercase tracking-wider">Offer Ends:</span>
                <div class="flex items-center gap-1 font-mono font-bold text-xs">
                    <span class="bg-white text-brand-800 px-1.5 py-0.5 rounded" x-text="String(hours).padStart(2, '0')">04</span>
                    <span class="text-white">:</span>
                    <span class="bg-white text-brand-800 px-1.5 py-0.5 rounded" x-text="String(minutes).padStart(2, '0')">59</span>
                    <span class="text-white">:</span>
                    <span class="bg-white text-brand-800 px-1.5 py-0.5 rounded" x-text="String(seconds).padStart(2, '0')">59</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($superDeals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FEATURED PRODUCTS SECTION -->
<section class="py-8 sm:py-12 max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <div>
            <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-brand-900">Featured Products</h2>
            <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 mt-0.5">Top-rated natural supplies most loved by farmers</p>
        </div>
        <a href="{{ route('products.index') }}" class="group text-brand-700 font-bold text-xs sm:text-sm hover:underline flex items-center gap-1.5 shrink-0">
            <span>View All</span>
            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        @foreach($featuredProducts as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>

<!-- POPULAR PRODUCTS GRID -->
<section class="py-8 sm:py-12 bg-brand-50/30 border-t border-brand-100">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h2 class="text-lg sm:text-2xl md:text-3xl font-extrabold text-brand-900">Popular Products</h2>
                <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 mt-0.5">Best-selling farm &amp; livestock supplies</p>
            </div>
            <a href="{{ route('products.index') }}" class="group text-brand-700 font-bold text-xs sm:text-sm hover:underline flex items-center gap-1.5 shrink-0">
                <span>Explore All</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($popularProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- WHY CHOOSE US (100% Natural Green & White Cards) -->
<section class="py-10 sm:py-16 max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 text-center">
    <div>
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-brand-900 mb-2">Why Shop at Khati Bajar?</h2>
        <p class="text-xs sm:text-sm text-gray-500 max-w-2xl mx-auto mb-8 sm:mb-12">We are committed to providing 100% pure, natural agricultural products and the highest service to farmers.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-8">
        <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-brand-100 shadow-xs hover:shadow-md transition-all duration-300 text-center group">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-brand-50 text-brand-700 rounded-2xl flex items-center justify-center text-xl sm:text-2xl mx-auto mb-4 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <h3 class="font-bold text-base sm:text-lg text-brand-900 mb-1.5">100% Pure &amp; Genuine</h3>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">All our vitamins, minerals and medicines are sourced directly from manufacturers or approved natural sources.</p>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-brand-100 shadow-xs hover:shadow-md transition-all duration-300 text-center group">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-brand-50 text-brand-700 rounded-2xl flex items-center justify-center text-xl sm:text-2xl mx-auto mb-4 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-Seedling fa-seedling"></i>
            </div>
            <h3 class="font-bold text-base sm:text-lg text-brand-900 mb-1.5">Fair Farmer Prices</h3>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Considering the economic savings of farmers, we always offer the best wholesale-friendly prices in the market.</p>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-brand-100 shadow-xs hover:shadow-md transition-all duration-300 text-center group">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-brand-50 text-brand-700 rounded-2xl flex items-center justify-center text-xl sm:text-2xl mx-auto mb-4 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <h3 class="font-bold text-base sm:text-lg text-brand-900 mb-1.5">Fast Delivery Service</h3>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">After order confirmation, we guarantee fast delivery right to your district or sub-district doorstep.</p>
        </div>
    </div>
</section>
@endsection
