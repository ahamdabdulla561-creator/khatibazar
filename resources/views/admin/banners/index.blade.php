@extends('layouts.admin')

@section('title', 'Hero Sliders & Banners - Khati Bajar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Hero Sliders & Banner Management</h1>
            <p class="text-xs text-gray-500 mt-1">Control all homepage hero slider images, titles, badges, and button links from here</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-extrabold px-5 py-3 rounded-2xl shadow-md transition text-xs flex items-center justify-center gap-2 w-fit">
            <i class="fa-solid fa-plus"></i> Add New Hero Banner
        </a>
    </div>

    <!-- Banners Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">Image / Preview</th>
                        <th class="py-4 px-6 font-bold">Banner Details</th>
                        <th class="py-4 px-6 font-bold">Position</th>
                        <th class="py-4 px-6 font-bold">Sort Order</th>
                        <th class="py-4 px-6 font-bold">Status</th>
                        <th class="py-4 px-6 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="py-4 px-6">
                                @if($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" class="w-24 h-14 object-cover rounded-xl border border-gray-200 shadow-sm">
                                @elseif($banner->background_image)
                                    <div class="w-24 h-14 bg-cover bg-center rounded-xl border border-gray-200 shadow-sm" style="background-image: url('{{ $banner->background_image }}')"></div>
                                @else
                                    <div class="w-24 h-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 font-bold text-[10px]">No Image</div>
                                @endif
                            </td>

                            <td class="py-4 px-6 max-w-xs">
                                @if($banner->badge_text)
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full inline-block mb-1">
                                        {{ $banner->badge_text }}
                                    </span>
                                @endif
                                <h4 class="font-extrabold text-gray-900 text-sm line-clamp-1">{{ $banner->title }}</h4>
                                <p class="text-gray-500 text-[11px] line-clamp-1 mt-0.5">{{ $banner->subtitle }}</p>
                                <span class="text-[10px] text-brand-600 font-mono mt-1 block">Button: {{ $banner->button_text }} ({{ $banner->button_link }})</span>
                            </td>

                            <td class="py-4 px-6">
                                <span class="bg-slate-100 text-slate-700 font-extrabold px-2.5 py-1 rounded-full text-[10px] uppercase">
                                    {{ str_replace('_', ' ', $banner->position) }}
                                </span>
                            </td>

                            <td class="py-4 px-6 font-bold font-mono text-gray-700">
                                {{ $banner->sort_order }}
                            </td>

                            <td class="py-4 px-6">
                                @if($banner->status === 'active')
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full flex items-center gap-1 w-fit">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Active
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full flex items-center gap-1 w-fit">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold px-3 py-1.5 rounded-xl border border-brand-200 transition text-xs inline-flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>

                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this hero banner?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-xl border border-red-200 transition text-xs inline-flex items-center gap-1">
                                        <i class="fa-solid fa-trash-can"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i class="fa-solid fa-image text-3xl mb-2 block text-gray-300"></i>
                                <span class="font-bold text-sm">No Hero Banners Found</span>
                                <p class="text-xs text-gray-500 mt-1">Click "Add New Hero Banner" above to create your first dynamic slider banner.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="p-4 border-t">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
