@extends('layouts.admin')

@section('title', 'Dashboard Overview - Khati Bazar')

@section('content')
<div class="space-y-8">
    
    <!-- Top Stats Counters Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Sales -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Sales</span>
                <span class="text-2xl font-extrabold text-gray-900">{{ format_price($totalSales) }}</span>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Orders</span>
                <span class="text-2xl font-extrabold text-gray-900">{{ number_format($totalOrders) }}</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Customers</span>
                <span class="text-2xl font-extrabold text-gray-900">{{ number_format($totalCustomers) }}</span>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Products</span>
                <span class="text-2xl font-extrabold text-gray-900">{{ number_format($totalProducts) }}</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

    </div>

    <!-- Order Status Counter Badges Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="bg-amber-50 border border-amber-200 p-4 rounded-2xl text-center block hover:bg-amber-100 transition">
            <span class="text-2xl font-extrabold text-amber-700 block">{{ $pendingOrders }}</span>
            <span class="text-xs font-bold text-amber-800">Pending Orders</span>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="bg-blue-50 border border-blue-200 p-4 rounded-2xl text-center block hover:bg-blue-100 transition">
            <span class="text-2xl font-extrabold text-blue-700 block">{{ $confirmedOrders }}</span>
            <span class="text-xs font-bold text-blue-800">Confirmed Orders</span>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="bg-indigo-50 border border-indigo-200 p-4 rounded-2xl text-center block hover:bg-indigo-100 transition">
            <span class="text-2xl font-extrabold text-indigo-700 block">{{ $processingOrders }}</span>
            <span class="text-xs font-bold text-indigo-800">Processing</span>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="bg-purple-50 border border-purple-200 p-4 rounded-2xl text-center block hover:bg-purple-100 transition">
            <span class="text-2xl font-extrabold text-purple-700 block">{{ $shippedOrders }}</span>
            <span class="text-xs font-bold text-purple-800">Shipped Orders</span>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="bg-green-50 border border-green-200 p-4 rounded-2xl text-center block hover:bg-green-100 transition">
            <span class="text-2xl font-extrabold text-green-700 block">{{ $deliveredOrders }}</span>
            <span class="text-xs font-bold text-green-800">Delivered</span>
        </a>

        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="bg-red-50 border border-red-200 p-4 rounded-2xl text-center block hover:bg-red-100 transition">
            <span class="text-2xl font-extrabold text-red-700 block">{{ $cancelledOrders }}</span>
            <span class="text-xs font-bold text-red-800">Cancelled</span>
        </a>
    </div>

    <!-- Recent Orders Table & Low Stock Alert Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Orders Table -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-2 border-b">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-brand-600"></i> Recent Orders
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-brand-600 hover:underline">View All Orders</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b text-gray-500 uppercase font-semibold">
                            <th class="p-3">Order ID</th>
                            <th class="p-3">Customer Name & Phone</th>
                            <th class="p-3">Total Amount</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-mono font-bold text-brand-700">#{{ $order->order_number }}</td>
                                <td class="p-3">
                                    <span class="font-bold text-gray-800 block">{{ $order->customer_name }}</span>
                                    <span class="text-gray-500">{{ $order->customer_phone }}</span>
                                </td>
                                <td class="p-3 font-extrabold text-gray-900">{{ format_price($order->grand_total) }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                        @if($order->order_status == 'delivered') bg-green-100 text-green-700
                                        @elseif($order->order_status == 'cancelled') bg-red-100 text-red-700
                                        @else bg-amber-100 text-amber-700 @endif">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-brand-50 text-brand-700 font-bold px-3 py-1.5 rounded-lg hover:bg-brand-100 transition">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alert Box -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Low Stock Product Alert
            </h2>

            <div class="space-y-3">
                @foreach($lowStockProducts as $prd)
                    <div class="p-3 bg-amber-50/60 border border-amber-100 rounded-2xl flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-gray-900 block line-clamp-1">{{ $prd->name }}</span>
                            <span class="text-gray-500">SKU: {{ $prd->sku }}</span>
                        </div>
                        <span class="bg-red-500 text-white font-extrabold px-2.5 py-1 rounded-full text-[10px]">
                            Stock: {{ $prd->stock }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
