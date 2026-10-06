@extends('layouts.admin')

@section('title', 'Categories - Khati Bajar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Category Management</h1>
            <p class="text-xs text-gray-500 mt-1">Organize and manage all categories on the website</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-md transition text-xs flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Category
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase font-semibold">
                <tr>
                    <th class="p-4">Order</th>
                    <th class="p-4">Image</th>
                    <th class="p-4">Category Name</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-bold text-gray-600">{{ $category->sort_order }}</td>
                        <td class="p-4">
                            <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://placehold.co/80x80?text=Cat' }}" class="w-10 h-10 object-cover rounded-xl border border-gray-200">
                        </td>
                        <td class="p-4 font-bold text-gray-900">{{ $category->name }}</td>
                        <td class="p-4 text-xs font-mono text-gray-500">{{ $category->slug }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $category->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="inline-block bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-100 transition"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-600 p-2 rounded-lg hover:bg-red-100 transition"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
