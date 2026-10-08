@extends('layouts.admin')

@section('title', 'Products Management - Khati Bazar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-brand-600"></i> Product Management (পণ্য ব্যবস্থাপনা)
            </h1>
            <p class="text-xs text-gray-500 mt-1">ছবি আপলোড, দাম কমানো-বাড়ানো এবং ক্যাটাগরি ও অফার নিয়ন্ত্রণের পূর্ণ ব্যবস্থা</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-md transition text-xs flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Product
        </a>
    </div>

    <!-- Search & Category Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex-1 flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by product name or SKU..." class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-brand-500">
            <select name="category_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-xl text-xs font-bold">Search</button>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="p-4">Image (ছবি)</th>
                        <th class="p-4">Product Info (পণ্যের নাম)</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price (মূল্য)</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="p-4">
                                <div class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 p-1 flex items-center justify-center">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            </td>
                            <td class="p-4">
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="font-extrabold text-gray-900 hover:text-brand-600 block line-clamp-1 text-sm">
                                    {{ $product->name }}
                                </a>
                                <span class="text-[11px] text-gray-400 font-mono">SKU: {{ $product->sku }}</span>
                            </td>
                            <td class="p-4 text-xs">
                                <span class="font-bold text-gray-800 block">{{ $product->category->name ?? '-' }}</span>
                                <span class="text-gray-500">{{ $product->brand->name ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-black text-brand-700 text-sm block">{{ format_price($product->effective_price) }}</span>
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="text-xs text-gray-400 line-through">{{ format_price($product->regular_price) }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $product->stock > 5 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->stock }} pcs
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-1">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-1.5 bg-brand-50 text-brand-700 hover:bg-brand-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition shadow-xs">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit Price & Image
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-600 p-2 rounded-xl hover:bg-red-600 hover:text-white transition" title="Delete Product"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
