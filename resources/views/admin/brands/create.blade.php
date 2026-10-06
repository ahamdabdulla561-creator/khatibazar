@extends('layouts.admin')

@section('title', 'Create New Brand - Khati Bajar')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-900">Create New Brand</h1>
        <a href="{{ route('admin.brands.index') }}" class="text-xs text-gray-500 hover:underline">Back to List</a>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Brand Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Brand Logo</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-md transition text-sm">
            Save Brand
        </button>
    </form>
</div>
@endsection
