@extends('layouts.app')

@section('title', 'Order Successful - Khati Bajar')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4">
        
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-lg text-center mb-8">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-4 animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Congratulations! Your order has been placed successfully.</h1>
            <p class="text-sm text-gray-500 mb-6">Our representative will contact you by phone very soon.</p>

            <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4 inline-block mb-6">
                <span class="text-xs text-brand-800 font-medium block">Your Order ID</span>
                <span class="text-xl md:text-2xl font-mono font-bold text-brand-700 tracking-wider">{{ $order->order_number }}</span>
            </div>

            <!-- Customer & Shipping Summary -->
            <div class="text-left border-t border-gray-100 pt-6 space-y-3 text-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-gray-400 block">Customer Name</span>
                        <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Mobile Number</span>
                        <span class="font-bold text-gray-800">{{ $order->customer_phone }}</span>
                    </div>
                </div>

                <div>
                    <span class="text-xs text-gray-400 block">Delivery Address</span>
                    <span class="font-medium text-gray-800">{{ $order->shipping_address }}, {{ $order->shipping_upazila }}, {{ $order->shipping_district }}</span>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mt-6 text-left border-t border-gray-100 pt-6">
                <h3 class="font-bold text-gray-900 mb-3 text-sm">Order Items:</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-xs bg-gray-50 p-2.5 rounded-xl">
                            <div>
                                <span class="font-bold text-gray-800">{{ $item->product_name }}</span>
                                @if($item->variant_name)
                                    <span class="text-gray-500">({{ $item->variant_name }})</span>
                                @endif
                                <span class="text-gray-500">x {{ $item->quantity }}</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ format_price($item->subtotal) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pricing Breakdown -->
            <div class="mt-6 pt-4 border-t border-gray-100 space-y-2 text-sm text-right">
                <div class="flex justify-between text-gray-600">
                    <span>Product Subtotal:</span>
                    <span>{{ format_price($order->subtotal) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Delivery Charge:</span>
                    <span>{{ format_price($order->delivery_charge) }}</span>
                </div>
                <div class="flex justify-between font-extrabold text-base text-gray-900 border-t pt-2">
                    <span>Grand Total:</span>
                    <span class="text-brand-700">{{ format_price($order->grand_total) }}</span>
                </div>
            </div>

            <!-- Live Chat & Admin Support Box for Order Success Page -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-left space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-brand-50 p-4 rounded-2xl border border-brand-100">
                    <div>
                        <h4 class="font-bold text-brand-900 text-xs flex items-center gap-2">
                            <i class="fa-solid fa-headset text-brand-600"></i> এডমিন সাপোর্ট ও চ্যাট ইনবক্স
                        </h4>
                        <p class="text-[11px] text-brand-700 mt-0.5">আপনার অর্ডারের যে কোনো পরিবর্তন বা তথ্য জানতে এখান থেকেই মেসেজ পাঠান অথবা হোয়াটসঅ্যাপ করুন।</p>
                    </div>
                    @php
                        $waSuccessUrl = "https://wa.me/8801700000000?text=" . urlencode("আসসালামু আলাইকুম, আমি মাত্র অর্ডার #" . $order->order_number . " সম্পন্ন করেছি।");
                    @endphp
                    <a href="{{ $waSuccessUrl }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shrink-0 shadow-sm">
                        <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Chat
                    </a>
                </div>

                <!-- Chat Timeline & Form -->
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 space-y-3">
                    <h5 class="font-bold text-gray-900 text-xs flex items-center justify-between">
                        <span><i class="fa-solid fa-comments text-brand-600 mr-1"></i> এডমিনের সাথে লাইভ বার্তা আদান-প্রদান</span>
                        <span class="text-[11px] text-gray-500 font-normal">{{ count($order->messages) }} টি মেসেজ</span>
                    </h5>

                    <div class="space-y-2 max-h-60 overflow-y-auto p-3 bg-white rounded-xl border border-gray-100 text-xs">
                        @forelse($order->messages as $msg)
                            <div class="flex flex-col {{ $msg->sender_type === 'customer' ? 'items-end' : 'items-start' }}">
                                <div class="flex items-center gap-1.5 text-[10px] text-gray-400 mb-0.5">
                                    <span class="font-bold {{ $msg->sender_type === 'customer' ? 'text-brand-700' : 'text-emerald-700' }}">
                                        {{ $msg->sender_name }} ({{ $msg->sender_type === 'customer' ? 'আপনি' : 'এডমিন' }})
                                    </span>
                                    <span>• {{ $msg->created_at->format('d M, h:i A') }}</span>
                                </div>
                                <div class="max-w-[85%] p-2.5 rounded-xl text-xs font-medium {{ $msg->sender_type === 'customer' ? 'bg-brand-600 text-white rounded-tr-none shadow-sm' : 'bg-emerald-50 text-emerald-950 border border-emerald-200 rounded-tl-none shadow-sm' }}">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-xs text-gray-400">
                                এডমিনের কোনো বার্তা এলে বা আপনি বার্তা পাঠালে তা এখানে প্রদর্শিত হবে।
                            </div>
                        @endforelse
                    </div>

                    <form action="{{ route('order.track.message', $order->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="message" required placeholder="এডমিনকে মেসেজ লিখুন..." class="flex-1 bg-white border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-1 shadow-sm whitespace-nowrap">
                            <i class="fa-solid fa-paper-plane"></i> পাঠান
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex justify-center gap-4">
            <a href="{{ route('order.track') }}?query={{ $order->order_number }}" class="bg-brand-600 text-white font-bold px-6 py-3 rounded-full hover:bg-brand-700 transition text-sm">
                <i class="fa-solid fa-location-dot mr-1"></i> Track Order
            </a>
            <a href="{{ route('home') }}" class="bg-gray-200 text-gray-800 font-bold px-6 py-3 rounded-full hover:bg-gray-300 transition text-sm">
                Go to Home
            </a>
        </div>

    </div>
</div>
@endsection
