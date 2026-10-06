@extends('layouts.admin')

@section('title', 'Add New Courier Service - Admin Panel')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-truck-medical text-brand-600"></i> Add New Courier Service
            </h1>
            <p class="text-xs text-gray-500 mt-1">Configure a courier partner for nationwide customer order shipments.</p>
        </div>
        <a href="{{ route('admin.courier-services.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">
            &larr; Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.courier-services.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Courier Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Sundarban Courier Service" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Code -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Unique Code (slug) <span class="text-gray-400 font-normal">(Auto generated if blank)</span></label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. sundarban_courier" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                @error('code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Tracking URL Template -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 mb-1">Tracking Link Template <span class="text-gray-400 font-normal">(Use {tracking_code} placeholder)</span></label>
                <input type="text" name="tracking_url_template" value="{{ old('tracking_url_template') }}" placeholder="https://steadfast.com.bd/t/{tracking_code}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                <p class="text-[11px] text-gray-400 mt-1">Example: <code>https://steadfast.com.bd/t/{tracking_code}</code> or <code>https://pathao.com/tracking/?consignment_id={tracking_code}</code></p>
                @error('tracking_url_template') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Logo Upload -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Upload Courier Logo</label>
                <input type="file" name="logo" class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-brand-500">
                @error('logo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Logo URL Fallback -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Or Logo Image URL</label>
                <input type="url" name="logo_url" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.png" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                @error('logo_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Show on site)</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 mb-1">Notes / Description</label>
                <textarea name="notes" rows="3" placeholder="Additional details or instructions for this courier service..." class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t flex justify-end gap-3">
            <a href="{{ route('admin.courier-services.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-brand-600/20 transition">Save Courier</button>
        </div>
    </form>
</div>
@endsection
