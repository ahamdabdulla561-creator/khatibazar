<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', site_setting('site_name', 'Khati Bajar - Pure & Organic Products'))</title>

    <!-- Google Fonts: Inter & Hind Siliguri for Bengali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; }

        /* Keyframe Animations for Hero Section */
        @keyframes heroSlideLeft {
            0% { opacity: 0; transform: translateX(-40px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes heroSlideRight {
            0% { opacity: 0; transform: translateX(40px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes heroFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        /* Continuous Infinite Animations */
        @keyframes badgeGlowPulse {
            0%, 100% { transform: scale(1); filter: brightness(1); }
            50% { transform: scale(1.06); filter: brightness(1.15); box-shadow: 0 0 10px rgba(245, 158, 11, 0.5); }
        }
        @keyframes discountBadgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }
        }
        @keyframes flamePulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.18) rotate(-4deg); }
        }
        @keyframes continuousFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .animate-badge-glow {
            animation: badgeGlowPulse 2.5s ease-in-out infinite;
        }
        .animate-discount-glow {
            animation: discountBadgePulse 2.8s ease-in-out infinite;
        }
        .animate-flame {
            animation: flamePulse 2s ease-in-out infinite;
        }
        .animate-continuous-float {
            animation: continuousFloat 4s ease-in-out infinite;
        }

        .animate-hero-left {
            animation: heroSlideLeft 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-hero-right {
            animation: heroSlideRight 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-float {
            animation: heroFloat 5s ease-in-out infinite;
        }
        .animate-pulse-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }

        /* Generic Reveal Animation Classes */
        .reveal-side-left {
            opacity: 0;
            transform: translateX(-35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-side-right {
            opacity: 0;
            transform: translateX(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-side-left.animated-in,
        .reveal-side-right.animated-in,
        .reveal-up.animated-in {
            opacity: 1;
            transform: translate(0, 0);
        }

        /* Ultra Smooth Product Side Entrance Animation */
        .product-card-anim {
            opacity: 0;
            transform: translateX(-28px);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        /* Stagger effect for grid items */
        .grid > .product-card-anim:nth-child(1), .product-card-anim:nth-child(1) { transition-delay: 0ms; }
        .grid > .product-card-anim:nth-child(2), .product-card-anim:nth-child(2) { transition-delay: 90ms; }
        .grid > .product-card-anim:nth-child(3), .product-card-anim:nth-child(3) { transition-delay: 180ms; }
        .grid > .product-card-anim:nth-child(4), .product-card-anim:nth-child(4) { transition-delay: 270ms; }
        .grid > .product-card-anim:nth-child(5), .product-card-anim:nth-child(5) { transition-delay: 360ms; }
        .grid > .product-card-anim:nth-child(6), .product-card-anim:nth-child(6) { transition-delay: 450ms; }
        .grid > .product-card-anim:nth-child(7), .product-card-anim:nth-child(7) { transition-delay: 540ms; }
        .grid > .product-card-anim:nth-child(8), .product-card-anim:nth-child(8) { transition-delay: 630ms; }

        .product-card-anim.animated-in {
            opacity: 1;
            transform: translateX(0);
        }

        /* Subtle smooth side-hover elevate interaction */
        .product-card-hover {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
        }
        .product-card-hover:hover {
            transform: translateY(-6px) scale(1.008);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.08), 0 8px 15px -6px rgba(0, 0, 0, 0.04);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false, searchOpen: false, cartCount: 0 }" x-init="fetch('{{ route('cart.count') }}').then(r => r.ok ? r.json() : {count: 0}).then(d => cartCount = d ? d.count : 0).catch(() => cartCount = 0)">

    <!-- Top Announcement Bar -->
    <div class="bg-brand-900 text-white text-xs md:text-sm py-2.5 px-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-3">
            <div class="flex items-center gap-4 md:gap-6">
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-phone text-amber-500"></i> Helpline: {{ site_setting('phone', '01711-000000') }}</span>
                <span class="hidden md:flex items-center gap-1.5"><i class="fa-solid fa-envelope text-amber-500"></i> {{ site_setting('email', 'info@khatibajar.com') }}</span>
            </div>
            <div class="flex items-center gap-4 md:gap-6">
                <a href="{{ route('order.track') }}" class="hover:text-amber-400 transition flex items-center gap-1.5"><i class="fa-solid fa-comments text-amber-500"></i> Order Chat / SMS</a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-amber-400 transition flex items-center gap-1.5"><i class="fa-solid fa-user text-amber-500"></i> Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-amber-400 transition flex items-center gap-1.5"><i class="fa-solid fa-right-to-bracket text-amber-500"></i> Login / Register</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white sticky top-0 z-40 shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                @if(site_setting('site_logo'))
                    <img src="{{ asset('storage/' . site_setting('site_logo')) }}" alt="Logo" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-brand-600 text-white rounded-xl flex items-center justify-center font-bold text-xl shadow-md group-hover:bg-brand-700 transition">
                        K
                    </div>
                    <div>
                        <span class="text-xl font-bold text-brand-800 tracking-tight block">Khati Bajar</span>
                        <span class="text-[10px] text-gray-500 font-medium block -mt-1">Khati Bajar Online Shop</span>
                    </div>
                @endif
            </a>

            <!-- Desktop Search Bar -->
            <div class="hidden md:flex flex-1 max-w-xl relative" x-data="searchComponent()">
                <div class="relative w-full">
                    <input 
                        type="text" 
                        x-model="query" 
                        @input.debounce.300ms="fetchSuggestions()"
                        placeholder="Search products or brands..." 
                        class="w-full bg-gray-50 border border-gray-200 rounded-full py-2.5 pl-5 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition"
                    >
                    <button type="button" @click="search()" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-brand-600 text-white rounded-full hover:bg-brand-700 transition flex items-center justify-center">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>
                <!-- Suggestions Dropdown -->
                <div x-show="suggestions.length > 0" @click.away="suggestions = []" x-cloak class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                    <template x-for="item in suggestions" :key="item.id">
                        <a :href="'/products/' + item.slug" class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b border-gray-50 last:border-0 transition">
                            <img :src="item.image ? '/storage/' + item.image : 'https://placehold.co/100x100?text=KB'" class="w-10 h-10 object-cover rounded-lg">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></h4>
                                <p class="text-xs text-brand-600 font-bold" x-text="'৳ ' + (item.sale_price || item.regular_price)"></p>
                            </div>
                        </a>
                    </template>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center gap-3 md:gap-4">
                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2 bg-brand-50 text-brand-700 px-3.5 py-2 rounded-full hover:bg-brand-100 transition font-medium text-sm">
                    <i class="fa-solid fa-basket-shopping text-base text-brand-600"></i>
                    <span class="hidden sm:inline">Cart</span>
                    <span class="bg-brand-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center" x-text="cartCount">0</span>
                </a>

                <!-- Mobile Menu Trigger -->
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700 p-2 text-xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="hidden md:block bg-brand-800 text-white border-t border-brand-700">
            <div class="max-w-7xl mx-auto px-4 flex items-center gap-6 text-sm font-medium">
                
                <!-- Categories Header Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" @click="open = !open" class="py-3 px-4 bg-brand-900 hover:bg-brand-950 text-white rounded-t-lg font-bold flex items-center gap-2.5 transition">
                        <i class="fa-solid fa-bars text-amber-400"></i>
                        <span>Categories</span>
                        <i class="fa-solid fa-chevron-down text-xs ml-1 text-amber-300 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                         class="absolute left-0 top-full w-64 bg-white text-gray-800 rounded-b-2xl shadow-2xl border border-gray-100 z-50 py-2 space-y-1">
                        @foreach(\App\Models\Category::where('status', 'active')->orderBy('sort_order', 'asc')->get() as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between px-4 py-2.5 hover:bg-brand-50 hover:text-brand-700 text-xs font-semibold text-gray-700 transition group">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-6 h-6 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-brand-600 group-hover:text-white transition">
                                        @if($cat->image)
                                            <img src="{{ asset('storage/' . $cat->image) }}" class="w-4 h-4 object-cover rounded">
                                        @else
                                            <i class="fa-solid fa-prescription-bottle-medical text-xs"></i>
                                        @endif
                                    </div>
                                    <span class="truncate">{{ $cat->name }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-brand-600 group-hover:translate-x-1 transition-all"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('home') }}" class="py-3 hover:text-amber-400 transition {{ request()->routeIs('home') ? 'text-amber-400 font-bold border-b-2 border-amber-400' : '' }}">Home</a>
                <a href="{{ route('products.index') }}" class="py-3 hover:text-amber-400 transition {{ request()->routeIs('products.index') ? 'text-amber-400 font-bold border-b-2 border-amber-400' : '' }}">All Products</a>
                
                @foreach(\App\Models\Category::where('status', 'active')->orderBy('sort_order', 'asc')->take(4)->get() as $navCat)
                    <a href="{{ route('category.show', $navCat->slug) }}" class="py-3 hover:text-amber-400 transition">{{ $navCat->name }}</a>
                @endforeach
                
                <a href="{{ route('order.track') }}" class="py-3 hover:text-amber-400 transition ml-auto text-amber-300 font-semibold flex items-center gap-1.5"><i class="fa-solid fa-comments"></i> Order Chat / SMS</a>
            </div>
        </nav>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
            <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="relative bg-white w-4/5 max-w-sm ml-auto h-full shadow-2xl flex flex-col z-10">
                <div class="p-4 bg-brand-800 text-white flex items-center justify-between">
                    <span class="font-bold text-lg">মোপাইল মেনু</span>
                    <button @click="mobileMenuOpen = false" class="text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="p-4 border-b">
                    <form action="{{ route('products.index') }}" method="GET">
                        <input type="text" name="q" placeholder="Search products..." class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-brand-500">
                    </form>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-3 font-medium">
                    <a href="{{ route('home') }}" class="block py-2 border-b text-gray-700">Home</a>
                    <a href="{{ route('products.index') }}" class="block py-2 border-b text-gray-700">All Products</a>
                    @foreach(\App\Models\Category::where('status', 'active')->get() as $navCat)
                        <a href="{{ route('category.show', $navCat->slug) }}" class="block py-2 border-b text-gray-600 pl-2 text-sm">{{ $navCat->name }}</a>
                    @endforeach
                    <a href="{{ route('cart.index') }}" class="block py-2 border-b text-gray-700">Cart</a>
                    <a href="{{ route('order.track') }}" class="block py-2 border-b text-amber-600 font-bold flex items-center gap-1.5"><i class="fa-solid fa-comments"></i> Order Chat / SMS</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notification Messages -->
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl shadow-sm mb-4 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl shadow-sm mb-4 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 pt-12 pb-6 border-t border-gray-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div>
                <h3 class="text-white text-lg font-bold mb-4">Khati Bajar</h3>
                <p class="text-sm text-gray-400 mb-4 leading-relaxed">
                    The trusted digital marketplace for 100% pure products &amp; medicines for agriculture, livestock, and fish farmers.
                </p>
                <div class="flex space-x-3">
                    @if(site_setting('facebook_url'))
                        <a href="{{ site_setting('facebook_url') }}" target="_blank" class="w-9 h-9 bg-gray-800 text-white rounded-full flex items-center justify-center hover:bg-brand-600 transition"><i class="fa-brands fa-facebook-f"></i></a>
                    @endif
                    @if(site_setting('youtube_url'))
                        <a href="{{ site_setting('youtube_url') }}" target="_blank" class="w-9 h-9 bg-gray-800 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition"><i class="fa-brands fa-youtube"></i></a>
                    @endif
                    @if(site_setting('whatsapp_number'))
                        <a href="https://wa.me/{{ site_setting('whatsapp_number') }}" target="_blank" class="w-9 h-9 bg-gray-800 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition"><i class="fa-brands fa-whatsapp"></i></a>
                    @endif
                </div>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-base">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-amber-400 transition">All Products</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-amber-400 transition">Shopping Cart</a></li>
                    <li><a href="{{ route('order.track') }}" class="hover:text-amber-400 transition">Order Tracking</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-base">Product Categories</h4>
                <ul class="space-y-2 text-sm">
                    @foreach(\App\Models\Category::where('status', 'active')->take(4)->get() as $fCat)
                        <li><a href="{{ route('category.show', $fCat->slug) }}" class="hover:text-amber-400 transition">{{ $fCat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-base">Contact</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot mt-1 text-amber-400"></i>
                        <span>{{ site_setting('address', 'Dhaka, Bangladesh') }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-amber-400"></i>
                        <span>{{ site_setting('phone', '01711-000000') }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-amber-400"></i>
                        <span>{{ site_setting('email', 'info@khatibajar.com') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 max-w-7xl mx-auto px-4 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-4">
            <p>&copy; {{ date('Y') }} Khati Bajar. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <span>Cash on Delivery Supported</span>
                <i class="fa-solid fa-shield-halved text-brand-500 text-sm"></i>
            </div>
        </div>
    </footer>

    <!-- Search AJAX Component Script -->
    <script>
        function searchComponent() {
            return {
                query: '',
                suggestions: [],
                fetchSuggestions() {
                    if (this.query.length < 2) {
                        this.suggestions = [];
                        return;
                    }
                    fetch('{{ route("api.search_suggestions") }}?q=' + encodeURIComponent(this.query))
                        .then(res => res.json())
                        .then(data => {
                            this.suggestions = data;
                        });
                },
                search() {
                    if (this.query.trim()) {
                        window.location.href = '{{ route("products.index") }}?q=' + encodeURIComponent(this.query);
                    }
                }
            }
        }
    </script>
    <!-- Floating Cart Widget (5️⃣ Star Highlight) -->
    <a href="{{ route('cart.index') }}" class="fixed right-0 top-1/2 -translate-y-1/2 z-40 bg-gradient-to-b from-brand-700 to-brand-900 text-white py-3 px-2.5 rounded-l-2xl shadow-2xl flex flex-col items-center gap-1.5 border-l-2 border-y border-amber-400/50 hover:px-3.5 transition-all duration-300 group">
        <div class="relative">
            <i class="fa-solid fa-cart-shopping text-lg text-amber-400 group-hover:scale-110 transition-transform"></i>
            <span class="absolute -top-2 -right-2.5 bg-red-600 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm" x-text="cartCount">0</span>
        </div>
        <span class="text-[10px] font-extrabold uppercase tracking-tighter text-amber-300">Cart</span>
    </a>

    <!-- Floating Back-to-Top Button (6️⃣ Star Highlight) -->
    <div x-data="{ showTopBtn: false }" @scroll.window="showTopBtn = (window.pageYOffset > 250)" class="fixed bottom-6 right-6 z-40">
        <button 
            type="button"
            x-show="showTopBtn" 
            x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="opacity-0 translate-y-6 scale-75" 
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-6 scale-75"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })" 
            class="w-12 h-12 bg-gradient-to-tr from-brand-600 via-brand-500 to-emerald-400 text-white rounded-full shadow-2xl flex items-center justify-center text-lg hover:scale-110 hover:shadow-brand-600/50 transition duration-300 cursor-pointer border-2 border-white"
            title="Back to top"
        >
            <i class="fa-solid fa-arrow-up"></i>
        </button>
    </div>

    <!-- Global Product Quick View Modal (4️⃣ Star Highlight: Dark Overlay + Fade/Scale Animation + Variants) -->
    <div 
        x-data="quickViewComponent()" 
        @open-quickview.window="loadProduct($event.detail.id)"
        x-show="isOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        @keydown.escape.window="closeModal()"
    >
        <!-- Dark Overlay Background (13) -->
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            @click="closeModal()" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
        ></div>

        <!-- Modal Dialog (14: Fade/Scale Effect) -->
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="opacity-0 scale-90 translate-y-4" 
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform" 
            x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
            x-transition:leave-end="opacity-0 scale-90 translate-y-4"
            class="relative bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden z-10 border border-gray-100 p-6 md:p-8"
        >
            <!-- Close Button -->
            <button @click="closeModal()" class="absolute top-4 right-4 bg-gray-100 text-gray-500 hover:bg-red-600 hover:text-white rounded-full w-8 h-8 flex items-center justify-center transition font-bold z-20">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Loading Spinner -->
            <div x-show="loading" class="py-12 text-center">
                <i class="fa-solid fa-spinner fa-spin text-3xl text-brand-600 mb-3"></i>
                <p class="text-xs font-bold text-gray-500">Loading product details...</p>
            </div>

            <!-- Product Content Body -->
            <div x-show="!loading && product" class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <!-- Image -->
                <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-center border border-gray-100 h-64">
                    <img :src="product.image_url" :alt="product.name" class="max-h-full max-w-full object-contain">
                </div>

                <!-- Info & Selection -->
                <div class="space-y-4">
                    <span class="text-[10px] font-extrabold uppercase bg-brand-50 text-brand-700 px-2.5 py-1 rounded-full tracking-wider" x-text="product.category_name"></span>
                    <h3 class="text-lg md:text-xl font-extrabold text-gray-900 leading-snug" x-text="product.name"></h3>
                    <p class="text-xs text-gray-500 line-clamp-2" x-text="product.description"></p>

                    <!-- Price View (16: Dynamic Update) -->
                    <div class="flex items-baseline gap-2 pt-1 border-t">
                        <span class="text-2xl font-black text-brand-700" x-text="'৳ ' + calculateTotalPrice().toLocaleString()"></span>
                        <template x-if="product && product.sale_price">
                            <span class="text-sm text-gray-400 line-through" x-text="product.formatted_regular_price"></span>
                        </template>
                    </div>

                    <!-- Variant Selection Options (15: Active State) -->
                    <template x-if="product && product.variants && product.variants.length > 0">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700">Select Size / Variant:</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="v in product.variants" :key="v.id">
                                    <button 
                                        type="button"
                                        @click="selectedVariant = v"
                                        :class="selectedVariant && selectedVariant.id === v.id ? 'border-brand-600 bg-brand-50 text-brand-700 font-extrabold shadow-sm ring-1 ring-brand-600' : 'border-gray-200 text-gray-700 hover:border-brand-300 bg-white'"
                                        class="px-3 py-1.5 rounded-xl border text-xs transition duration-200"
                                    >
                                        <span x-text="v.name"></span>
                                        <span class="text-[10px] opacity-70 block" x-text="v.formatted_price"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Quantity Controller (17: Dynamic Update) -->
                    <div class="flex items-center gap-3 pt-2">
                        <span class="text-xs font-bold text-gray-700">Quantity:</span>
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                            <button type="button" @click="quantity > 1 ? quantity-- : 1" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-200 transition font-bold">-</button>
                            <span class="px-3 text-xs font-bold text-gray-900" x-text="quantity"></span>
                            <button type="button" @click="quantity++" class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-200 transition font-bold">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3 pt-3">
                        <button 
                            type="button" 
                            @click="addToCart(false)" 
                            :disabled="adding"
                            class="bg-brand-50 hover:bg-brand-100 text-brand-700 font-extrabold text-xs py-3 rounded-xl border border-brand-200 transition flex items-center justify-center gap-1.5"
                        >
                            <i class="fa-solid fa-basket-shopping"></i> Add to Cart
                        </button>

                        <button 
                            type="button" 
                            @click="addToCart(true)" 
                            :disabled="adding"
                            class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-1.5 shadow-md"
                        >
                            <i class="fa-solid fa-bolt"></i> Order Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Alpine Component Script -->
    <script>
        function quickViewComponent() {
            return {
                isOpen: false,
                loading: false,
                adding: false,
                product: null,
                selectedVariant: null,
                quantity: 1,

                loadProduct(productId) {
                    this.isOpen = true;
                    this.loading = true;
                    this.product = null;
                    this.selectedVariant = null;
                    this.quantity = 1;

                    fetch('/api/products/' + productId + '/quick-view')
                        .then(res => res.json())
                        .then(data => {
                            this.product = data;
                            if (data.variants && data.variants.length > 0) {
                                this.selectedVariant = data.variants[0];
                            }
                            this.loading = false;
                        })
                        .catch(() => {
                            this.loading = false;
                            this.isOpen = false;
                        });
                },

                calculateTotalPrice() {
                    if (!this.product) return 0;
                    const unitPrice = this.selectedVariant ? this.selectedVariant.price : (this.product.effective_price || this.product.regular_price);
                    return unitPrice * this.quantity;
                },

                addToCart(redirectCheckout = false) {
                    if (!this.product || this.adding) return;
                    this.adding = true;

                    fetch('{{ route("cart.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: this.product.id,
                            product_variant_id: this.selectedVariant ? this.selectedVariant.id : null,
                            quantity: this.quantity
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.adding = false;
                        if (data.success) {
                            if (redirectCheckout) {
                                window.location.href = '{{ route("checkout.index") }}';
                            } else {
                                alert(data.message || 'Product added to cart!');
                                this.closeModal();
                                window.location.reload();
                            }
                        } else {
                            alert(data.message || 'Error adding to cart.');
                        }
                    })
                    .catch(() => {
                        this.adding = false;
                    });
                },

                closeModal() {
                    this.isOpen = false;
                }
            }
        }
    </script>

    <!-- Intersection Observer for Smooth Entrance Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cardObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                rootMargin: '0px 0px -20px 0px',
                threshold: 0.05
            });

            const initCardAnimations = () => {
                const selectors = '.product-card-anim:not(.animated-in), .reveal-side-left:not(.animated-in), .reveal-side-right:not(.animated-in), .reveal-up:not(.animated-in)';
                document.querySelectorAll(selectors).forEach(el => {
                    cardObserver.observe(el);
                });
            };

            initCardAnimations();
            window.addEventListener('content-updated', initCardAnimations);
        });
    </script>
    @stack('scripts')
</body>
</html>
