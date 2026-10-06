<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CourierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourierServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = CourierService::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('code', 'like', "%{$q}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $couriers = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('admin.courier_services.index', compact('couriers'));
    }

    public function create()
    {
        return view('admin.courier_services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100|unique:courier_services,code',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'logo_url' => 'nullable|url|max:500',
            'charge' => 'nullable|numeric|min:0',
            'tracking_url_template' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $code = $request->filled('code') ? Str::slug($request->code, '_') : Str::slug($request->name, '_');

        // Check unique code fallback
        if (CourierService::where('code', $code)->exists()) {
            $code = $code . '_' . time();
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('couriers', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->logo_url;
        }

        $courier = CourierService::create([
            'name' => $request->name,
            'code' => $code,
            'logo' => $logoPath,
            'charge' => $request->charge ?? 0,
            'tracking_url_template' => $request->tracking_url_template,
            'notes' => $request->notes,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Courier Service Created', "নতুন কুরিয়ার সার্ভিস '{$courier->name}' যুক্ত করা হয়েছে।");

        return redirect()->route('admin.courier-services.index')
            ->with('success', "কুরিয়ার সার্ভিস '{$courier->name}' সফলভাবে তৈরি করা হয়েছে!");
    }

    public function edit($id)
    {
        $courier = CourierService::findOrFail($id);
        return view('admin.courier_services.edit', compact('courier'));
    }

    public function update(Request $request, $id)
    {
        $courier = CourierService::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:courier_services,code,' . $courier->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'logo_url' => 'nullable|url|max:500',
            'charge' => 'nullable|numeric|min:0',
            'tracking_url_template' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $logoPath = $courier->logo;
        if ($request->hasFile('logo')) {
            if ($courier->logo && !filter_var($courier->logo, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($courier->logo)) {
                Storage::disk('public')->delete($courier->logo);
            }
            $logoPath = $request->file('logo')->store('couriers', 'public');
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->logo_url;
        }

        $courier->update([
            'name' => $request->name,
            'code' => Str::slug($request->code, '_'),
            'logo' => $logoPath,
            'charge' => $request->charge ?? 0,
            'tracking_url_template' => $request->tracking_url_template,
            'notes' => $request->notes,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Courier Service Updated', "কুরিয়ার সার্ভিস '{$courier->name}' আপডেট করা হয়েছে।");

        return redirect()->route('admin.courier-services.index')
            ->with('success', "কুরিয়ার সার্ভিস '{$courier->name}' সফলভাবে আপডেট করা হয়েছে!");
    }

    public function destroy($id)
    {
        $courier = CourierService::findOrFail($id);
        $name = $courier->name;

        if ($courier->logo && !filter_var($courier->logo, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($courier->logo)) {
            Storage::disk('public')->delete($courier->logo);
        }

        $courier->delete();

        AuditLog::log('Courier Service Deleted', "কুরিয়ার সার্ভিস '{$name}' মুছে ফেলা হয়েছে।");

        return redirect()->route('admin.courier-services.index')
            ->with('success', "কুরিয়ার সার্ভিস '{$name}' সফলভাবে মুছে ফেলা হয়েছে!");
    }

    public function toggleStatus($id)
    {
        $courier = CourierService::findOrFail($id);
        $courier->status = $courier->status === 'active' ? 'inactive' : 'active';
        $courier->save();

        AuditLog::log('Courier Service Status Toggled', "কুরিয়ার সার্ভিস '{$courier->name}' স্ট্যাটাস পরিবর্তিত হয়ে {$courier->status} হয়েছে।");

        return redirect()->back()->with('success', "কুরিয়ার সার্ভিস '{$courier->name}' এর স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!");
    }
}
