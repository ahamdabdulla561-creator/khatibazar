@extends('layouts.admin')

@section('title', 'Courier Services Management - Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-brand-600"></i> Courier Services Management
            </h1>
            <p class="text-sm text-gray-500 mt-1">Manage delivery courier companies and tracking options for customer orders.</p>
        </div>
        <a href="{{ route('admin.courier-services.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-lg shadow-brand-600/20 transition text-sm">
            <i class="fa-solid fa-plus"></i> Add New Courier
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
            <form method="GET" action="{{ route('admin.courier-services.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search courier name or code..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 bg-white">
                
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
                        <th class="px-6 py-4">Courier Name</th>
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Tracking Link Template</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($couriers as $courier)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-bold text-gray-500">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg text-xs">
                                {{ $courier->sort_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center gap-3">
                                @if($courier->logo)
                                    <img src="{{ filter_var($courier->logo, FILTER_VALIDATE_URL) ? $courier->logo : asset('storage/' . $courier->logo) }}" alt="{{ $courier->name }}" class="w-10 h-10 object-contain rounded-lg border bg-white p-1">
                                @else
                                    <div class="w-10 h-10 bg-brand-50 rounded-lg border border-brand-200 flex items-center justify-center text-brand-600 font-black text-xs">
                                        <i class="fa-solid fa-truck"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="block font-extrabold text-gray-900 text-sm">{{ $courier->name }}</span>
                                    @if($courier->notes)
                                        <span class="block text-xs text-gray-400 max-w-xs truncate">{{ $courier->notes }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">
                            <span class="px-2.5 py-1 bg-gray-100 rounded-md font-semibold text-gray-700">{{ $courier->code }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                            @if($courier->tracking_url_template)
                                <span class="text-blue-600 font-mono">{{ $courier->tracking_url_template }}</span>
                            @else
                                <span class="text-gray-300 italic">No direct tracking URL</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.courier_services.toggle_status', $courier->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $courier->status === 'active' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                    <span class="w-2 h-2 rounded-full {{ $courier->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($courier->status) }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.courier-services.edit', $courier->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.courier-services.destroy', $courier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this courier service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-truck-ramp-box text-4xl mb-3 text-gray-300 block"></i>
                            No courier services found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $couriers->links() }}
        </div>
    </div>
</div>
@endsection
