@extends('layouts.admin')

@section('title', 'Customer List - Khati Bazar Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Customer List (Customer Management)</h1>
            <p class="text-xs text-gray-500 mt-1">View registered customer details and manage their accounts</p>
        </div>

        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, phone or email..." class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-brand-500">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold">Search</button>
        </form>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="p-4">Customer Name</th>
                        <th class="p-4">Mobile Number</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Total Orders</th>
                        <th class="p-4">Total Purchases</th>
                        <th class="p-4">Account Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-bold text-gray-900">{{ $customer->name }}</td>
                            <td class="p-4 font-semibold text-gray-800">{{ $customer->phone }}</td>
                            <td class="p-4 text-xs text-gray-500">{{ $customer->email ?? 'N/A' }}</td>
                            <td class="p-4 font-bold text-brand-700">{{ $customer->orders_count }}</td>
                            <td class="p-4 font-extrabold text-gray-900">{{ format_price($customer->orders_sum_grand_total ?? 0) }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $customer->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="inline-block bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-100 transition"><i class="fa-solid fa-eye"></i></a>
                                <form action="{{ route('admin.customers.toggle_status', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to toggle this customer\'s status?')">
                                    @csrf
                                    <button type="submit" class="bg-amber-50 text-amber-600 p-2 rounded-lg hover:bg-amber-100 transition" title="Toggle Status"><i class="fa-solid fa-user-lock"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
