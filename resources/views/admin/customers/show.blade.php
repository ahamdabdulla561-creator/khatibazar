@extends('layouts.admin')

@section('title', 'Customer Profile - ' . $customer->name . ' - Khati Bajar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">{{ $customer->name }}</h1>
            <p class="text-xs text-gray-500 mt-1">Registration Date: {{ $customer->created_at->format('d M, Y') }}</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="text-xs text-gray-500 hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Back to List</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Customer Details Info Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="font-bold text-gray-900 text-base pb-2 border-b">Profile Details</h2>

            <div class="space-y-2 text-xs">
                <div>
                    <span class="text-gray-400 block">Name:</span>
                    <span class="font-bold text-gray-900 text-sm">{{ $customer->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Mobile:</span>
                    <span class="font-bold text-gray-900 text-sm">{{ $customer->phone }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Email:</span>
                    <span class="font-medium text-gray-800">{{ $customer->email ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Status:</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $customer->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.customers.toggle_status', $customer->id) }}" method="POST" class="pt-4 border-t">
                @csrf
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl text-xs transition">
                    {{ $customer->status == 'active' ? 'Suspend Account' : 'Activate Account' }}
                </button>
            </form>
        </div>

        <!-- Order History List -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="font-bold text-gray-900 text-base pb-2 border-b">Customer Order History ({{ $customer->orders->count() }})</h2>

            <div class="space-y-3">
                @foreach($customer->orders as $order)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-mono font-bold text-brand-700 block">#{{ $order->order_number }}</span>
                            <span class="text-gray-500">{{ $order->created_at->format('d M Y') }} — {{ $order->items->count() }} items</span>
                        </div>
                        <div>
                            <span class="font-extrabold text-gray-900 block">{{ format_price($order->grand_total) }}</span>
                            <span class="uppercase font-semibold text-[10px] text-gray-500">{{ $order->order_status }}</span>
                        </div>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-brand-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-brand-700">View</a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
