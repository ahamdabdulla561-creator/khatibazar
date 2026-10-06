@extends('layouts.admin')

@section('title', 'ব্র্যান্ড সম্পাদনা - খাঁটি বাজার')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-900">ব্র্যান্ড এডিট করুন</h1>
        <a href="{{ route('admin.brands.index') }}" class="text-xs text-gray-500 hover:underline">তালিকায় ফিরুন</a>
    </div>

    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">ব্র্যান্ডের নাম <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $brand->name) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">নতুন লোগো আপলোড (যদি থাকে)</label>
            @if($brand->logo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $brand->logo) }}" class="w-16 h-16 object-cover rounded-xl border">
                </div>
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">স্ট্যাটাস</label>
            <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                <option value="active" {{ $brand->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $brand->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-md transition text-sm">
            আপডেট সম্পন্ন করুন
        </button>
    </form>
</div>
@endsection
