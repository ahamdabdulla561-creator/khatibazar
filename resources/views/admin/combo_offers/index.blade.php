@extends('layouts.admin')

@section('title', 'Combo Offer Cards Management - Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-fire text-amber-500"></i> Combo Offer Cards Management
            </h1>
            <p class="text-sm text-gray-500 mt-1">Control all 6-card horizontal slider items, images, badges, and prices shown on the homepage.</p>
        </div>
        <a href="{{ route('admin.combo-offers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold rounded-xl shadow-lg shadow-amber-500/20 transition text-sm">
            <i class="fa-solid fa-plus"></i> Add New Offer Card
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Filters & List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between gap-4">
            <form method="GET" action="{{ route('admin.combo-offers.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search offer title or badge..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 bg-white">
                
                <select name="status" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 bg-white">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-xl transition">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 font-bold uppercase text-xs border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Sort</th>
                        <th class="px-6 py-4">Card Preview</th>
                        <th class="px-6 py-4">Title & Link</th>
                        <th class="px-6 py-4">Badges & Offer Text</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($offers as $offer)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-bold text-gray-500">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg text-xs">
                                {{ $offer->sort_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-16 h-20 bg-amber-500/10 border border-amber-300 rounded-xl p-1 flex flex-col items-center justify-between text-center relative overflow-hidden">
                                <span class="bg-red-600 text-white text-[7px] font-black w-full rounded-t-sm truncate px-0.5">{{ $offer->badge_text }}</span>
                                <img src="{{ $offer->image ? (filter_var($offer->image, FILTER_VALIDATE_URL) ? $offer->image : asset('storage/' . $offer->image)) : 'https://placehold.co/80x80?text=Khati+Bazar' }}" class="h-8 object-contain my-0.5">
                                <span class="bg-red-600 text-white text-[7px] font-bold w-full rounded-sm truncate">{{ $offer->offer_text }}</span>
                                <span class="bg-gradient-to-r from-lime-500 to-amber-500 text-white text-[8px] font-extrabold w-full rounded-full py-0.5">৳ {{ number_format($offer->price) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="block font-extrabold text-gray-900 text-sm">{{ $offer->title }}</span>
                            @if($offer->product)
                                <span class="text-xs text-brand-600 font-medium block">Linked Product: {{ $offer->product->name }}</span>
                            @endif
                            @if($offer->link)
                                <span class="text-[11px] font-mono text-gray-400 block truncate max-w-xs">{{ $offer->link }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded text-[10px] uppercase block w-fit mb-1">Top: {{ $offer->badge_text }}</span>
                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[10px] block w-fit">Bar: {{ $offer->offer_text }}</span>
                        </td>
                        <td class="px-6 py-4 font-black text-gray-900 text-base">
                            ৳ {{ number_format($offer->price) }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.combo_offers.toggle_status', $offer->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $offer->status === 'active' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                    <span class="w-2 h-2 rounded-full {{ $offer->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($offer->status) }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.combo-offers.edit', $offer->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit Card">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.combo-offers.destroy', $offer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this offer card?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete Card">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-fire text-4xl mb-3 text-gray-300 block"></i>
                            No combo offer cards found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $offers->links() }}
        </div>
    </div>
</div>
@endsection
