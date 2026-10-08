@extends('layouts.admin')

@section('title', 'Audit Logs - Khati Bazar Admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900">Audit Logs (System Audit Logs)</h1>
        <p class="text-xs text-gray-500 mt-1">Tracking history of all admin and system actions</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="p-4">Date & Time</th>
                        <th class="p-4">Admin Name</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 text-xs font-semibold text-gray-700 whitespace-nowrap">
                                {{ $log->created_at->format('d M Y - h:i A') }}
                            </td>
                            <td class="p-4 font-bold text-gray-900 whitespace-nowrap">
                                {{ $log->user->name ?? 'System' }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="bg-slate-100 text-slate-800 font-bold px-2.5 py-1 rounded-full text-xs">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4 text-xs text-gray-800 max-w-md">
                                {{ $log->description }}
                            </td>
                            <td class="p-4 text-xs font-mono text-gray-500 whitespace-nowrap">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
