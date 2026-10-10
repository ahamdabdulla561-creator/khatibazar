<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Auto-sync real Khati Bazar uploaded products
        try {
            $catNuts = Category::firstOrCreate(['slug' => 'nuts-seeds'], [
                'name' => 'Nuts & Seeds (বাদাম ও বীজ)',
                'description' => 'প্রাকৃতিক ও প্রিমিয়াম কোয়ালিটি বাদাম এবং পুষ্টিকর বীজ',
                'status' => 'active',
                'sort_order' => 1,
            ]);

            $catSpices = Category::firstOrCreate(['slug' => 'pure-spices'], [
                'name' => 'Pure Spices (খাঁটি গুঁড়া মসলা)',
                'description' => 'নিজস্ব তত্ত্বাবধানে তৈরি ১০০% খাঁটি মসলা',
                'status' => 'active',
                'sort_order' => 2,
            ]);

            $brand = \App\Models\Brand::firstOrCreate(['slug' => 'khati-bazar'], [
                'name' => 'খাঁটি বাজার (Khati Bazar)',
                'status' => 'active',
            ]);

            // 1. Multi Seeds & Nuts Mix
            $p1 = Product::updateOrCreate(['slug' => 'multi-seeds-and-nuts-mix'], [
                'category_id' => $catNuts->id,
                'brand_id' => $brand->id,
                'name' => 'মাল্টি সিডস এন্ড নাটস মিক্স (Multi Seeds & Nuts Mix)',
                'sku' => 'KB-SNM-01',
                'short_description' => 'কাঠবাদাম, কাজুবাদাম, আখরোট, কুমড়ার বীজ, সূর্যমুখীর বীজ, তিল, চিয়া বীজ, ফ্লাক্সসিড সহ প্রকৃতির সেরা পুষ্টিকর উপাদানে প্রস্তুত।',
                'description' => "খাঁটি বাজার বিডি প্রস্তুতকৃত মাল্টি সিডস এন্ড নাটস মিক্স শরীরের প্রয়োজনীয় পুষ্টি, শক্তি ও রোগ প্রতিরোধ ক্ষমতা বৃদ্ধি করে।\n\nউপাদান:\n- কাঠবাদাম, কাজুবাদাম, আখরোট\n- কুমড়ার বীজ, সূর্যমুখীর বীজ, তরমুজের বীজ\n- কালো তিল, সাদা তিল, চিয়া বীজ, ব্রাউন ফ্লাক্সসিড\n\nসংরক্ষণ: ১ মাসের বেশি সময় ধরে খেতে ফ্রিজে সংরক্ষণ করুন।",
                'image' => 'products/multi-seeds-nuts-500g.jpg',
                'regular_price' => 700.00,
                'sale_price' => 650.00,
                'stock' => 100,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 1,
            ]);
            \App\Models\ProductVariant::updateOrCreate(['product_id' => $p1->id, 'sku' => 'KB-SNM-250G'], [
                'name' => '২৫০ গ্রাম জার (250g)', 'size' => '250g', 'price' => 350.00, 'stock' => 50, 'status' => 'active'
            ]);
            \App\Models\ProductVariant::updateOrCreate(['product_id' => $p1->id, 'sku' => 'KB-SNM-500G'], [
                'name' => '৫০০ গ্রাম জার (500g)', 'size' => '500g', 'price' => 650.00, 'stock' => 50, 'status' => 'active'
            ]);
            \App\Models\ProductVariant::updateOrCreate(['product_id' => $p1->id, 'sku' => 'KB-SNM-1000G'], [
                'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 1250.00, 'stock' => 30, 'status' => 'active'
            ]);
            \App\Models\ProductImage::updateOrCreate(['product_id' => $p1->id, 'image_path' => 'products/multi-seeds-nuts-250g.jpg'], ['sort_order' => 1]);
            \App\Models\ProductImage::updateOrCreate(['product_id' => $p1->id, 'image_path' => 'products/multi-seeds-nuts-1000g.jpg'], ['sort_order' => 2]);
            \App\Models\ProductImage::updateOrCreate(['product_id' => $p1->id, 'image_path' => 'products/multi-seeds-nuts-500g-tall.jpg'], ['sort_order' => 3]);

            // 2. Premium Chia Seeds
            Product::updateOrCreate(['slug' => 'chia-seeds-500g'], [
                'category_id' => $catNuts->id,
                'brand_id' => $brand->id,
                'name' => 'চিয়া সিড (Premium Chia Seeds 500g)',
                'sku' => 'KB-CHIA-500G',
                'short_description' => 'প্রাকৃতিক, পুষ্টিকর ও স্বাস্থ্যসম্মত ১০০% খাঁটি চিয়া সিড।',
                'description' => "চিয়া সিড শরীরের ওজন নিয়ন্ত্রণে সহায়তা করে, হৃদযন্ত্র সুস্থ রাখে, হজম শক্তি বাড়ায় এবং রোগ প্রতিরোধ ক্ষমতা বৃদ্ধি করে।\n\nব্যবহারবিধি:\nপ্রতিদিন সকালে ১ চা চামচ, দুপুরে/রাতের জন্য ১-২ চা চামচ চিয়া সিড পানিতে ভিজিয়ে নিন। সালাদ, স্মুদি বা দুধে মিশিয়ে খেতে পারেন।\n\nনেট ওজন: ৫০০ গ্রাম।",
                'image' => 'products/chia-seed-500g.jpg',
                'regular_price' => 500.00,
                'sale_price' => 450.00,
                'stock' => 80,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 2,
            ]);

            // 3. Pure Turmeric Powder
            $p3 = Product::updateOrCreate(['slug' => 'pure-turmeric-powder-250g'], [
                'category_id' => $catSpices->id,
                'brand_id' => $brand->id,
                'name' => 'হলুদের গুঁড়া (Pure Turmeric Powder 250g)',
                'sku' => 'KB-HLD-250G',
                'short_description' => 'বাছাইকৃত উৎকৃষ্ট শুকনো হলুদ থেকে আধুনিক প্রযুক্তিতে প্রস্তুত ১০০% স্বাস্থ্যসম্মত ও খাঁটি।',
                'description' => "খাঁটি বাজার বিডি প্রাকৃতিক হলুদের গুঁড়া। ১০০% খাঁটি, কোনো প্রকার কৃত্রিম রঙ বা ভেজাল নেই।\n\nসব ধরনের তরকারি ও মাংস রান্নায় অসাধারণ প্রাকৃতিক রঙ ও ঘ্রাণ দেয়।\n\nনেট ওজন: ২৫০ গ্রাম।",
                'image' => 'products/holud-gura-250g.jpg',
                'regular_price' => 160.00,
                'sale_price' => 140.00,
                'stock' => 100,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 3,
            ]);
            \App\Models\ProductImage::updateOrCreate(['product_id' => $p3->id, 'image_path' => 'products/holud-gura-clean.jpg'], ['sort_order' => 1]);
            \App\Models\ProductImage::updateOrCreate(['product_id' => $p3->id, 'image_path' => 'products/holud-gura-detail.jpg'], ['sort_order' => 2]);

            // 4. Pure Chilli Powder
            Product::updateOrCreate(['slug' => 'pure-chilli-powder-250g'], [
                'category_id' => $catSpices->id,
                'brand_id' => $brand->id,
                'name' => 'মরিচের গুঁড়া (Pure Chilli Powder 250g)',
                'sku' => 'KB-MRC-250G',
                'short_description' => 'বাছাইকৃত উৎকৃষ্ট শুকনো লাল মরিচ থেকে আধুনিক প্রযুক্তিতে প্রস্তুত ১০০% খাঁটি মরিচের গুঁড়া।',
                'description' => "খাঁটি বাজার বিডি প্রাকৃতিক মরিচের গুঁড়া।\n\nসব ধরনের ভর্তা, ভাজি, তরকারি ও মাংস রান্নায় চমৎকার স্বাদ এনে দেয়।\n\nনেট ওজন: ২৫০ গ্রাম।",
                'image' => 'products/morich-gura-250g.jpg',
                'regular_price' => 180.00,
                'sale_price' => 160.00,
                'stock' => 100,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 4,
            ]);

            // 5. Pure Coriander Powder
            Product::updateOrCreate(['slug' => 'pure-coriander-powder-250g'], [
                'category_id' => $catSpices->id,
                'brand_id' => $brand->id,
                'name' => 'ধনিয়ার গুঁড়া (Pure Coriander Powder 250g)',
                'sku' => 'KB-DHN-250G',
                'short_description' => 'বাছাইকৃত উৎকৃষ্ট শুকনো খাঁটি ধনিয়া থেকে প্রস্তুত ১০০% স্বাস্থ্যসম্মত ও খাঁটি।',
                'description' => "খাঁটি বাজার বিডি প্রাকৃতিক ধনিয়ার গুঁড়া। রান্নায় আনে খাঁটি অতুলনীয় সুবাস ও অনন্য স্বাদ।\n\nনেট ওজন: ২৫০ গ্রাম।",
                'image' => 'products/dhonia-gura-250g.jpg',
                'regular_price' => 150.00,
                'sale_price' => 130.00,
                'stock' => 100,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 5,
            ]);
        } catch (\Throwable $e) {
            // Ignore if error
        }

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

        try {
            if (\App\Models\ComboOffer::count() === 0) {
                (new \Database\Seeders\ComboOfferSeeder())->run();
            }
        } catch (\Throwable $e) {
            // Ignore if table not migrated
        }

        $comboOffers = \App\Models\ComboOffer::with('product')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.home', compact('categories', 'products', 'heroBanners', 'comboOffers'));
    }
}
