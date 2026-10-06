@extends('layouts.app')

@section('title', 'আমার অর্ডারসমূহ - খাঁটি বাজার')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-64 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm h-fit">
                <nav class="space-y-1 text-sm font-semibold">
                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-gauge mr-2"></i> ড্যাশবোর্ড
                    </a>
                    <a href="{{ route('customer.orders') }}" class="block px-4 py-2.5 rounded-xl bg-brand-50 text-brand-700 font-bold">
                        <i class="fa-solid fa-box-archive mr-2"></i> আমার অর্ডারসমূহ
                    </a>
                </nav>
            </div>

            <!-- Orders Table / List -->
            <div class="flex-1 bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                <h1 class="text-xl font-bold text-gray-900 mb-6 pb-2 border-b">আমার অর্ডার তালিকা</h1>

                @if(count($orders) > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <span class="font-mono font-bold text-brand-700 text-sm block">#{{ $order->order_number }}</span>
                                    <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                </div>

                                <div>
                                    <span class="text-xs font-bold px-3 py-1 rounded-full uppercase
                                        @if($order->order_status == 'delivered') bg-green-100 text-green-700
                                        @elseif($order->order_status == 'cancelled') bg-red-100 text-red-700
                                        @else bg-amber-100 text-amber-700 @endif">
                                        {{ $order->order_status }}
                                    </span>
                                </div>

                                <div>
                                    <span class="font-extrabold text-sm text-gray-900 block">{{ format_price($order->grand_total) }}</span>
                                    <span class="text-[11px] text-gray-500">মেথড: {{ strtoupper($order->payment_method) }}</span>
                                </div>

                                <a href="{{ route('customer.orders.show', $order->id) }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                                    অর্ডার ডিটেইলস
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-12">আপনি এখনো কোনো অর্ডার করেননি।</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
