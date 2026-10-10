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
            ['key' => 'email', 'value' => 'info@khatibazarbd.store', 'group' => 'contact', 'label' => 'Email Address', 'type' => 'text'],
            ['key' => 'address', 'value' => 'ঢাকা, বাংলাদেশ', 'group' => 'contact', 'label' => 'Full Address', 'type' => 'textarea'],
            ['key' => 'inside_dhaka_charge', 'value' => '150', 'group' => 'shipping', 'label' => 'Inside Dhaka Delivery Charge (BDT)', 'type' => 'number'],
            ['key' => 'outside_dhaka_charge', 'value' => '150', 'group' => 'shipping', 'label' => 'Outside Dhaka Delivery Charge (BDT)', 'type' => 'number'],
            ['key' => 'currency_symbol', 'value' => '৳', 'group' => 'general', 'label' => 'Currency Symbol', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Categories
        $catVet = Category::updateOrCreate(['slug' => 'veterinary-medicine'], [
            'name' => 'Veterinary Medicine',
            'description' => 'উচ্চমানের গবাদিপশুর ঔষুধ ও সাপ্লিমেন্ট',
            'image' => 'categories/vet_medicine.png',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $catFish = Category::updateOrCreate(['slug' => 'fish-medicine'], [
            'name' => 'Fish Medicine',
            'description' => 'মাছ চাষের জন্য প্রয়োজনীয় উপাদান ও ঔষুধ',
            'image' => 'categories/fish_medicine.png',
            'status' => 'active',
            'sort_order' => 2,
        ]);

        $catCfc = Category::updateOrCreate(['slug' => 'cfc-plus-combo'], [
            'name' => 'CFC Plus Combo',
            'description' => 'বিশেষ সিএফসি প্লাস খামার প্যাকেজ ও কম্বো',
            'image' => 'categories/cfc_combo.png',
            'status' => 'active',
            'sort_order' => 3,
        ]);

        $catOther = Category::updateOrCreate(['slug' => 'other-products'], [
            'name' => 'Other Products',
            'description' => 'অন্যান্য কৃষি ও খামার পণ্য সামগ্রী',
            'image' => 'categories/other_products.png',
            'status' => 'active',
            'sort_order' => 4,
        ]);

        // 4. Brands
        $b1 = Brand::updateOrCreate(['slug' => 'khati-bajar-organics'], ['name' => 'Khati Bazar Organics', 'status' => 'active']);
        $b2 = Brand::updateOrCreate(['slug' => 'agrovet-ltd'], ['name' => 'AgroVet Ltd', 'status' => 'active']);
        $b3 = Brand::updateOrCreate(['slug' => 'bioaqua-labs'], ['name' => 'BioAqua Labs', 'status' => 'active']);

        // 5. Products with Size & Color Variants
        $cfcProduct = Product::updateOrCreate(['slug' => 'cfc-plus-combo-feed-concentrate'], [
            'category_id' => $catCfc->id,
            'brand_id' => $b1->id,
            'name' => 'CFC Plus Combo Feed Concentrate',
            'sku' => 'KB-CFC-01',
            'short_description' => 'গবাদিপশুর স্বাস্থ্য সুরক্ষা, দুধ উৎপাদন বৃদ্ধি এবং দ্রুত ওজন বাড়ানোর প্রিমিয়াম ফর্মুলা।',
            'description' => 'সিএফসি প্লাস পাউডার খামারের গরু, ছাগল ও মহিষের হজমশক্তি বৃদ্ধি, রোগ প্রতিরোধ ক্ষমতা বাড়ানো এবং দ্রুত শারীরিক বৃদ্ধির জন্য অত্যন্ত কার্যকরী একটি পণ্য।',
            'image' => 'products/cfc_plus.png',
            'regular_price' => 800.00,
            'sale_price' => 800.00,
            'stock' => 500,
            'status' => 'active',
            'is_featured' => true,
            'is_super_offer' => true,
            'sort_order' => 1,
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
