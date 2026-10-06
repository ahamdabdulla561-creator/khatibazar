@extends('layouts.app')

@section('title', 'Order Chat & Support - Khati Bajar')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm text-center mb-8">
            <div class="w-16 h-16 bg-brand-100 text-brand-700 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Order Chat & Message Support</h1>
            <p class="text-xs md:text-sm text-gray-500 mb-6 max-w-md mx-auto">অর্ডারের মেসেজ পাঠাতে বা এডমিনের দেওয়া বার্তা দেখতে আপনার অর্ডার আইডি অথবা মোবাইল নম্বর দিয়ে খুঁজুন।</p>

            <form action="{{ route('order.track') }}" method="GET" class="max-w-lg mx-auto flex gap-2">
                <input 
                    type="text" 
                    name="query" 
                    value="{{ request('query') }}" 
                    required 
                    placeholder="অর্ডার আইডি (e.g. KB-20261005-XXXXX) বা ফোন নম্বর..." 
                    class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                >
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-3.5 rounded-2xl transition shadow-md flex items-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-comments"></i> Find & Chat
                </button>
            </form>
        </div>

        @if($searched)
            @if(count($orders) > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                            <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-gray-100 mb-6 gap-2">
                                <div>
                                    <span class="text-xs text-gray-400 block">Order ID</span>
                                    <span class="text-lg font-mono font-bold text-brand-700">{{ $order->order_number }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Order Date</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $order->created_at->format('d M, Y - h:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Grand Total</span>
                                    <span class="text-base font-extrabold text-gray-900">{{ format_price($order->grand_total) }}</span>
                                </div>
                            </div>

                            <!-- Order Status Timeline -->
                            <div class="mb-8">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Order Progress Timeline:</h4>
                                
                                @php
                                    $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                                    $statusNames = [
                                        'pending'    => 'Order Placed',
                                        'confirmed'  => 'Confirmed',
                                        'processing' => 'Processing',
                                        'shipped'    => 'Shipped',
                                        'delivered'  => 'Delivered'
                                    ];
                                    $currentIndex = array_search($order->order_status, $statuses);
                                    if ($order->order_status == 'cancelled') $currentIndex = -1;
                                @endphp

                                @if($order->order_status == 'cancelled')
                                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-center font-bold text-sm">
                                        <i class="fa-solid fa-circle-xmark text-lg mr-1"></i> This order has been Cancelled.
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 relative">
                                        @foreach($statuses as $idx => $stKey)
                                            <div class="flex flex-col items-center text-center p-3 rounded-2xl border {{ $idx <= $currentIndex ? 'bg-brand-50 border-brand-300 text-brand-800' : 'bg-gray-50 border-gray-100 text-gray-400' }}">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mb-2 {{ $idx <= $currentIndex ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                                    {{ $idx + 1 }}
                                                </div>
                                                <span class="text-xs font-bold">{{ $statusNames[$stKey] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($order->courier_name || $order->courierService)
                                    <div class="mt-4 p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center font-bold text-lg">
                                                <i class="fa-solid fa-truck-fast"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs text-emerald-800 font-bold block">Assigned Courier Partner:</span>
                                                <span class="text-sm font-extrabold text-emerald-950">{{ $order->courier_name ?? $order->courierService->name }}</span>
                                                @if($order->courier_tracking_id)
                                                    <span class="text-xs font-mono text-emerald-700 block">Tracking No: {{ $order->courier_tracking_id }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($order->courierService && $order->courier_tracking_id && $order->courierService->getTrackingUrl($order->courier_tracking_id))
                                            <a href="{{ $order->courierService->getTrackingUrl($order->courier_tracking_id) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Live Courier Tracking &rarr;
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Ordered Items List -->
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Items Ordered:</h4>
                                <div class="space-y-2">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between items-center text-xs bg-gray-50 p-3 rounded-xl">
                                            <div>
                                                <span class="font-bold text-gray-900">{{ $item->product_name }}</span>
                                                @if($item->variant_name)
                                                    <span class="text-gray-500">({{ $item->variant_name }})</span>
                                                @endif
                                                <span class="text-gray-500 ml-2">x {{ $item->quantity }}</span>
                                            </div>
                                            <span class="font-bold text-gray-900">{{ format_price($item->subtotal) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Live Chat & WhatsApp Box for Track Order -->
                            <div class="mt-6 pt-6 border-t border-gray-100 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-brand-50 p-4 rounded-2xl border border-brand-100">
                                    <div>
                                        <h4 class="font-bold text-brand-900 text-xs flex items-center gap-2">
                                            <i class="fa-solid fa-headset text-brand-600"></i> এডমিন সাপোর্ট ও সরাসরি বার্তা
                                        </h4>
                                        <p class="text-[11px] text-brand-700 mt-0.5">এই অর্ডার নিয়ে সরাসরি প্রশ্ন করতে মেসেজ লিখুন অথবা হোয়াটসঅ্যাপ ব্যবহার করুন।</p>
                                    </div>
                                    @php
                                        $waUrl = "https://wa.me/8801700000000?text=" . urlencode("আসসালামু আলাইকুম, আমি আমার অর্ডার #" . $order->order_number . " ট্র্যাক করেছি এবং বিস্তারিত জানতে চাই।");
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition shrink-0 shadow-sm">
                                        <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Chat
                                    </a>
                                </div>

                                <!-- Chat Timeline & Send Box -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-3">
                                    <h5 class="font-bold text-gray-800 text-xs flex items-center justify-between">
                                        <span><i class="fa-solid fa-comments text-brand-600 mr-1"></i> মেসেজ ইনবক্স</span>
                                        <span class="text-[11px] text-gray-400 font-normal">{{ count($order->messages) }} টি মেসেজ</span>
                                    </h5>
                                    
                                    <div class="space-y-2 max-h-60 overflow-y-auto p-2 bg-white rounded-xl border border-gray-100 text-xs">
                                        @forelse($order->messages as $msg)
                                            <div class="flex flex-col {{ $msg->sender_type === 'customer' ? 'items-end' : 'items-start' }}">
                                                <div class="flex items-center gap-1.5 text-[10px] text-gray-400 mb-0.5">
                                                    <span class="font-bold {{ $msg->sender_type === 'customer' ? 'text-brand-700' : 'text-emerald-700' }}">
                                                        {{ $msg->sender_name }} ({{ $msg->sender_type === 'customer' ? 'আপনি' : 'এডমিন' }})
                                                    </span>
                                                    <span>• {{ $msg->created_at->format('d M, h:i A') }}</span>
                                                </div>
                                                <div class="max-w-[85%] p-2.5 rounded-xl text-xs font-medium {{ $msg->sender_type === 'customer' ? 'bg-brand-600 text-white rounded-tr-none' : 'bg-emerald-50 text-emerald-950 border border-emerald-200 rounded-tl-none' }}">
                                                    {{ $msg->message }}
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4 text-xs text-gray-400">
                                                কোনো মেসেজ রেকর্ড নেই। কোনো প্রশ্ন থাকলে নিচে লিখুন।
                                            </div>
                                        @endforelse
                                    </div>

                                    <form action="{{ route('order.track.message', $order->id) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="message" required placeholder="অর্ডার সংক্রান্ত প্রশ্ন লিখুন..." class="flex-1 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition flex items-center gap-1 shadow-sm whitespace-nowrap">
                                            <i class="fa-solid fa-paper-plane"></i> পাঠান
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-8 text-center border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">No order found</h3>
                    <p class="text-xs text-gray-500">Please provide a valid order ID or mobile number.</p>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
