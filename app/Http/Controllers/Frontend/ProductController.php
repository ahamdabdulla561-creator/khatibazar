<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants'])->where('status', 'active');

        // Search keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$q}%"));
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', fn($c) => $c->where('slug', $request->category));
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->whereHas('brand', fn($b) => $b->where('slug', $request->brand));
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('regular_price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('regular_price', '<=', (float)$request->max_price);
        }

        // Super deal
        if ($request->filled('super_offer')) {
            $query->where('is_super_offer', true);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('id', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('frontend.products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'activeVariants'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'brand', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function searchSuggestions(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (empty($q)) {
            return response()->json([]);
        }

        $products = Product::where('status', 'active')
            ->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            })
            ->take(5)
            ->get(['id', 'name', 'slug', 'image', 'regular_price', 'sale_price']);

        return response()->json($products);
    }

    public function quickView($id)
    {
        $product = Product::with(['category', 'brand', 'images', 'activeVariants'])
            ->where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => \Illuminate\Support\Str::limit(strip_tags($product->description), 160),
            'image_url' => $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400?text=Khati+Bajar',
            'regular_price' => (float)$product->regular_price,
            'sale_price' => $product->sale_price ? (float)$product->sale_price : null,
            'effective_price' => (float)$product->effective_price,
            'formatted_price' => format_price($product->effective_price),
            'formatted_regular_price' => format_price($product->regular_price),
            'discount_percent' => $product->discount_percent,
            'category_name' => $product->category ? $product->category->name : '',
            'in_stock' => $product->inStock(),
            'variants' => $product->activeVariants->map(function ($v) {
                return [
                    'id' => $v->id,
                    'name' => $v->name,
                    'price' => (float)$v->price,
                    'formatted_price' => format_price($v->price),
                    'stock' => $v->stock,
                ];
            }),
        ]);
    }
}
