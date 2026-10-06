<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders')->withSum('orders', 'grand_total');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $customers = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::where('role', 'customer')->with(['orders.items', 'addresses'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function toggleStatus($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $newStatus = $customer->status === 'active' ? 'suspended' : 'active';
        $customer->update(['status' => $newStatus]);

        AuditLog::log('Customer Status Toggle', "গ্রাহক {$customer->name} (ID: {$customer->id}) স্ট্যাটাস পরিবর্তন করা হয়েছে ({$newStatus})।");

        return redirect()->back()->with('success', "গ্রাহকের অ্যাকাউন্ট সফলভাবে {$newStatus} করা হয়েছে।");
    }
}
