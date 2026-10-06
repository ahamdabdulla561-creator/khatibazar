<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ComboOffer;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->orderBy('sort_order', 'asc')->get();
        
        $featuredProducts = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('sort_order', 'asc')
            ->take(8)
            ->get();

        $comboOffers = ComboOffer::with('product')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        $superDeals = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->where('is_super_offer', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        // Fallback to active products if less than 6
        if ($superDeals->count() < 6) {
            $existingIds = $superDeals->pluck('id')->toArray();
            $additionalProducts = Product::with(['category', 'brand', 'variants'])
                ->where('status', 'active')
                ->whereNotIn('id', $existingIds)
                ->orderBy('id', 'desc')
                ->take(12 - $superDeals->count())
                ->get();
            $superDeals = $superDeals->concat($additionalProducts);
        }

        $popularProducts = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $heroBanners = \App\Models\Banner::where('position', 'hero_main')->where('status', 'active')->orderBy('sort_order', 'asc')->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'superDeals', 'comboOffers', 'popularProducts', 'heroBanners'));
    }
}
