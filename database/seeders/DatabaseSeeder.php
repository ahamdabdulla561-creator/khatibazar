<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CourierService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Customer Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@khatibajar.com'],
            [
                'name' => 'Khati Bazar Admin',
                'phone' => '01711112222',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $monikaAdmin = User::updateOrCreate(
            ['email' => 'monika@khatibajar.com'],
            [
                'name' => 'Monika',
                'phone' => '01722223333',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $customer = User::updateOrCreate(
            ['phone' => '01700000000'],
            [
                'name' => 'Demo Customer',
                'email' => 'customer@khatibajar.com',
                'password' => Hash::make('123456'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        // 2. Site Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'খাঁটি বাজার (Khati Bazar)', 'group' => 'general', 'label' => 'Website Name', 'type' => 'text'],
            ['key' => 'phone', 'value' => '01355465191', 'group' => 'contact', 'label' => 'Phone Number', 'type' => 'text'],
            ['key' => 'whatsapp_number', 'value' => '8801355465191', 'group' => 'contact', 'label' => 'WhatsApp Number', 'type' => 'text'],
            ['key' => 'email', 'value' => 'khatibazarbdstore@gmail.com', 'group' => 'contact', 'label' => 'Email Address', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/share/19fM5TXnjj/', 'group' => 'contact', 'label' => 'Facebook Page URL', 'type' => 'text'],
            ['key' => 'address', 'value' => 'ঢাকা, বাংলাদেশ', 'group' => 'contact', 'label' => 'Full Address', 'type' => 'textarea'],
            ['key' => 'inside_dhaka_charge', 'value' => '150', 'group' => 'shipping', 'label' => 'Inside Dhaka Delivery Charge (BDT)', 'type' => 'number'],
            ['key' => 'outside_dhaka_charge', 'value' => '150', 'group' => 'shipping', 'label' => 'Outside Dhaka Delivery Charge (BDT)', 'type' => 'number'],
            ['key' => 'currency_symbol', 'value' => '৳', 'group' => 'general', 'label' => 'Currency Symbol', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Categories
        $catNuts = Category::updateOrCreate(['slug' => 'nuts-seeds'], [
            'name' => 'Nuts & Seeds (বাদাম ও বীজ)',
            'description' => 'প্রাকৃতিক ও প্রিমিয়াম কোয়ালিটি বাদাম এবং পুষ্টিকর বীজ',
            'image' => 'products/multi-seeds-nuts-500g.jpg',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $catSpices = Category::updateOrCreate(['slug' => 'pure-spices'], [
            'name' => 'Pure Spices (খাঁটি গুঁড়া মসলা)',
            'description' => 'নিজস্ব তত্ত্বাবধানে তৈরি ১০০% খাঁটি মসলা',
            'image' => 'products/holud-gura-250g.jpg',
            'status' => 'active',
            'sort_order' => 2,
        ]);

        $catVet = Category::updateOrCreate(['slug' => 'veterinary-medicine'], [
            'name' => 'Veterinary Medicine',
            'description' => 'উচ্চমানের গবাদিপশুর ঔষুধ ও সাপ্লিমেন্ট',
            'image' => 'categories/vet_medicine.png',
            'status' => 'active',
            'sort_order' => 3,
        ]);

        $catFish = Category::updateOrCreate(['slug' => 'fish-medicine'], [
            'name' => 'Fish Medicine',
            'description' => 'মাছ চাষের জন্য প্রয়োজনীয় উপাদান ও ঔষুধ',
            'image' => 'categories/fish_medicine.png',
            'status' => 'active',
            'sort_order' => 4,
        ]);

        // 4. Brands
        $b1 = Brand::updateOrCreate(['slug' => 'khati-bazar'], ['name' => 'খাঁটি বাজার (Khati Bazar)', 'status' => 'active']);
        $b2 = Brand::updateOrCreate(['slug' => 'agrovet-ltd'], ['name' => 'AgroVet Ltd', 'status' => 'active']);
        $b3 = Brand::updateOrCreate(['slug' => 'bioaqua-labs'], ['name' => 'BioAqua Labs', 'status' => 'active']);

        // 5. Products with Size Variants
        // 5.1 Multi Seeds & Nuts Mix
        $p1 = Product::updateOrCreate(['slug' => 'multi-seeds-and-nuts-mix'], [
            'category_id' => $catNuts->id,
            'brand_id' => $b1->id,
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
        ProductVariant::updateOrCreate(['product_id' => $p1->id, 'sku' => 'KB-SNM-250G'], [
            'name' => '২৫০ গ্রাম জার (250g)', 'size' => '250g', 'price' => 350.00, 'stock' => 50, 'status' => 'active'
        ]);
        ProductVariant::updateOrCreate(['product_id' => $p1->id, 'sku' => 'KB-SNM-500G'], [
            'name' => '৫০০ গ্রাম জার (500g)', 'size' => '500g', 'price' => 650.00, 'stock' => 50, 'status' => 'active'
        ]);
        ProductVariant::updateOrCreate(['product_id' => $p1->id, 'sku' => 'KB-SNM-1000G'], [
            'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 1250.00, 'stock' => 30, 'status' => 'active'
        ]);
        \App\Models\ProductImage::updateOrCreate(['product_id' => $p1->id, 'image_path' => 'products/multi-seeds-nuts-250g.jpg'], ['sort_order' => 1]);
        \App\Models\ProductImage::updateOrCreate(['product_id' => $p1->id, 'image_path' => 'products/multi-seeds-nuts-1000g.jpg'], ['sort_order' => 2]);
        \App\Models\ProductImage::updateOrCreate(['product_id' => $p1->id, 'image_path' => 'products/multi-seeds-nuts-500g-tall.jpg'], ['sort_order' => 3]);

        // 5.2 Premium Chia Seeds
        Product::updateOrCreate(['slug' => 'chia-seeds-500g'], [
            'category_id' => $catNuts->id,
            'brand_id' => $b1->id,
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

        // 5.3 Pure Turmeric Powder
        $p3 = Product::updateOrCreate(['slug' => 'pure-turmeric-powder-250g'], [
            'category_id' => $catSpices->id,
            'brand_id' => $b1->id,
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

        // 5.4 Pure Chilli Powder
        Product::updateOrCreate(['slug' => 'pure-chilli-powder-250g'], [
            'category_id' => $catSpices->id,
            'brand_id' => $b1->id,
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

        // 5.5 Pure Coriander Powder
        Product::updateOrCreate(['slug' => 'pure-coriander-powder-250g'], [
            'category_id' => $catSpices->id,
            'brand_id' => $b1->id,
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

        $variants = [
            ['name' => '1 KG Pack', 'size' => '1 KG', 'color' => 'Green', 'sku' => 'KB-CFC-1KG', 'price' => 800.00, 'stock' => 100],
            ['name' => '2 KG Pack', 'size' => '2 KG', 'color' => 'Green', 'sku' => 'KB-CFC-2KG', 'price' => 1400.00, 'stock' => 80],
            ['name' => '3 KG Pack', 'size' => '3 KG', 'color' => 'Yellow', 'sku' => 'KB-CFC-3KG', 'price' => 2000.00, 'stock' => 60],
            ['name' => '6 KG Mega Pack', 'size' => '6 KG', 'color' => 'Red', 'sku' => 'KB-CFC-6KG', 'price' => 3600.00, 'stock' => 40],
            ['name' => '12 KG Jumbo Pack', 'size' => '12 KG', 'color' => 'Gold', 'sku' => 'KB-CFC-12KG', 'price' => 6800.00, 'stock' => 30],
        ];

        foreach ($variants as $v) {
            ProductVariant::updateOrCreate(
                ['product_id' => $cfcProduct->id, 'sku' => $v['sku']],
                [
                    'name' => $v['name'],
                    'size' => $v['size'],
                    'color' => $v['color'],
                    'price' => $v['price'],
                    'stock' => $v['stock'],
                    'status' => 'active',
                ]
            );
        }

        $p2 = Product::updateOrCreate(['slug' => 'super-vet-calcium-mineral-solution'], [
            'category_id' => $catVet->id,
            'brand_id' => $b2->id,
            'name' => 'Super Vet Calcium & Mineral Solution',
            'sku' => 'KB-VET-101',
            'short_description' => 'দুগ্ধবতী গাভীর জন্য উচ্চমাত্রার লিকুইড ক্যালসিয়াম ও ফসফরাস।',
            'description' => 'গাভীর হাড় শক্ত করতে এবং দুগ্ধ নিঃসরণ ক্ষমতা বহুগুণ বাড়াতে সহায়তা করে। প্রতিদিন নির্ধারিত মাত্রায় ব্যবহার্য।',
            'image' => 'products/super_vet.png',
            'regular_price' => 650.00,
            'sale_price' => 580.00,
            'discount_percent' => 11,
            'stock' => 45,
            'status' => 'active',
            'is_featured' => true,
            'is_super_offer' => false,
            'sort_order' => 2,
        ]);

        ProductVariant::updateOrCreate(['product_id' => $p2->id, 'sku' => 'KB-VET-101-1L'], [
            'name' => '1 Liter Bottle', 'size' => '1 L', 'color' => 'White', 'price' => 580.00, 'stock' => 25, 'status' => 'active'
        ]);
        ProductVariant::updateOrCreate(['product_id' => $p2->id, 'sku' => 'KB-VET-101-5L'], [
            'name' => '5 Liter Jar', 'size' => '5 L', 'color' => 'Blue', 'price' => 2500.00, 'stock' => 20, 'status' => 'active'
        ]);

        $p3 = Product::updateOrCreate(['slug' => 'bioaqua-fish-growth-booster-500g'], [
            'category_id' => $catFish->id,
            'brand_id' => $b3->id,
            'name' => 'BioAqua Fish Growth Booster 500g',
            'sku' => 'KB-FISH-201',
            'short_description' => 'পুকুরের পানির গুণমান বৃদ্ধি এবং মাছের দ্রুত দৈহিক বৃদ্ধি সহায়তায় প্রবায়োটিক।',
            'description' => 'পুকুরের অ্যামোনিয়া গ্যাস দূর করে এবং মাছকে সব ধরনের রোগবালাই থেকে রক্ষা করে।',
            'image' => 'products/bioaqua_fish.png',
            'regular_price' => 450.00,
            'sale_price' => 390.00,
            'discount_percent' => 13,
            'stock' => 60,
            'status' => 'active',
            'is_featured' => true,
            'is_super_offer' => true,
            'sort_order' => 3,
        ]);

        $p4 = Product::updateOrCreate(['slug' => 'organic-farm-soil-enhancer-5kg'], [
            'category_id' => $catOther->id,
            'brand_id' => $b1->id,
            'name' => 'Organic Farm Soil Enhancer 5KG',
            'sku' => 'KB-AGRI-301',
            'short_description' => '১০০% জৈব উপাদানে তৈরি মাটির উর্বরতা বৃদ্ধিকারী প্রাকৃতিক সার।',
            'description' => 'ফসলের ফলন দ্বিগুণ করতে এবং মাটির অনুজীব সক্রিয় রাখতে দারুণ উপযোগী।',
            'image' => 'products/soil_enhancer.png',
            'regular_price' => 500.00,
            'sale_price' => 450.00,
            'discount_percent' => 10,
            'stock' => 100,
            'status' => 'active',
            'is_featured' => true,
            'is_super_offer' => false,
            'sort_order' => 4,
        ]);

        // 6. Seed Sample Orders for Day-wise, Weekly, Monthly Sales Reports
        $courier = CourierService::first();
        $courierName = $courier ? $courier->name : 'Sundarban Courier Service';

        // Sample Order 1: Today
        $o1 = Order::updateOrCreate(['order_number' => 'KB-ORD-20261003-01'], [
            'user_id' => $customer->id,
            'customer_name' => 'Md. Rahim',
            'customer_phone' => '01712345678',
            'customer_email' => 'rahim@example.com',
            'shipping_address' => 'Mirpur-10, Dhaka',
            'shipping_district' => 'Dhaka',
            'shipping_upazila' => 'Mirpur',
            'courier_name' => 'Sundarban Courier Service',
            'subtotal' => 2400.00,
            'delivery_charge' => 70.00,
            'discount_amount' => 0.00,
            'grand_total' => 2470.00,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'created_at' => Carbon::now(),
        ]);
        OrderItem::updateOrCreate(['order_id' => $o1->id, 'product_id' => $cfcProduct->id], [
            'product_name' => $cfcProduct->name,
            'unit_price' => 800.00,
            'quantity' => 3,
            'subtotal' => 2400.00,
        ]);

        // Sample Order 2: Yesterday (This Week)
        $o2 = Order::updateOrCreate(['order_number' => 'KB-ORD-20261002-02'], [
            'user_id' => $customer->id,
            'customer_name' => 'Karim Farmer',
            'customer_phone' => '01898765432',
            'shipping_address' => 'Bogura Sadar, Bogura',
            'shipping_district' => 'Bogura',
            'shipping_upazila' => 'Sadar',
            'courier_name' => 'SA Paribahan',
            'subtotal' => 1160.00,
            'delivery_charge' => 130.00,
            'discount_amount' => 0.00,
            'grand_total' => 1290.00,
            'payment_method' => 'bkash',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'created_at' => Carbon::now()->subDays(1),
        ]);
        OrderItem::updateOrCreate(['order_id' => $o2->id, 'product_id' => $p2->id], [
            'product_name' => $p2->name,
            'unit_price' => 580.00,
            'quantity' => 2,
            'subtotal' => 1160.00,
        ]);

        // Sample Order 3: 5 Days ago (This Month)
        $o3 = Order::updateOrCreate(['order_number' => 'KB-ORD-20260928-03'], [
            'user_id' => $customer->id,
            'customer_name' => 'Alim Enterprise',
            'customer_phone' => '01911223344',
            'shipping_address' => 'Savar, Dhaka',
            'shipping_district' => 'Dhaka',
            'shipping_upazila' => 'Savar',
            'courier_name' => 'Pathao Courier',
            'subtotal' => 3900.00,
            'delivery_charge' => 130.00,
            'discount_amount' => 0.00,
            'grand_total' => 4030.00,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'created_at' => Carbon::now()->subDays(5),
        ]);
        OrderItem::updateOrCreate(['order_id' => $o3->id, 'product_id' => $p3->id], [
            'product_name' => $p3->name,
            'unit_price' => 390.00,
            'quantity' => 10,
            'subtotal' => 3900.00,
        ]);
    }
}
