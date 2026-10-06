@extends('layouts.admin')

@section('title', 'Orders - Khati Bajar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Order Management</h1>
            <p class="text-xs text-gray-500 mt-1">Manage and process all customer orders</p>
        </div>
    </div>

    <!-- Status Tabs & Filter -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex flex-wrap gap-2 text-xs font-bold">
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl transition {{ !request('status') ? 'bg-brand-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All Orders</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">Pending</a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') == 'confirmed' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">Confirmed</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') == 'processing' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">Processing</a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') == 'shipped' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">Shipped</a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') == 'delivered' ? 'bg-green-600 text-white shadow-sm' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">Delivered</a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-3.5 py-2 rounded-xl transition {{ request('status') == 'cancelled' ? 'bg-red-600 text-white shadow-sm' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">Cancelled</a>
        </div>

        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Order number or phone..." class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-brand-500">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold">Search</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="p-4">Order ID</th>
                        <th class="p-4">Customer Name & Phone</th>
                        <th class="p-4">Delivery Address</th>
                        <th class="p-4">Total Amount</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-mono font-bold text-brand-700">#{{ $order->order_number }}</td>
                            <td class="p-4">
                                <span class="font-bold text-gray-900 block">{{ $order->customer_name }}</span>
                                <span class="text-xs text-gray-500">{{ $order->customer_phone }}</span>
                            </td>
                            <td class="p-4 text-xs text-gray-600 max-w-xs truncate">
                                {{ $order->shipping_upazila }}, {{ $order->shipping_district }}
                            </td>
                            <td class="p-4 font-extrabold text-gray-900">{{ format_price($order->grand_total) }}</td>
                            <td class="p-4 text-xs">
                                <span class="font-bold uppercase block">{{ $order->payment_method }}</span>
                                <span class="text-gray-500">({{ ucfirst($order->payment_status) }})</span>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                    @if($order->order_status == 'delivered') bg-green-100 text-green-700
                                    @elseif($order->order_status == 'cancelled') bg-red-100 text-red-700
                                    @elseif($order->order_status == 'pending') bg-amber-100 text-amber-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-brand-600 text-white font-bold text-xs px-3.5 py-2 rounded-xl hover:bg-brand-700 transition">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
