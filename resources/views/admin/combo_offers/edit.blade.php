@extends('layouts.admin')

@section('title', 'Edit Combo Offer Card - Admin Panel')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Combo Offer Card: {{ $offer->title }}
            </h1>
            <p class="text-xs text-gray-500 mt-1">Update image, badge text, offer price, or redirect link.</p>
        </div>
        <a href="{{ route('admin.combo-offers.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">
            &larr; Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.combo-offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 mb-1">Offer Title / Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $offer->title) }}" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Link to Product -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Link to Existing Product (Optional)</label>
                <select name="product_id" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="">-- None (Standalone Custom Offer Card) --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id', $offer->product_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} (৳{{ number_format($p->effective_price) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Offer Price (৳) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $offer->price) }}" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                @error('price') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Top Red Badge Text -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Top Red Badge Text</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $offer->badge_text) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Red Bar Offer Text -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Red Bar Offer Text (e.g. ৳ 340 or 3 kg 2100 Tk)</label>
                <input type="text" name="offer_text" value="{{ old('offer_text', $offer->offer_text) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Upload Image -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Upload Offer Image</label>
                @if($offer->image)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ filter_var($offer->image, FILTER_VALIDATE_URL) ? $offer->image : asset('storage/' . $offer->image) }}" class="w-12 h-12 object-contain rounded-lg border bg-white p-1">
                        <span class="text-xs text-gray-400">Current Image</span>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-brand-500">
                @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Image URL Fallback -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Or Image URL</label>
                <input type="url" name="image_url" value="{{ old('image_url', filter_var($offer->image, FILTER_VALIDATE_URL) ? $offer->image : '') }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Target Link URL -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Custom Redirect URL / Link</label>
                <input type="text" name="link" value="{{ old('link', $offer->link) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $offer->sort_order) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="active" {{ old('status', $offer->status) === 'active' ? 'selected' : '' }}>Active (Show in horizontal slider)</option>
                    <option value="inactive" {{ old('status', $offer->status) === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t flex justify-end gap-3">
            <a href="{{ route('admin.combo-offers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold rounded-xl text-sm shadow-lg shadow-amber-500/20 transition">Update Offer Card</button>
        </div>
    </form>
</div>
@endsection
