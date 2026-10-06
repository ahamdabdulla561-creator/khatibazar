@extends('layouts.app')

@section('title', 'অর্ডার ডিটেইলস #' . $order->order_number . ' - খাঁটি বাজার')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b mb-6 gap-4">
                <div>
                    <span class="text-xs text-gray-400 block">অর্ডার আইডি</span>
                    <h1 class="text-xl font-mono font-bold text-brand-700">#{{ $order->order_number }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full uppercase
                        @if($order->order_status == 'delivered') bg-green-100 text-green-700
                        @elseif($order->order_status == 'cancelled') bg-red-100 text-red-700
                        @else bg-amber-100 text-amber-700 @endif">
                        অর্ডার স্ট্যাটাস: {{ $order->order_status }}
                    </span>
                    <a href="{{ route('customer.orders') }}" class="text-xs text-gray-500 hover:underline">তালিকায় ফিরে যান</a>
                </div>
            </div>

            <!-- Customer Shipping Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-2xl mb-8 text-sm">
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">গ্রাহকের তথ্য:</h3>
                    <p class="text-gray-700"><span class="font-semibold">নাম:</span> {{ $order->customer_name }}</p>
                    <p class="text-gray-700"><span class="font-semibold">ফোন:</span> {{ $order->customer_phone }}</p>
                    @if($order->customer_email)
                        <p class="text-gray-700"><span class="font-semibold">ইমেইল:</span> {{ $order->customer_email }}</p>
                    @endif
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-2">ডেলিভারি ঠিকানা ও কুরিয়ার:</h3>
                    <p class="text-gray-700">{{ $order->shipping_address }}</p>
                    <p class="text-gray-700">{{ $order->shipping_upazila }}, {{ $order->shipping_district }}</p>
                    <p class="text-gray-700 font-semibold mt-1">পেমেন্ট মেথড: {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})</p>
                    
                    @if($order->courier_name || $order->courierService)
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <span class="text-xs font-bold text-gray-500 block">কুরিয়ার সার্ভিস:</span>
                            <span class="font-extrabold text-brand-700 text-sm flex items-center gap-1.5">
                                🚚 {{ $order->courier_name ?? $order->courierService->name }}
                            </span>
                            @if($order->courier_tracking_id)
                                <p class="text-xs text-gray-600 font-mono mt-0.5">
                                    ট্র্যাকিং আইডি: <strong>{{ $order->courier_tracking_id }}</strong>
                                </p>
                                @if($order->courierService && $order->courierService->getTrackingUrl($order->courier_tracking_id))
                                    <a href="{{ $order->courierService->getTrackingUrl($order->courier_tracking_id) }}" target="_blank" class="inline-block mt-1.5 text-xs font-bold text-blue-600 hover:underline">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> কুরিয়ার ওয়েবসাইটে ট্র্যাক করুন &rarr;
                                    </a>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Products List -->
            <h3 class="font-bold text-gray-900 text-base mb-3">অর্ডারের পণ্যসমূহ:</h3>
            <div class="space-y-3 mb-8">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl text-sm border border-gray-100">
                        <div>
                            <span class="font-bold text-gray-900 block">{{ $item->product_name }}</span>
                            @if($item->variant_name)
                                <span class="text-xs text-gray-500">ভ্যারিয়েন্ট: {{ $item->variant_name }}</span>
                            @endif
                            <span class="text-xs text-gray-500 block">একক মূল্য: {{ format_price($item->unit_price) }} x {{ $item->quantity }} টি</span>
                        </div>
                        <span class="font-extrabold text-gray-900">{{ format_price($item->subtotal) }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Grand Total Breakdown -->
            <div class="border-t pt-4 space-y-2 text-sm text-right max-w-xs ml-auto">
                <div class="flex justify-between text-gray-600">
                    <span>সাবটোটাল:</span>
                    <span class="font-bold text-gray-900">{{ format_price($order->subtotal) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>ডেলিভারি চার্জ:</span>
                    <span class="font-bold text-gray-900">{{ format_price($order->delivery_charge) }}</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-gray-900 border-t pt-2">
                    <span>সর্বমোট:</span>
                    <span class="text-brand-700">{{ format_price($order->grand_total) }}</span>
                </div>
            </div>

            <!-- Order Support & Messaging Box -->
            <div class="mt-8 border-t pt-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-brand-50 p-4 rounded-2xl border border-brand-100">
                    <div>
                        <h4 class="font-bold text-brand-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-headset text-brand-600"></i> এই অর্ডারটি নিয়ে এডমিনের সাথে যোগাযোগ করুন
                        </h4>
                        <p class="text-xs text-brand-700 mt-0.5">অর্ডার সম্পর্কিত যেকোনো জিজ্ঞাসা বা সমস্যার জন্য আমাদের সরাসরি মেসেজ অথবা হোয়াটসঅ্যাপ করুন।</p>
                    </div>
                    @php
                        $adminWaText = urlencode("আসসালামু আলাইকুম, আমি খাঁটি বাজার থেকে বলছি। আমার অর্ডার #" . $order->order_number . " নিয়ে জানতে চাই।");
                    @endphp
                    <a href="https://wa.me/8801700000000?text={{ $adminWaText }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow transition whitespace-nowrap">
                        <i class="fa-brands fa-whatsapp text-sm"></i> সরাসরি WhatsApp চ্যাট
                    </a>
                </div>

                <!-- Chat Box -->
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 space-y-4">
                    <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-comments text-brand-600"></i> এডমিন সাপোর্ট চ্যাট ইনবক্স
                    </h4>

                    <!-- Chat Messages Timeline -->
                    <div class="space-y-3 max-h-72 overflow-y-auto p-3 bg-white rounded-xl border border-gray-100">
                        @forelse($order->messages as $msg)
                            <div class="flex flex-col {{ $msg->sender_type === 'customer' ? 'items-end' : 'items-start' }}">
                                <div class="flex items-center gap-1.5 text-[11px] text-gray-500 mb-1">
                                    <span class="font-bold {{ $msg->sender_type === 'customer' ? 'text-brand-700' : 'text-emerald-700' }}">
                                        {{ $msg->sender_name }} ({{ $msg->sender_type === 'customer' ? 'আপনি' : 'এডমিন সাপোর্ট' }})
                                    </span>
                                    <span>• {{ $msg->created_at->format('d M, Y h:i A') }}</span>
                                </div>
                                <div class="max-w-[85%] p-3 rounded-2xl text-xs font-medium leading-relaxed {{ $msg->sender_type === 'customer' ? 'bg-brand-600 text-white rounded-tr-none shadow-sm' : 'bg-emerald-50 text-emerald-950 border border-emerald-200 rounded-tl-none shadow-sm' }}">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-xs text-gray-400">
                                <i class="fa-regular fa-comment-dots text-2xl mb-1 block"></i>
                                আপনি এখনও কোনো মেসেজ পাঠাননি। নিচে মেসেজ লিখে এডমিনকে পাঠান।
                            </div>
                        @endforelse
                    </div>

                    <!-- Send Message Form -->
                    <form action="{{ route('customer.orders.message', $order->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="message" placeholder="আপনার প্রশ্ন বা মেসেজ লিখুন..." required class="flex-1 bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow transition flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fa-solid fa-paper-plane"></i> পাঠান
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
