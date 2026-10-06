<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order', 'asc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $slug = Str::slug($request->name);
        $count = Category::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Category Created', "ক্যাটাগরি '{$category->name}' তৈরি করা হয়েছে।");

        return redirect()->route('admin.categories.index')->with('success', 'ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        AuditLog::log('Category Updated', "ক্যাটাগরি '{$category->name}' আপডেট করা হয়েছে।");

        return redirect()->route('admin.categories.index')->with('success', 'ক্যাটাগরি আপডেট সফল হয়েছে!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

        AuditLog::log('Category Deleted', "ক্যাটাগরি '{$name}' মুছে ফেলা হয়েছে।");

        return redirect()->route('admin.categories.index')->with('success', 'ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
