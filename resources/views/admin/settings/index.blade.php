@extends('layouts.admin')

@section('title', 'Site Settings - Khati Bajar Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">Dynamic Site Settings</h1>
        <p class="text-xs text-gray-500 mt-1">Manage website information, logo, and delivery fees</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- General Info Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">General Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Website Name</label>
                    <input type="text" name="site_name" value="{{ site_setting('site_name', 'Khati Bajar') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ site_setting('currency_symbol', '৳') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Change Website Logo</label>
                    @if(site_setting('site_logo'))
                        <img src="{{ asset('storage/' . site_setting('site_logo')) }}" class="h-12 w-auto mb-2 border p-1 rounded-xl">
                    @endif
                    <input type="file" name="site_logo" accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
                </div>
            </div>
        </div>

        <!-- Contact Details Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Contact Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Helpline Phone Number</label>
                    <input type="text" name="phone" value="{{ site_setting('phone', '01711-000000') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Official Email</label>
                    <input type="email" name="email" value="{{ site_setting('email', 'info@khatibajar.com') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Office Address</label>
                    <textarea name="address" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">{{ site_setting('address', 'Dhaka, Bangladesh') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Shipping Charges Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Delivery Charges (Shipping Fees)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Inside Dhaka Delivery Fee (৳)</label>
                    <input type="number" name="inside_dhaka_charge" value="{{ site_setting('inside_dhaka_charge', 70) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Outside Dhaka Delivery Fee (৳)</label>
                    <input type="number" name="outside_dhaka_charge" value="{{ site_setting('outside_dhaka_charge', 130) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>
            </div>
        </div>

        <!-- Payment Methods Configuration Card (bKash, Nagad, Rocket, COD) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-2 border-b">
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-brand-600"></i> Payment Gateway & MFS Controls
                </h2>
                <span class="text-xs bg-brand-50 text-brand-700 px-3 py-1 rounded-full font-bold">Admin Controlled</span>
            </div>

            <!-- bKash Settings -->
            <div class="p-4 rounded-2xl bg-pink-50/50 border border-pink-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-600 text-white font-extrabold flex items-center justify-center text-xs">bk</div>
                        <span class="font-extrabold text-pink-900 text-sm">bKash Settings (বিকাশ)</span>
                    </div>
                    <select name="bkash_status" class="bg-white border border-pink-300 rounded-xl px-3 py-1 text-xs font-bold text-pink-900">
                        <option value="1" {{ site_setting('bkash_status', '1') == '1' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                        <option value="0" {{ site_setting('bkash_status') == '0' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">bKash Phone Number</label>
                        <input type="text" name="bkash_number" value="{{ site_setting('bkash_number', '01711-000000') }}" placeholder="017XXXXXXXX" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Type</label>
                        <select name="bkash_type" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-semibold">
                            <option value="Personal" {{ site_setting('bkash_type', 'Personal') == 'Personal' ? 'selected' : '' }}>Personal (Send Money)</option>
                            <option value="Merchant" {{ site_setting('bkash_type') == 'Merchant' ? 'selected' : '' }}>Merchant (Payment)</option>
                            <option value="Agent" {{ site_setting('bkash_type') == 'Agent' ? 'selected' : '' }}>Agent (Cash In)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">bKash Payment Instructions for Customer</label>
                        <textarea name="bkash_instruction" rows="2" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-medium">{{ site_setting('bkash_instruction', 'bKash Personal নম্বর 01711-000000 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Nagad Settings -->
            <div class="p-4 rounded-2xl bg-orange-50/50 border border-orange-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-orange-500 text-white font-extrabold flex items-center justify-center text-xs">ng</div>
                        <span class="font-extrabold text-orange-900 text-sm">Nagad Settings (নগদ)</span>
                    </div>
                    <select name="nagad_status" class="bg-white border border-orange-300 rounded-xl px-3 py-1 text-xs font-bold text-orange-900">
                        <option value="1" {{ site_setting('nagad_status', '1') == '1' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                        <option value="0" {{ site_setting('nagad_status') == '0' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nagad Phone Number</label>
                        <input type="text" name="nagad_number" value="{{ site_setting('nagad_number', '01822-000000') }}" placeholder="018XXXXXXXX" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Type</label>
                        <select name="nagad_type" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-semibold">
                            <option value="Personal" {{ site_setting('nagad_type', 'Personal') == 'Personal' ? 'selected' : '' }}>Personal (Send Money)</option>
                            <option value="Merchant" {{ site_setting('nagad_type') == 'Merchant' ? 'selected' : '' }}>Merchant (Payment)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nagad Payment Instructions for Customer</label>
                        <textarea name="nagad_instruction" rows="2" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-medium">{{ site_setting('nagad_instruction', 'Nagad Personal নম্বর 01822-000000 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Rocket Settings -->
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-600 text-white font-extrabold flex items-center justify-center text-xs">ro</div>
                        <span class="font-extrabold text-purple-900 text-sm">Rocket Settings (রকেট)</span>
                    </div>
                    <select name="rocket_status" class="bg-white border border-purple-300 rounded-xl px-3 py-1 text-xs font-bold text-purple-900">
                        <option value="1" {{ site_setting('rocket_status', '1') == '1' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                        <option value="0" {{ site_setting('rocket_status') == '0' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Rocket Phone Number</label>
                        <input type="text" name="rocket_number" value="{{ site_setting('rocket_number', '01933-000000-8') }}" placeholder="019XXXXXXXX-X" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Account Type</label>
                        <select name="rocket_type" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-semibold">
                            <option value="Personal" {{ site_setting('rocket_type', 'Personal') == 'Personal' ? 'selected' : '' }}>Personal (Send Money)</option>
                            <option value="Merchant" {{ site_setting('rocket_type') == 'Merchant' ? 'selected' : '' }}>Merchant (Payment)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Rocket Payment Instructions for Customer</label>
                        <textarea name="rocket_instruction" rows="2" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-medium">{{ site_setting('rocket_instruction', 'Rocket Personal নম্বর 01933-000000-8 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Cash on Delivery Settings -->
            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-extrabold flex items-center justify-center text-xs">cod</div>
                        <span class="font-extrabold text-emerald-900 text-sm">Cash on Delivery Settings</span>
                    </div>
                    <select name="cod_status" class="bg-white border border-emerald-300 rounded-xl px-3 py-1 text-xs font-bold text-emerald-900">
                        <option value="1" {{ site_setting('cod_status', '1') == '1' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                        <option value="0" {{ site_setting('cod_status') == '0' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">COD Instructions for Customer</label>
                    <textarea name="cod_instruction" rows="2" class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-medium">{{ site_setting('cod_instruction', 'পণ্য বুঝে পেয়ে ক্যাশ টাকা পরিশোধ করুন।') }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-4 rounded-2xl shadow-xl transition text-sm">
            Save Settings
        </button>
    </form>
</div>
@endsection
