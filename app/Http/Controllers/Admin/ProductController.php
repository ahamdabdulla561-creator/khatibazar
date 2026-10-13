<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'super_offer') {
                $query->where('is_super_offer', true);
            } elseif ($request->filter === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $products = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        Log::info("Attempting to store new product", [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'has_image' => $request->hasFile('image'),
            'gallery_count' => $request->hasFile('gallery') ? count($request->file('gallery')) : 0,
        ]);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'regular_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'short_description' => 'nullable|string',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'variants' => 'nullable|array',
                'variants.*.name' => 'nullable|string',
                'variants.*.price' => 'nullable|numeric|min:0',
                'variants.*.stock' => 'nullable|integer|min:0',
            ]);

            // Ensure Storage folders exist
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            if (!Storage::disk('public')->exists('products/gallery')) {
                Storage::disk('public')->makeDirectory('products/gallery');
            }

            // Safe Unicode / Bengali slug generation
            $slug = Str::slug($request->name);
            if (empty($slug)) {
                $slug = 'product-' . time() . '-' . Str::random(4);
            } else {
                $count = Product::where('slug', 'like', "{$slug}%")->count();
                if ($count > 0) {
                    $slug .= '-' . ($count + 1);
                }
            }

            $sku = $request->sku ?? ('KB-PRD-' . strtoupper(Str::random(6)));

            $mainImagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $mainImagePath = $request->file('image')->store('products', 'public');
            }

            $discountPercent = null;
            if ($request->sale_price && $request->regular_price > 0 && $request->sale_price < $request->regular_price) {
                $discountPercent = round((($request->regular_price - $request->sale_price) / $request->regular_price) * 100);
            }

            $product = Product::create([
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'slug' => $slug,
                'sku' => $sku,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'image' => $mainImagePath,
                'regular_price' => $request->regular_price,
                'sale_price' => $request->sale_price,
                'discount_percent' => $discountPercent,
                'stock' => $request->stock,
                'status' => $request->status,
                'is_featured' => $request->boolean('is_featured'),
                'is_super_offer' => $request->boolean('is_super_offer'),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Save Gallery Images safely
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $idx => $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $path = $file->store('products/gallery', 'public');
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $path,
                                'sort_order' => $idx + 1,
                            ]);
                            Log::info("Gallery image saved for product #{$product->id}: {$path}");
                        } catch (\Throwable $imgEx) {
                            Log::error("Failed to save gallery image for product #{$product->id}: " . $imgEx->getMessage());
                        }
                    }
                }
            }

            // Save Variants
            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $var) {
                    if ((!empty($var['name']) || !empty($var['size']) || !empty($var['color'])) && isset($var['price']) && $var['price'] !== '') {
                        $vName = $var['name'] ?? trim(($var['size'] ?? '') . ' ' . ($var['color'] ?? ''));
                        $vSlug = Str::slug($vName ?: 'std');
                        if (empty($vSlug)) {
                            $vSlug = 'v-' . Str::random(4);
                        }
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $vName ?: 'Standard',
                            'size' => $var['size'] ?? null,
                            'color' => $var['color'] ?? null,
                            'sku' => $var['sku'] ?? ($product->sku . '-' . $vSlug),
                            'price' => $var['price'],
                            'stock' => $var['stock'] ?? 0,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            Log::info("Product created successfully #{$product->id} - {$product->name} (Slug: {$product->slug})");
            AuditLog::log('Product Created', "পণ্য '{$product->name}' তৈরি করা হয়েছে।");

            return redirect()->route('admin.products.index')->with('success', 'পণ্য সফলভাবে যুক্ত হয়েছে!');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::warning("Product validation failed", [
                'errors' => $ve->errors(),
                'name' => $request->name
            ]);
            throw $ve;
        } catch (\Throwable $e) {
            Log::error("Product creation error: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'name' => $request->name
            ]);
            return redirect()->back()->withInput()->with('error', 'প্রোডাক্ট সংরক্ষণ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        Log::info("Attempting to update product #{$id}", [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'has_image' => $request->hasFile('image'),
            'gallery_count' => $request->hasFile('gallery') ? count($request->file('gallery')) : 0,
        ]);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'regular_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'status' => 'required|in:active,inactive',
            ]);

            // Ensure Storage folders exist
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            if (!Storage::disk('public')->exists('products/gallery')) {
                Storage::disk('public')->makeDirectory('products/gallery');
            }

            $mainImagePath = $product->image;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $mainImagePath = $request->file('image')->store('products', 'public');
            }

            $discountPercent = null;
            if ($request->sale_price && $request->regular_price > 0 && $request->sale_price < $request->regular_price) {
                $discountPercent = round((($request->regular_price - $request->sale_price) / $request->regular_price) * 100);
            }

            $product->update([
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'image' => $mainImagePath,
                'regular_price' => $request->regular_price,
                'sale_price' => $request->sale_price,
                'discount_percent' => $discountPercent,
                'stock' => $request->stock,
                'status' => $request->status,
                'is_featured' => $request->boolean('is_featured'),
                'is_super_offer' => $request->boolean('is_super_offer'),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Additional gallery upload safely
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $idx => $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $path = $file->store('products/gallery', 'public');
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $path,
                                'sort_order' => $idx + 1,
                            ]);
                            Log::info("Additional gallery image saved for product #{$product->id}: {$path}");
                        } catch (\Throwable $imgEx) {
                            Log::error("Failed to save gallery image for product #{$product->id}: " . $imgEx->getMessage());
                        }
                    }
                }
            }

            // Sync Variants
            if ($request->has('variants') && is_array($request->variants)) {
                $existingVariantIds = [];
                foreach ($request->variants as $var) {
                    if ((!empty($var['name']) || !empty($var['size']) || !empty($var['color'])) && isset($var['price']) && $var['price'] !== '') {
                        $vName = $var['name'] ?? trim(($var['size'] ?? '') . ' ' . ($var['color'] ?? ''));
                        $vSlug = Str::slug($vName ?: 'std');
                        if (empty($vSlug)) {
                            $vSlug = 'v-' . Str::random(4);
                        }
                        if (isset($var['id']) && !empty($var['id'])) {
                            $variantModel = ProductVariant::find($var['id']);
                            if ($variantModel && $variantModel->product_id == $product->id) {
                                $variantModel->update([
                                    'name' => $vName ?: $variantModel->name,
                                    'size' => $var['size'] ?? null,
                                    'color' => $var['color'] ?? null,
                                    'sku' => $var['sku'] ?? $variantModel->sku,
                                    'price' => $var['price'],
                                    'stock' => $var['stock'] ?? 0,
                                ]);
                                $existingVariantIds[] = $variantModel->id;
                            }
                        } else {
                            $newVar = ProductVariant::create([
                                'product_id' => $product->id,
                                'name' => $vName ?: 'Standard',
                                'size' => $var['size'] ?? null,
                                'color' => $var['color'] ?? null,
                                'sku' => $var['sku'] ?? ($product->sku . '-' . $vSlug),
                                'price' => $var['price'],
                                'stock' => $var['stock'] ?? 0,
                                'status' => 'active',
                            ]);
                            $existingVariantIds[] = $newVar->id;
                        }
                    }
                }
                // Delete removed variants
                ProductVariant::where('product_id', $product->id)->whereNotIn('id', $existingVariantIds)->delete();
            }

            Log::info("Product updated successfully #{$product->id} - {$product->name}");
            AuditLog::log('Product Updated', "পণ্য '{$product->name}' আপডেট করা হয়েছে।");

            return redirect()->route('admin.products.index')->with('success', 'পণ্য সফলভাবে আপডেট করা হয়েছে!');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::warning("Product update validation failed #{$id}", [
                'errors' => $ve->errors(),
                'name' => $request->name
            ]);
            throw $ve;
        } catch (\Throwable $e) {
            Log::error("Product update error #{$id}: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'name' => $request->name
            ]);
            return redirect()->back()->withInput()->with('error', 'প্রোডাক্ট আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        AuditLog::log('Product Deleted', "পণ্য '{$name}' মুছে ফেলা হয়েছে।");

        return redirect()->route('admin.products.index')->with('success', 'পণ্যটি মুছে ফেলা হয়েছে।');
    }

    public function toggleSuperOffer($id)
    {
        $product = Product::findOrFail($id);
        $product->is_super_offer = !$product->is_super_offer;
        $product->save();

        $statusText = $product->is_super_offer ? 'Super Offer এ যুক্ত করা হয়েছে' : 'Super Offer থেকে সরানো হয়েছে';
        AuditLog::log('Product Super Offer Toggled', "পণ্য '{$product->name}' {$statusText}।");

        return redirect()->back()->with('success', "পণ্য '{$product->name}' {$statusText}!");
    }

    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        $statusText = $product->is_featured ? 'Featured Products এ যুক্ত করা হয়েছে' : 'Featured Products থেকে সরানো হয়েছে';
        AuditLog::log('Product Featured Toggled', "পণ্য '{$product->name}' {$statusText}।");

        return redirect()->back()->with('success', "পণ্য '{$product->name}' {$statusText}!");
    }
}
