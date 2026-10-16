<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        $products = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $heroBanners = \App\Models\Banner::where('position', 'hero_main')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();


        $comboOffers = \App\Models\ComboOffer::with('product')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.home', compact('categories', 'products', 'heroBanners', 'comboOffers'));
    }
}
