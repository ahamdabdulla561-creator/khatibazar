<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Khati Bajar')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts -->
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
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 transform transition-transform duration-300 md:translate-x-0 md:static md:inset-0 flex flex-col shadow-2xl"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-4 bg-slate-950 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain bg-white/90 p-1 rounded-lg">
                <span class="font-extrabold text-base text-white tracking-wide">Admin Panel</span>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <!-- Navigation Items -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-chart-line w-5"></i>
                <span>Dashboard</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-4">Products & Stock</div>

            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-box-archive w-5"></i>
                <span>All Products (সকল পণ্য)</span>
            </a>

            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.create') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-plus w-5"></i>
                <span>Upload Product (নতুন পণ্য)</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-layer-group w-5"></i>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.brands.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-tags w-5"></i>
                <span>Brand Management</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-4">Orders & Customers</div>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-shopping-cart w-5"></i>
                <span>Order Management</span>
            </a>

            <a href="{{ route('admin.reports.sales') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white font-bold' : 'hover:bg-slate-800 text-emerald-400' }}">
                <i class="fa-solid fa-chart-pie w-5 text-emerald-400"></i>
                <span>Sales Reports (বিক্রয় রিপোর্ট)</span>
            </a>

            <a href="{{ route('admin.courier-services.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.courier-services.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-truck-fast w-5"></i>
                <span>Courier Services</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.customers.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-users w-5"></i>
                <span>Customer List</span>
            </a>

            <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-4">System Configuration</div>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-gears w-5"></i>
                <span>Site Settings</span>
            </a>

            <a href="{{ route('admin.audit_logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.audit_logs.*') ? 'bg-brand-600 text-white font-bold' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-clock-rotate-left w-5"></i>
                <span>Audit Logs</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-brand-400 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-globe"></i> Visit Website
            </a>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shadow-sm">
            <button @click="sidebarOpen = true" class="md:hidden text-gray-600 text-xl"><i class="fa-solid fa-bars"></i></button>

            <h1 class="text-lg font-bold text-gray-800 hidden md:block">@yield('title', 'Dashboard')</h1>

            <div class="flex items-center gap-4 ml-auto">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-brand-100 text-brand-700 font-bold rounded-full flex items-center justify-center border border-brand-200">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="block text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <span class="block text-xs text-gray-500">Super Admin</span>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body Area -->
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl shadow-sm mb-6 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl shadow-sm mb-6 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
