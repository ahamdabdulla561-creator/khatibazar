@extends('layouts.app')

@section('title', 'All Products - Khati Bajar')

@section('content')
<div class="bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Title -->
        <div class="reveal-side-left flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">All Products</h1>
                <p class="text-xs text-gray-500 mt-1">Total {{ $products->total() }} products found</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Mobile Filter Trigger -->
                <button @click="filterDrawerOpen = true" class="md:hidden bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-filter text-brand-600"></i> Filter
                </button>

                <!-- Sorting Dropdown -->
                <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                    @foreach(request()->except('sort', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <label class="text-xs font-semibold text-gray-600 hidden sm:inline">Sort by:</label>
                    <select name="sort" onchange="this.form.submit()" class="bg-white border border-gray-200 text-xs md:text-sm font-medium rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-brand-500 shadow-sm">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popularity</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8" x-data="{ filterDrawerOpen: false }">
            
            <!-- Desktop Filter Sidebar (19: Accordion & 20: Price Range Slider) -->
            <aside class="reveal-side-left hidden md:block bg-white p-6 rounded-2xl border border-gray-100 shadow-sm h-fit" x-data="{ openCat: true, openBrand: true, openPrice: true, maxPriceVal: {{ request('max_price', 15000) }} }">
                <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                    @if(request()->filled('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Categories Filter (19: Accordion) -->
                    <div class="border-b pb-4">
                        <button type="button" @click="openCat = !openCat" class="w-full font-bold text-gray-900 text-sm flex items-center justify-between py-1 focus:outline-none">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-layer-group text-brand-600 text-xs"></i> Category</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openCat }"></i>
                        </button>
                        <div x-show="openCat" x-transition:enter="transition ease-out duration-200" class="mt-3 space-y-2 max-h-48 overflow-y-auto pr-1">
                            <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-600 focus:ring-brand-500">
                                <span>All Categories</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-600 focus:ring-brand-500">
                                    <span>{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brands Filter (19: Accordion) -->
                    <div class="border-b pb-4">
                        <button type="button" @click="openBrand = !openBrand" class="w-full font-bold text-gray-900 text-sm flex items-center justify-between py-1 focus:outline-none">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-tags text-brand-600 text-xs"></i> Brand</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openBrand }"></i>
                        </button>
                        <div x-show="openBrand" x-transition:enter="transition ease-out duration-200" class="mt-3 space-y-2 max-h-40 overflow-y-auto pr-1">
                            <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                <input type="radio" name="brand" value="" {{ !request('brand') ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-600">
                                <span>All Brands</span>
                            </label>
                            @foreach($brands as $brand)
                                <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                    <input type="radio" name="brand" value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-600">
                                    <span>{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter (20: Interactive Slider Interaction) -->
                    <div class="border-b pb-4">
                        <button type="button" @click="openPrice = !openPrice" class="w-full font-bold text-gray-900 text-sm flex items-center justify-between py-1 focus:outline-none">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-sliders text-brand-600 text-xs"></i> Price Range (৳)</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openPrice }"></i>
                        </button>
                        <div x-show="openPrice" x-transition:enter="transition ease-out duration-200" class="mt-3 space-y-3">
                            <div class="flex justify-between items-center text-xs font-bold text-brand-700">
                                <span>৳ 0</span>
                                <span>Up to: ৳ <span x-text="Number(maxPriceVal).toLocaleString()"></span></span>
                            </div>
                            <input 
                                type="range" 
                                name="max_price" 
                                min="100" 
                                max="20000" 
                                step="100" 
                                x-model="maxPriceVal" 
                                class="w-full accent-brand-600 cursor-pointer"
                            >
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min Price" class="w-full text-xs border rounded-lg p-2 focus:ring-1 focus:ring-brand-500">
                                <input type="number" name="max_price" x-model="maxPriceVal" placeholder="Max Price" class="w-full text-xs border rounded-lg p-2 focus:ring-1 focus:ring-brand-500">
                            </div>
                        </div>
                    </div>

                    <!-- Super Offer Checkbox -->
                    <div>
                        <label class="flex items-center gap-2 text-xs font-bold text-gray-800 cursor-pointer p-2 bg-amber-50 rounded-xl border border-amber-200">
                            <input type="checkbox" name="super_offer" value="1" {{ request('super_offer') ? 'checked' : '' }} onchange="this.form.submit()" class="text-amber-500 rounded">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-fire text-amber-500"></i> Super Offers Only</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-brand-600 text-white font-bold text-xs py-3 rounded-xl hover:bg-brand-700 transition shadow-md">
                        Apply Filter
                    </button>
                    @if(request()->anyFilled(['category', 'brand', 'min_price', 'max_price', 'super_offer', 'q']))
                        <a href="{{ route('products.index') }}" class="block text-center text-xs text-red-600 font-semibold hover:underline mt-2">
                            Clear Filters
                        </a>
                    @endif
                </form>
            </aside>

            <!-- Product Grid Area -->
            <div class="md:col-span-3">
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
                        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">No products matched your search or selected filters. Please try adjusting your filters.</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-brand-600 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-brand-700 transition">
                            View All Products
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
