@extends('layouts.admin')

@section('title', 'Add New Hero Banner - Khati Bajar Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Add New Hero Banner</h1>
            <p class="text-xs text-gray-500 mt-1">Create a new dynamic banner slide for the homepage hero slider</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="text-xs text-gray-500 hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Banners List
        </a>
    </div>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
        @csrf

        <!-- Banner Title -->
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Banner Heading / Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Khati Bajar — Pure Agricultural & Veterinary Supplies" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Badge Tag Text & Sort Order -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Badge Tag Text (Top Pill Tag)</label>
                <input type="text" name="badge_text" value="{{ old('badge_text') }}" placeholder="e.g. 100% Genuine Farm Products" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                @error('badge_text') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order (0, 1, 2...)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>
        </div>

        <!-- Subtitle / Description -->
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Subtitle / Short Description</label>
            <textarea name="subtitle" rows="2" placeholder="e.g. Trusted digital marketplace for livestock medicines, CFC plus combo feeds..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">{{ old('subtitle') }}</textarea>
            @error('subtitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Image Upload & URL Fallback -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Upload Banner Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-xl p-2.5">
                <span class="text-[11px] text-gray-400 mt-1 block">Supported: JPG, PNG, WEBP (Recommended 1200x500px)</span>
                @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">OR Background Image URL (Unsplash / External)</label>
                <input type="url" name="background_image" value="{{ old('background_image') }}" placeholder="https://images.unsplash.com/photo-..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                @error('background_image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Button Text & Button Link -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Button Text</label>
                <input type="text" name="button_text" value="{{ old('button_text', 'Shop Now') }}" placeholder="e.g. Shop Now / View Deals" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Button Redirect URL / Link</label>
                <input type="text" name="button_link" value="{{ old('button_link', '/products') }}" placeholder="e.g. /products or /category/veterinary-medicine" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>
        </div>

        <!-- Position & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Banner Position <span class="text-red-500">*</span></label>
                <select name="position" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold">
                    <option value="hero_main">Hero Main Slider (center top slider)</option>
                    <option value="sub_hero">Sub Hero Carousel</option>
                    <option value="offer_banner">Promotional Banner</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold">
                    <option value="active">Active (সক্রিয়)</option>
                    <option value="inactive">Inactive (নিষ্ক্রিয়)</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-4 rounded-2xl shadow-xl transition text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Save Hero Banner
        </button>
    </form>
</div>
@endsection
