@extends('layouts.admin')

@section('title', 'Edit Hero Banner - Khati Bajar Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Edit Hero Banner</h1>
            <p class="text-xs text-gray-500 mt-1">Update banner slide content, image, text, and button link</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="text-xs text-gray-500 hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Banners List
        </a>
    </div>

    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- Banner Title -->
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Banner Heading / Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
            @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Badge Tag Text & Sort Order -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Badge Tag Text (Top Pill Tag)</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                @error('badge_text') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Sort Order (0, 1, 2...)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>
        </div>

        <!-- Subtitle / Description -->
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Subtitle / Short Description</label>
            <textarea name="subtitle" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">{{ old('subtitle', $banner->subtitle) }}</textarea>
            @error('subtitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Current Banner Image Preview & Upload -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Change Banner Image</label>
                @if($banner->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $banner->image) }}" class="h-20 w-auto rounded-xl border p-1 shadow-sm">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-xl p-2.5">
                @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Background Image URL (Fallback)</label>
                <input type="url" name="background_image" value="{{ old('background_image', $banner->background_image) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                @error('background_image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Button Text & Button Link -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Button Text</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Button Redirect URL / Link</label>
                <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
            </div>
        </div>

        <!-- Position & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Banner Position <span class="text-red-500">*</span></label>
                <select name="position" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold">
                    <option value="hero_main" {{ $banner->position == 'hero_main' ? 'selected' : '' }}>Hero Main Slider (center top slider)</option>
                    <option value="sub_hero" {{ $banner->position == 'sub_hero' ? 'selected' : '' }}>Sub Hero Carousel</option>
                    <option value="offer_banner" {{ $banner->position == 'offer_banner' ? 'selected' : '' }}>Promotional Banner</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-semibold">
                    <option value="active" {{ $banner->status == 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                    <option value="inactive" {{ $banner->status == 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-4 rounded-2xl shadow-xl transition text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Update Hero Banner
        </button>
    </form>
</div>
@endsection
