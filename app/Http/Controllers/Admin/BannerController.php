<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'badge_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'background_image' => 'nullable|url|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:500',
            'position' => 'required|string|in:hero_main,sub_hero,offer_banner',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        $banner = Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge_text' => $request->badge_text,
            'image' => $imagePath,
            'background_image' => $request->background_image,
            'button_text' => $request->button_text ?? 'Shop Now',
            'button_link' => $request->button_link ?? '/products',
            'position' => $request->position,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Hero Banner Created', "নতুন ব্যানার তৈরি করা হয়েছে: {$banner->title}");

        return redirect()->route('admin.banners.index')->with('success', 'নতুন হিরো স্লাইডার ব্যানার সফলভাবে তৈরি করা হয়েছে!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'badge_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'background_image' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:500',
            'position' => 'required|string|in:hero_main,sub_hero,offer_banner',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'badge_text' => $request->badge_text,
            'image' => $banner->image,
            'background_image' => $request->background_image,
            'button_text' => $request->button_text ?? 'Shop Now',
            'button_link' => $request->button_link ?? '/products',
            'position' => $request->position,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Hero Banner Updated', "ব্যানার আপডেট করা হয়েছে: {$banner->title}");

        return redirect()->route('admin.banners.index')->with('success', 'হিরো স্লাইডার ব্যানার সফলভাবে আপডেট করা হয়েছে!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $title = $banner->title;
        $banner->delete();

        AuditLog::log('Hero Banner Deleted', "ব্যানার মুছে ফেলা হয়েছে: {$title}");

        return redirect()->route('admin.banners.index')->with('success', 'ব্যানার সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
