@extends('layouts.app')

@section('title', 'মাই অ্যাকাউন্ট - খাঁটি বাজার')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="w-full md:w-64 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm h-fit">
                <div class="text-center pb-6 border-b mb-4">
                    <div class="w-16 h-16 bg-brand-100 text-brand-700 font-bold rounded-full flex items-center justify-center text-2xl mx-auto mb-2 border-2 border-brand-200">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h3 class="font-bold text-gray-900 text-base">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $user->phone }}</p>
                </div>

                <nav class="space-y-1 text-sm font-semibold">
                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 rounded-xl bg-brand-50 text-brand-700 font-bold">
                        <i class="fa-solid fa-gauge mr-2"></i> ড্যাশবোর্ড
                    </a>
                    <a href="{{ route('customer.orders') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-box-archive mr-2"></i> আমার অর্ডারসমূহ ({{ $totalOrders }})
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-red-600 font-bold hover:bg-red-50 rounded-xl transition">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> লগআউট
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Account Dashboard -->
            <div class="flex-1 space-y-6">
                
                <!-- Recent Orders Summary -->
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6 pb-2 border-b">
                        <h2 class="text-lg font-bold text-gray-900">সাম্প্রতিক অর্ডারসমূহ</h2>
                        <a href="{{ route('customer.orders') }}" class="text-xs font-bold text-brand-600 hover:underline">সব অর্ডার দেখুন</a>
                    </div>

                    @if(count($recentOrders) > 0)
                        <div class="space-y-3">
                            @foreach($recentOrders as $order)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 gap-3">
                                    <div>
                                        <span class="text-xs font-mono font-bold text-brand-700 block">#{{ $order->order_number }}</span>
                                        <span class="text-xs text-gray-500">{{ $order->created_at->format('d M, Y') }} — {{ $order->items()->count() }} টি পণ্য</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold px-3 py-1 rounded-full uppercase
                                            @if($order->order_status == 'delivered') bg-green-100 text-green-700
                                            @elseif($order->order_status == 'cancelled') bg-red-100 text-red-700
                                            @else bg-amber-100 text-amber-700 @endif">
                                            {{ $order->order_status }}
                                        </span>
                                        <span class="font-extrabold text-sm text-gray-900">{{ format_price($order->grand_total) }}</span>
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="bg-white border text-xs font-bold px-3 py-1.5 rounded-xl text-gray-700 hover:bg-gray-100">ডিটেইলস</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500 text-center py-6">আপনি এখনও কোনো অর্ডার করেননি।</p>
                    @endif
                </div>

                <!-- Update Profile Form -->
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b">প্রোফাইল তথ্য পরিবর্তন</h2>

                    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">পূর্ণ নাম</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">মোবাইল নম্বর</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">ইমেইল ঠিকানা</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                            </div>
                        </div>

                        <button type="submit" class="bg-brand-600 text-white font-bold text-xs py-3 px-6 rounded-xl hover:bg-brand-700 transition">
                            তথ্য আপডেট করুন
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
