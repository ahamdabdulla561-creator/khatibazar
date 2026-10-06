@extends('layouts.admin')

@section('title', 'অর্ডার ডিটেইলস #' . $order->order_number . ' - খাঁটি বাজার অ্যাডমিন')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-xs font-mono text-gray-400">অর্ডার নম্বর</span>
            <h1 class="text-2xl font-mono font-bold text-brand-700">#{{ $order->order_number }}</h1>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-500 hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> তালিকায় ফিরে যান</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Items & Customer Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Customer Details Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                <h2 class="font-bold text-gray-900 text-base pb-2 border-b flex items-center gap-2">
                    <i class="fa-solid fa-user text-brand-600"></i> কাস্টমার ও ডেলিভারি তথ্য
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block">কাস্টমার নাম:</span>
                        <span class="font-bold text-gray-900 text-sm">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">মোবাইল নম্বর:</span>
                        <span class="font-bold text-gray-900 text-sm flex items-center gap-2">
                            {{ $order->customer_phone }}
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                                if (strpos($cleanPhone, '88') !== 0 && strlen($cleanPhone) == 11) {
                                    $cleanPhone = '88' . $cleanPhone;
                                }
                                $waText = urlencode("আসসালামু আলাইকুম " . $order->customer_name . " ভাই, আপনার খাঁটি বাজার অর্ডার #" . $order->order_number . " নিয়ে যোগাযোগের জন্য বার্তা দেয়া হলো।");
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] px-2.5 py-1 rounded-full font-bold shadow-sm transition" title="হোয়াটসঅ্যাপে সরাসরি মেসেজ দিন">
                                <i class="fa-brands fa-whatsapp text-xs"></i> WhatsApp
                            </a>
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">ইমেইল:</span>
                        <span class="font-medium text-gray-800">{{ $order->customer_email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">ডেলিভারি এরিয়া:</span>
                        <span class="font-bold text-gray-800">{{ $order->delivery_area == 'inside_dhaka' ? 'ঢাকার ভেতরে' : 'ঢাকার বাইরে' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">কুরিয়ার সার্ভিস:</span>
                        <span class="font-bold text-brand-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-truck"></i> {{ $order->courier_name ?? ($order->courierService ? $order->courierService->name : 'নির্ধারিত হয়নি') }}
                        </span>
                        @if($order->courier_tracking_id)
                            <span class="text-[11px] font-mono text-gray-600 block">Trk: {{ $order->courier_tracking_id }}</span>
                        @endif
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-gray-400 block">সম্পূর্ণ ডেলিভারি ঠিকানা:</span>
                        <span class="font-semibold text-gray-900">{{ $order->shipping_address }}, {{ $order->shipping_upazila }}, {{ $order->shipping_district }}</span>
                    </div>
                    @if($order->notes)
                        <div class="sm:col-span-2 bg-amber-50 p-3 rounded-xl border border-amber-200 text-amber-800">
                            <span class="font-bold block">অর্ডার নোট:</span>
                            <span>{{ $order->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment & MFS Verification Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <h2 class="font-bold text-gray-900 text-base pb-2 border-b flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-brand-600"></i> পেমেন্ট ও ট্রানজ্যাকশন তথ্য
                    </span>
                    <span class="text-xs px-2.5 py-1 rounded-full font-bold uppercase {{ $order->payment_method === 'cod' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ strtoupper($order->payment_method) }}
                    </span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block mb-1">পেমেন্ট মেথড:</span>
                        <span class="font-extrabold text-gray-900 text-sm uppercase flex items-center gap-1.5">
                            @if($order->payment_method === 'bkash')
                                <span class="bg-pink-600 text-white font-bold text-[10px] px-2 py-0.5 rounded">bKash (বিকাশ)</span>
                            @elseif($order->payment_method === 'nagad')
                                <span class="bg-orange-500 text-white font-bold text-[10px] px-2 py-0.5 rounded">Nagad (নগদ)</span>
                            @elseif($order->payment_method === 'rocket')
                                <span class="bg-purple-600 text-white font-bold text-[10px] px-2 py-0.5 rounded">Rocket (রকেট)</span>
                            @else
                                <span class="bg-gray-700 text-white font-bold text-[10px] px-2 py-0.5 rounded">Cash on Delivery</span>
                            @endif
                        </span>
                    </div>

                    @if($order->payment_method !== 'cod')
                    <div>
                        <span class="text-gray-400 block mb-1">প্রেরকের মোবাইল নম্বর:</span>
                        <span class="font-bold text-gray-900 text-sm font-mono">{{ $order->sender_number ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="text-gray-400 block mb-1">ট্রানজ্যাকশন আইডি (TrxID):</span>
                        <span class="font-bold text-brand-700 text-sm font-mono tracking-wider bg-brand-50 px-2 py-1 rounded border border-brand-200 inline-block">
                            {{ $order->transaction_id ?? 'N/A' }}
                        </span>
                    </div>
                    @endif
                </div>

                @if($order->payment_screenshot)
                    <div class="mt-4 pt-4 border-t" x-data="{ showModal: false }">
                        <span class="text-xs font-bold text-gray-700 block mb-2">কাস্টমারের পেমেন্ট স্ক্রিনশট:</span>
                        <div class="relative inline-block border-2 border-brand-500 rounded-2xl overflow-hidden shadow-md group cursor-pointer" @click="showModal = true">
                            <img src="{{ asset('storage/' . $order->payment_screenshot) }}" class="h-40 max-w-full object-contain bg-gray-50 group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> বড় করে দেখুন
                            </div>
                        </div>

                        <!-- Screenshot Modal Lightbox -->
                        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @keydown.escape.window="showModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-2xl p-2 overflow-hidden shadow-2xl" @click.away="showModal = false">
                                <button @click="showModal = false" class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm z-10 shadow-lg hover:bg-red-700">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <img src="{{ asset('storage/' . $order->payment_screenshot) }}" class="max-h-[85vh] w-auto mx-auto object-contain rounded-xl">
                            </div>
                        </div>
                    </div>
                @elseif($order->payment_method !== 'cod')
                    <div class="mt-3 bg-amber-50 p-2.5 rounded-xl border border-amber-200 text-xs text-amber-800 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> কাস্টমার কোনো পেমেন্ট স্ক্রিনশট আপলোড করেনি।
                    </div>
                @endif
            </div>

            <!-- Products Table Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <h2 class="font-bold text-gray-900 text-base pb-2 border-b flex items-center gap-2">
                    <i class="fa-solid fa-box text-brand-600"></i> অর্ডারের পণ্যসমূহ
                </h2>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-brand-100 text-brand-700 font-bold rounded-xl flex items-center justify-center">
                                    {{ $item->quantity }}x
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 text-sm block">{{ $item->product_name }}</span>
                                    @if($item->variant_name)
                                        <span class="text-gray-500">ভ্যারিয়েন্ট: {{ $item->variant_name }}</span>
                                    @endif
                                    <span class="text-gray-400 font-mono block">SKU: {{ $item->sku ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-sm text-gray-900 block">{{ format_price($item->subtotal) }}</span>
                                <span class="text-gray-500">({{ format_price($item->unit_price) }} / প্রতি ইউনিট)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Customer Chat & Inbox Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <h2 class="font-bold text-gray-900 text-base pb-2 border-b flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-comments text-brand-600"></i> কাস্টমার মেসেজ ও চ্যাট ইনবক্স
                    </span>
                    <span class="text-xs bg-brand-50 text-brand-700 font-bold px-2.5 py-1 rounded-full">
                        {{ count($order->messages) }} টি মেসেজ
                    </span>
                </h2>

                <!-- Chat Timeline -->
                <div class="space-y-3 max-h-80 overflow-y-auto p-3 bg-gray-50/70 rounded-2xl border border-gray-100">
                    @forelse($order->messages as $msg)
                        <div class="flex flex-col {{ $msg->sender_type === 'admin' ? 'items-end' : 'items-start' }}">
                            <div class="flex items-center gap-1.5 text-[11px] text-gray-500 mb-1">
                                <span class="font-bold {{ $msg->sender_type === 'admin' ? 'text-brand-700' : 'text-slate-700' }}">
                                    {{ $msg->sender_name }} ({{ $msg->sender_type === 'admin' ? 'এডমিন' : 'কাস্টমার' }})
                                </span>
                                <span>• {{ $msg->created_at->format('d M, Y h:i A') }}</span>
                            </div>
                            <div class="max-w-[85%] p-3 rounded-2xl text-xs font-medium leading-relaxed {{ $msg->sender_type === 'admin' ? 'bg-brand-600 text-white rounded-tr-none shadow-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none shadow-sm' }}">
                                {{ $msg->message }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-gray-400">
                            <i class="fa-regular fa-comment-dots text-2xl mb-1 block"></i>
                            এই অর্ডারের জন্য এখনও কোনো মেসেজ আদান-প্রদান হয়নি।
                        </div>
                    @endforelse
                </div>

                <!-- Message Reply Form -->
                <form action="{{ route('admin.orders.message', $order->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="message" placeholder="কাস্টমারকে মেসেজ লিখুন..." required class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow transition flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-paper-plane"></i> পাঠান
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Status Update Panel -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                <h2 class="font-bold text-gray-900 text-base pb-2 border-b">অর্ডার স্ট্যাটাস আপডেট</h2>

                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">অর্ডার স্ট্যাটাস</label>
                        <select name="order_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending (পেন্ডিং)</option>
                            <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed (কনফার্মড)</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing (প্রসেসিং)</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped (শিপড)</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered (ডেলিভার্ড)</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled (বাতিল করুন)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">পেমেন্ট স্ট্যাটাস</label>
                        <select name="payment_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid (পরিশোধিত)</option>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <!-- Courier Service Selection -->
                    <div class="border-t pt-3 mt-3">
                        <label class="block text-xs font-bold text-gray-700 mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-truck-fast text-brand-600"></i> কুরিয়ার সার্ভিস নির্বাচন
                        </label>
                        <select name="courier_service_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold text-gray-800">
                            <option value="">-- কুরিয়ার সার্ভিস সিলেক্ট করুন --</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}" {{ $order->courier_service_id == $courier->id ? 'selected' : '' }}>
                                    {{ $courier->name }} {{ $courier->status === 'inactive' ? '(ইনঅ্যাক্টিভ)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Courier Tracking ID / Waybill -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">কুরিয়ার ট্র্যাকিং নম্বর (Waybill / Consignment ID)</label>
                        <input type="text" name="courier_tracking_id" value="{{ old('courier_tracking_id', $order->courier_tracking_id) }}" placeholder="e.g. STEAD123456 / CN98765" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-mono">
                        @if($order->courierService && $order->courier_tracking_id)
                            @php $trackUrl = $order->courierService->getTrackingUrl($order->courier_tracking_id); @endphp
                            @if($trackUrl)
                                <a href="{{ $trackUrl }}" target="_blank" class="text-[11px] text-blue-600 hover:underline font-bold mt-1 inline-block">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> কুরিয়ার ট্র্যাকিং পেজ খুলুন &rarr;
                                </a>
                            @endif
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-3.5 rounded-xl shadow-md transition text-sm">
                        আপডেট সংরক্ষণ করুন
                    </button>
                </form>

                <div class="border-t pt-4 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>সাবটোটাল:</span>
                        <span class="font-bold text-gray-900">{{ format_price($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>ডেলিভারি চার্জ:</span>
                        <span class="font-bold text-gray-900">{{ format_price($order->delivery_charge) }}</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-sm text-gray-900 border-t pt-2">
                        <span>সর্বমোট টাকা:</span>
                        <span class="text-brand-700 text-base">{{ format_price($order->grand_total) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
