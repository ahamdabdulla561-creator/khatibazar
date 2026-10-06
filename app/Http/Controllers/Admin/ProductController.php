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
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
        ]);

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $sku = $request->sku ?? ('KB-PRD-' . strtoupper(Str::random(6)));

        $mainImagePath = null;
        if ($request->hasFile('image')) {
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

        // Save Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $idx => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        // Save Variants
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $var) {
                if ((!empty($var['name']) || !empty($var['size']) || !empty($var['color'])) && isset($var['price'])) {
                    $vName = $var['name'] ?? trim(($var['size'] ?? '') . ' ' . ($var['color'] ?? ''));
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $vName ?: 'Standard',
                        'size' => $var['size'] ?? null,
                        'color' => $var['color'] ?? null,
                        'sku' => $var['sku'] ?? ($product->sku . '-' . Str::slug($vName ?: 'std')),
                        'price' => $var['price'],
                        'stock' => $var['stock'] ?? 0,
                        'status' => 'active',
                    ]);
                }
            }
        }

        AuditLog::log('Product Created', "পণ্য '{$product->name}' তৈরি করা হয়েছে।");

        return redirect()->route('admin.products.index')->with('success', 'পণ্য সফলভাবে যুক্ত হয়েছে!');
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

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $mainImagePath = $product->image;
        if ($request->hasFile('image')) {
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

        // Additional gallery upload
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $idx => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        // Sync Variants
        if ($request->has('variants') && is_array($request->variants)) {
            $existingVariantIds = [];
            foreach ($request->variants as $var) {
                if ((!empty($var['name']) || !empty($var['size']) || !empty($var['color'])) && isset($var['price'])) {
                    $vName = $var['name'] ?? trim(($var['size'] ?? '') . ' ' . ($var['color'] ?? ''));
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
                            'sku' => $var['sku'] ?? ($product->sku . '-' . Str::slug($vName ?: 'std')),
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

        AuditLog::log('Product Updated', "পণ্য '{$product->name}' আপডেট করা হয়েছে।");

        return redirect()->route('admin.products.index')->with('success', 'পণ্য সফলভাবে আপডেট করা হয়েছে!');
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
