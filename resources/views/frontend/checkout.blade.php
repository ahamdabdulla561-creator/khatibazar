@extends('layouts.app')

@section('title', 'অর্ডার সম্পন্ন করুন - খাঁটি বাজার')

@section('content')
<div class="bg-gray-50 py-8" x-data="checkoutComponent({{ $cart->items->sum(fn($i) => $i->price * $i->quantity) }}, {{ $insideDhakaCharge }}, {{ $outsideDhakaCharge }})">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-brand-900">আপনার অর্ডার সম্পন্ন করুন</h1>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">নিচের ফর্মে আপনার নাম, মোবাইল নম্বর এবং সম্পূর্ণ ঠিকানা সঠিকভাবে পূরণ করে অর্ডার কনফার্ম করুন।</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" @submit="submitting = true" id="checkoutForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Shipping Information Form -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-brand-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b flex items-center gap-2">
                            <i class="fa-solid fa-address-card text-brand-600"></i> ডেলিভারি ও গ্রাহকের তথ্য
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1">আপনার সম্পূর্ণ নাম <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" required placeholder="যেমন: মোঃ আব্দুল করিম" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                @error('customer_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Mobile Number -->
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1">মোবাইল নম্বর <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->check() ? auth()->user()->phone : '') }}" required placeholder="০১৭XXXXXXXX (সচল নম্বর দিন)" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                @error('customer_phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- District -->
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1">জেলা <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_district" value="{{ old('shipping_district') }}" required placeholder="যেমন: ঢাকা / ময়মনসিংহ / বগুড়া" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                @error('shipping_district') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Upazila/Area -->
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1">উপজেলা / থানা / এলাকা <span class="text-red-500">*</span></label>
                                <input type="text" name="shipping_upazila" value="{{ old('shipping_upazila') }}" required placeholder="যেমন: সাভার / গফরগাঁও / সদর" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                @error('shipping_upazila') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Full Address -->
                            <div class="md:col-span-2">
                                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1">সম্পূর্ণ ঠিকানা (বিস্তারিত) <span class="text-red-500">*</span></label>
                                <textarea name="shipping_address" rows="3" required placeholder="গ্রাম বা মহল্লার নাম, বাজার/রাস্তা, বাড়ির নম্বর অথবা পরিচিত কোনো স্থানের নাম লিখুন..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nationwide Delivery Charge Info (No Inside/Outside Dhaka split) -->
                    <div class="bg-white rounded-3xl p-6 border border-brand-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b flex items-center gap-2">
                            <i class="fa-solid fa-truck-ramp-box text-brand-600"></i> ডেলিভারি চার্জ
                        </h2>

                        <input type="hidden" name="delivery_area" value="outside_dhaka">

                        <div class="p-4 rounded-2xl border-2 border-brand-600 bg-brand-50/60 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <div>
                                    <span class="font-extrabold text-sm sm:text-base text-brand-900 block">সারা বাংলাদেশে ক্যাশ অন ডেলিভারি ১৫০ টাকা</span>
                                    <span class="text-xs text-brand-700 font-semibold">ডেলিভারি চার্জ: ৳১৫০ (পণ্য হাতে পেয়ে মূল্য পরিশোধের সুবিধা)</span>
                                </div>
                            </div>
                            <span class="font-black text-base sm:text-lg text-brand-700 shrink-0">৳ ১৫০</span>
                        </div>
                    </div>


                    <!-- Payment Method Selection (COD, bKash, Nagad, Rocket) -->
                    <div class="bg-white rounded-3xl p-6 border border-brand-100 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-brand-600"></i> পেমেন্ট পদ্ধতি নির্বাচন করুন
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Cash on Delivery Option -->
                            @if(site_setting('cod_status', '1') == '1')
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center justify-between" :class="paymentMethod === 'cod' ? 'border-brand-600 bg-brand-50/50' : 'border-gray-200 hover:border-gray-300'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-brand-600 focus:ring-brand-500">
                                    <div>
                                        <span class="font-extrabold text-sm text-gray-900 block">ক্যাশ অন ডেলিভারি</span>
                                        <span class="text-xs text-gray-500">পণ্য হাতে পেয়ে টাকা পরিশোধ করুন</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-hand-holding-dollar text-2xl text-brand-600"></i>
                            </label>
                            @endif

                            <!-- bKash Option -->
                            @if(site_setting('bkash_status', '1') == '1')
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center justify-between" :class="paymentMethod === 'bkash' ? 'border-pink-600 bg-pink-50/60' : 'border-gray-200 hover:border-pink-200'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="bkash" x-model="paymentMethod" class="text-pink-600 focus:ring-pink-500">
                                    <div>
                                        <span class="font-extrabold text-sm text-pink-900 block flex items-center gap-1.5">
                                            বিকাশ <span class="text-[10px] bg-pink-600 text-white font-bold px-1.5 py-0.5 rounded">bKash</span>
                                        </span>
                                        <span class="text-xs text-gray-500">বিকাশের মাধ্যমে সেন্ড মানি / পেমেন্ট করুন</span>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-pink-600 text-white font-extrabold flex items-center justify-center text-xs shadow-sm">bk</div>
                            </label>
                            @endif

                            <!-- Nagad Option -->
                            @if(site_setting('nagad_status', '1') == '1')
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center justify-between" :class="paymentMethod === 'nagad' ? 'border-orange-500 bg-orange-50/60' : 'border-gray-200 hover:border-orange-200'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="nagad" x-model="paymentMethod" class="text-orange-600 focus:ring-orange-500">
                                    <div>
                                        <span class="font-extrabold text-sm text-orange-900 block flex items-center gap-1.5">
                                            নগদ <span class="text-[10px] bg-orange-600 text-white font-bold px-1.5 py-0.5 rounded">Nagad</span>
                                        </span>
                                        <span class="text-xs text-gray-500">নগদের মাধ্যমে সেন্ড মানি / পেমেন্ট করুন</span>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-orange-500 text-white font-extrabold flex items-center justify-center text-xs shadow-sm">ng</div>
                            </label>
                            @endif

                            <!-- Rocket Option -->
                            @if(site_setting('rocket_status', '1') == '1')
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center justify-between" :class="paymentMethod === 'rocket' ? 'border-purple-600 bg-purple-50/60' : 'border-gray-200 hover:border-purple-200'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="rocket" x-model="paymentMethod" class="text-purple-600 focus:ring-purple-500">
                                    <div>
                                        <span class="font-extrabold text-sm text-purple-900 block flex items-center gap-1.5">
                                            রকেট <span class="text-[10px] bg-purple-600 text-white font-bold px-1.5 py-0.5 rounded">Rocket</span>
                                        </span>
                                        <span class="text-xs text-gray-500">রকেটের মাধ্যমে সেন্ড মানি / পেমেন্ট করুন</span>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-purple-600 text-white font-extrabold flex items-center justify-center text-xs shadow-sm">ro</div>
                            </label>
                            @endif
                        </div>

                        <!-- Dynamic MFS Payment Form Box (When bKash, Nagad, or Rocket is selected) -->
                        <div x-show="paymentMethod !== 'cod'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4 p-5 rounded-2xl border border-dashed space-y-4" :class="{
                            'bg-pink-50/80 border-pink-300 text-pink-900': paymentMethod === 'bkash',
                            'bg-orange-50/80 border-orange-300 text-orange-900': paymentMethod === 'nagad',
                            'bg-purple-50/80 border-purple-300 text-purple-900': paymentMethod === 'rocket'
                        }" x-cloak>
                            
                            <!-- Payment Instructions Header -->
                            <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border shadow-sm">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-sm shrink-0" :class="{
                                    'bg-pink-600': paymentMethod === 'bkash',
                                    'bg-orange-500': paymentMethod === 'nagad',
                                    'bg-purple-600': paymentMethod === 'rocket'
                                }">
                                    <span x-text="paymentMethod.toUpperCase().substring(0, 2)"></span>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-sm text-gray-900">
                                        <span x-text="paymentMethod === 'bkash' ? 'বিকাশ (bKash)' : (paymentMethod === 'nagad' ? 'নগদ (Nagad)' : 'রকেট (Rocket)')"></span> <span class="text-xs text-gray-600 font-normal">অ্যাকাউন্টের তথ্য:</span>
                                    </h4>
                                    <div class="text-base font-black text-gray-900 font-mono tracking-wide mt-0.5">
                                        <span x-text="paymentMethod === 'bkash' ? '{{ site_setting('bkash_number', '01711-000000') }}' : (paymentMethod === 'nagad' ? '{{ site_setting('nagad_number', '01822-000000') }}' : '{{ site_setting('rocket_number', '01933-000000-8') }}')"></span>
                                        <span class="text-xs font-bold text-white px-2 py-0.5 rounded-full ml-1" :class="{
                                            'bg-pink-600': paymentMethod === 'bkash',
                                            'bg-orange-500': paymentMethod === 'nagad',
                                            'bg-purple-600': paymentMethod === 'rocket'
                                        }" x-text="paymentMethod === 'bkash' ? '{{ site_setting('bkash_type', 'Personal') }}' : (paymentMethod === 'nagad' ? '{{ site_setting('nagad_type', 'Personal') }}' : '{{ site_setting('rocket_type', 'Personal') }}')"></span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                        <span x-text="paymentMethod === 'bkash' ? '{{ site_setting('bkash_instruction', 'উপরের বিকাশ পার্সোনাল নম্বরে সেন্ড মানি (Send Money) করুন।') }}' : (paymentMethod === 'nagad' ? '{{ site_setting('nagad_instruction', 'উপরের নগদ পার্সোনাল নম্বরে সেন্ড মানি (Send Money) করুন।') }}' : '{{ site_setting('rocket_instruction', 'উপরের রকেট পার্সোনাল নম্বরে সেন্ড মানি (Send Money) করুন।') }}')"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- MFS Form Inputs -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Sender Mobile Number -->
                                <div>
                                    <label class="block text-xs font-extrabold text-gray-800 mb-1">
                                        প্রেরকের মোবাইল নম্বর (যে নম্বর থেকে টাকা পাঠিয়েছেন) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="sender_number" :required="paymentMethod !== 'cod'" placeholder="যেমন: 017XXXXXXXX" value="{{ old('sender_number') }}" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-sm font-semibold focus:ring-2 focus:ring-brand-500">
                                    @error('sender_number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                <!-- Transaction ID -->
                                <div>
                                    <label class="block text-xs font-extrabold text-gray-800 mb-1">
                                        ট্রানজ্যাকশন আইডি (Transaction ID / TrxID) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="transaction_id" :required="paymentMethod !== 'cod'" placeholder="যেমন: TRX89237162" value="{{ old('transaction_id') }}" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-sm font-mono font-bold uppercase focus:ring-2 focus:ring-brand-500">
                                    @error('transaction_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                <!-- Payment Screenshot Upload Field -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-extrabold text-gray-800 mb-1">
                                        পেমেন্টের স্ক্রিনশট আপলোড করুন (ঐচ্ছিক)
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <input type="file" name="payment_screenshot" accept="image/*" @change="previewScreenshot" x-ref="screenshotInput" class="w-full text-xs text-gray-500 bg-white border border-gray-300 rounded-xl p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                                    </div>
                                    <span class="text-[11px] text-gray-500 mt-1 block">সমর্থিত ফরম্যাট: JPG, PNG, WEBP (সর্বোচ্চ ৫ মেগাবাইট)</span>
                                    @error('payment_screenshot') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                                    <!-- Screenshot Live Preview -->
                                    <template x-if="screenshotPreview">
                                        <div class="mt-3 relative inline-block border-2 border-brand-500 rounded-2xl p-1 bg-white shadow-md">
                                            <img :src="screenshotPreview" class="h-28 max-w-full object-contain rounded-xl">
                                            <button type="button" @click="removeScreenshot()" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shadow-lg hover:bg-red-700">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Order Summary Column -->
                <div>
                    <div class="bg-white rounded-3xl p-6 border border-brand-100 shadow-sm sticky top-24">
                        <h2 class="font-bold text-gray-900 text-lg mb-4 pb-2 border-b">অর্ডারের বিবরণ (পণ্যসমূহ)</h2>

                        <div class="space-y-3 max-h-60 overflow-y-auto pr-1 mb-4">
                            @foreach($cart->items as $item)
                                <div class="flex items-center justify-between gap-3 text-xs border-b pb-2">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $item->product->image_url }}" class="w-10 h-10 object-cover rounded-lg">
                                        <div>
                                            <span class="font-bold text-gray-800 block line-clamp-1">{{ $item->product->name }}</span>
                                            @if($item->variant)
                                                <span class="text-gray-500">({{ $item->variant->name }})</span>
                                            @endif
                                            <span class="text-gray-500">পরিমাণ: {{ $item->quantity }} টি</span>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900">{{ format_price($item->price * $item->quantity) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-3 text-sm mb-6 border-t pt-4">
                            <div class="flex justify-between text-gray-600">
                                <span>পণ্যের মোট মূল্য:</span>
                                <span class="font-bold text-gray-900">৳ <span x-text="subtotal.toLocaleString()"></span></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>ডেলিভারি চার্জ:</span>
                                <span class="font-bold text-brand-600">৳ <span x-text="shippingFee.toLocaleString()"></span></span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-base font-extrabold text-gray-900">
                                <span>সর্বমোট প্রদেয় বিল:</span>
                                <span class="text-brand-700 text-xl">৳ <span x-text="grandTotal.toLocaleString()"></span></span>
                            </div>
                        </div>

                        <!-- Submit Order Button -->
                        <button 
                            type="submit" 
                            :disabled="submitting"
                            class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-4 rounded-2xl shadow-xl hover:shadow-brand-600/30 transition text-center text-base flex items-center justify-center gap-2 disabled:opacity-50"
                        >
                            <span x-show="!submitting"><i class="fa-solid fa-circle-check"></i> অর্ডার কনফার্ম করুন</span>
                            <span x-show="submitting" x-cloak><i class="fa-solid fa-spinner fa-spin"></i> অর্ডার প্রসেস হচ্ছে...</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<script>
    function checkoutComponent(subtotal, insideCharge, outsideCharge) {
        return {
            subtotal: subtotal,
            insideCharge: insideCharge,
            outsideCharge: outsideCharge,
            deliveryArea: 'inside_dhaka',
            paymentMethod: 'cod',
            shippingFee: insideCharge,
            grandTotal: subtotal + insideCharge,
            submitting: false,
            screenshotPreview: null,

            recalculateTotal() {
                if (this.deliveryArea === 'inside_dhaka') {
                    this.shippingFee = this.insideCharge;
                } else {
                    this.shippingFee = this.outsideCharge;
                }
                this.grandTotal = this.subtotal + this.shippingFee;
            },

            previewScreenshot(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.screenshotPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.screenshotPreview = null;
                }
            },

            removeScreenshot() {
                this.screenshotPreview = null;
                if (this.$refs.screenshotInput) {
                    this.$refs.screenshotInput.value = '';
                }
            }
        }
    }
</script>
@endsection
