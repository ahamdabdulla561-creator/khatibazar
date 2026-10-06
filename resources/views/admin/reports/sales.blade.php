@extends('layouts.admin')

@section('title', 'বিক্রয় রিপোর্ট (Sales Reports)')

@section('content')
<div class="space-y-6">
    <!-- Header & Period Filter Tabs -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-emerald-600"></i> {{ $title }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">পণ্য বিক্রয়, অর্ডার সংখ্যা ও মোট আয় পর্যবেক্ষণ ও বিশ্লেষণ করুন</p>
        </div>

        <!-- Period Navigation -->
        <div class="flex flex-wrap items-center gap-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200">
            <a href="{{ route('admin.reports.sales', ['period' => 'today']) }}" 
               class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition {{ $period === 'today' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                <i class="fa-solid fa-calendar-day mr-1"></i> আজ (Today)
            </a>
            <a href="{{ route('admin.reports.sales', ['period' => 'weekly']) }}" 
               class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition {{ $period === 'weekly' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                <i class="fa-solid fa-calendar-week mr-1"></i> এই সপ্তাহ (Weekly)
            </a>
            <a href="{{ route('admin.reports.sales', ['period' => 'monthly']) }}" 
               class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition {{ $period === 'monthly' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                <i class="fa-solid fa-calendar-days mr-1"></i> এই মাস (Monthly)
            </a>
        </div>
    </div>

    <!-- Custom Date Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form action="{{ route('admin.reports.sales') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <input type="hidden" name="period" value="custom">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">শুরু (Start Date)</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">শেষ (End Date)</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-filter"></i> ফিল্টার প্রয়োগ করুন
            </button>
        </form>
    </div>

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Sales Revenue -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-700 text-white p-5 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold opacity-90 block">মোট বিক্রয় (Total Revenue)</span>
                    <h2 class="text-2xl font-black mt-1">৳ {{ number_format($totalSales, 2) }}</h2>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-5 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold opacity-90 block">মোট সফল অর্ডার (Orders)</span>
                    <h2 class="text-2xl font-black mt-1">{{ number_format($totalOrders) }} টি</h2>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
        </div>

        <!-- Total Items Sold -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white p-5 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold opacity-90 block">মোট পণ্য বিক্রিত (Items Sold)</span>
                    <h2 class="text-2xl font-black mt-1">{{ number_format($totalItemsSold) }} টি</h2>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
        </div>

        <!-- Avg Order Value -->
        <div class="bg-gradient-to-br from-purple-600 to-pink-600 text-white p-5 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold opacity-90 block">গড় অর্ডার মূল্য (Avg Order)</span>
                    <h2 class="text-2xl font-black mt-1">৳ {{ number_format($averageOrderValue, 2) }}</h2>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                    <i class="fa-solid fa-calculator"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown Table & Top Selling Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daily Breakdown Table (2 cols) -->
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list-ol text-emerald-600"></i> তারিখ অনুযায়ী দৈনিক হিসাব (Daily Breakdown)
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b border-slate-200">
                            <th class="py-2.5 px-3 font-bold">তারিখ (Date)</th>
                            <th class="py-2.5 px-3 font-bold">মোট অর্ডার</th>
                            <th class="py-2.5 px-3 font-bold text-right">মোট বিক্রয় টাকা</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dailyData as $row)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-2.5 px-3 font-medium text-slate-800">
                                    {{ \Carbon\Carbon::parse($row->date)->format('d M, Y (l)') }}
                                </td>
                                <td class="py-2.5 px-3 font-semibold text-slate-600">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-bold">
                                        {{ $row->total_orders }} টি
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-extrabold text-emerald-700 text-right">
                                    ৳ {{ number_format($row->total_revenue, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-400">কোনো তথ্য পাওয়া যায়নি (No sales records found).</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Products Sidebar (1 col) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-fire text-amber-500"></i> সেরা বিক্রীত পণ্য (Top Selling)
            </h3>
            <div class="space-y-3">
                @forelse($topProducts as $item)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 hover:border-amber-200 transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-lg overflow-hidden bg-white border border-slate-200 shrink-0">
                                <img src="{{ $item->product && $item->product->image ? (filter_var($item->product->image, FILTER_VALIDATE_URL) ? $item->product->image : asset('storage/' . $item->product->image)) : 'https://placehold.co/100x100?text=KB' }}" 
                                     alt="" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate">
                                    {{ $item->product->name ?? 'Deleted Product' }}
                                </h4>
                                <span class="text-[10px] text-slate-500 block">বিক্রি: <strong class="text-amber-600">{{ $item->total_qty }}</strong> টি</span>
                            </div>
                        </div>
                        <span class="text-xs font-black text-slate-700 shrink-0 ml-2">
                            ৳ {{ number_format($item->total_amount, 0) }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">কোনো ডেটা পাওয়া যায়নি</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
