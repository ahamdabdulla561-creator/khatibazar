@extends('layouts.admin')

@section('title', 'Brands - Khati Bajar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Brand Management</h1>
            <p class="text-xs text-gray-500 mt-1">Manage all brands on the website</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-md transition text-xs flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Brand
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase font-semibold">
                <tr>
                    <th class="p-4">Logo</th>
                    <th class="p-4">Brand Name</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($brands as $brand)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4">
                            <img src="{{ $brand->logo ? asset('storage/' . $brand->logo) : 'https://placehold.co/80x80?text=Brand' }}" class="w-10 h-10 object-cover rounded-xl border border-gray-200">
                        </td>
                        <td class="p-4 font-bold text-gray-900">{{ $brand->name }}</td>
                        <td class="p-4 text-xs font-mono text-gray-500">{{ $brand->slug }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $brand->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($brand->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="inline-block bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-100 transition"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this brand?')">
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
