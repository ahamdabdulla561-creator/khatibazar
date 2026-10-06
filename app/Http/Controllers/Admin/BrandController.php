<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        $slug = Str::slug($request->name);
        $count = Brand::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $brand = Brand::create([
            'name' => $request->name,
            'slug' => $slug,
            'logo' => $logoPath,
            'status' => $request->status,
        ]);

        AuditLog::log('Brand Created', "ব্র্যান্ড '{$brand->name}' তৈরি করা হয়েছে।");

        return redirect()->route('admin.brands.index')->with('success', 'ব্র্যান্ড সফলভাবে যুক্ত হয়েছে!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        $brand->update([
            'name' => $request->name,
            'logo' => $logoPath,
            'status' => $request->status,
        ]);

        AuditLog::log('Brand Updated', "ব্র্যান্ড '{$brand->name}' আপডেট করা হয়েছে।");

        return redirect()->route('admin.brands.index')->with('success', 'ব্র্যান্ড আপডেট সফল হয়েছে!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $name = $brand->name;
        $brand->delete();

        AuditLog::log('Brand Deleted', "ব্র্যান্ড '{$name}' মুছে ফেলা হয়েছে।");

        return redirect()->route('admin.brands.index')->with('success', 'ব্র্যান্ড মুছে ফেলা হয়েছে!');
    }
}
