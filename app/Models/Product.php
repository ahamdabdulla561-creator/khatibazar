<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'image',
        'regular_price',
        'sale_price',
        'discount_percent',
        'stock',
        'status',
        'is_featured',
        'is_super_offer',
        'sort_order',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_super_offer' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return 'https://placehold.co/400x400?text=Khati+Bazar';
        }
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        $clean = ltrim($this->image, '/');
        if (file_exists(public_path('images/' . $clean))) {
            return asset('images/' . $clean);
        }
        if (file_exists(public_path('storage/' . $clean))) {
            return asset('storage/' . $clean);
        }
        return asset('storage/' . $clean);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('status', 'active');
    }

    public function getEffectivePriceAttribute()
    {
        if ($this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->regular_price) {
            return $this->sale_price;
        }
        return $this->regular_price;
    }

    public function getDiscountPercentAttribute($value)
    {
        if ($value) {
            return $value;
        }
        if ($this->sale_price && $this->regular_price > 0 && $this->sale_price < $this->regular_price) {
            return round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }
        return 0;
    }

    public function inStock(): bool
    {
        if ($this->variants()->exists()) {
            return $this->activeVariants()->sum('stock') > 0;
        }
        return $this->stock > 0;
    }

    public static function ensureInitialProducts(): void
    {
        try {
            // Ensure Brands
            $b1 = Brand::firstOrCreate(['slug' => 'khati-bazar'], ['name' => 'খাঁটি বাজার (Khati Bazar)', 'status' => 'active']);
            $b2 = Brand::firstOrCreate(['slug' => 'agrovet-ltd'], ['name' => 'AgroVet Ltd', 'status' => 'active']);
            $b3 = Brand::firstOrCreate(['slug' => 'bioaqua-labs'], ['name' => 'BioAqua Labs', 'status' => 'active']);

            // Ensure Categories
            $catSpices = Category::firstOrCreate(['slug' => 'pure-spices'], [
                'name' => 'Pure Spices (খাঁটি গুঁড়া মসলা)',
                'description' => 'নিজস্ব তত্ত্বাবধানে তৈরি ১০০% খাঁটি মসলা',
                'image' => 'products/holud-gura-250g.jpg',
                'status' => 'active',
                'sort_order' => 1,
            ]);

            $catVet = Category::firstOrCreate(['slug' => 'veterinary-medicine'], [
                'name' => 'Veterinary Medicine',
                'description' => 'উচ্চমানের গবাদিপশুর ঔষুধ ও সাপ্লিমেন্ট',
                'image' => 'categories/vet_medicine.png',
                'status' => 'active',
                'sort_order' => 2,
            ]);

            $catFish = Category::firstOrCreate(['slug' => 'fish-medicine'], [
                'name' => 'Fish Medicine',
                'description' => 'মাছ চাষের জন্য প্রয়োজনীয় উপাদান ও ঔষুধ',
                'image' => 'categories/fish_medicine.png',
                'status' => 'active',
                'sort_order' => 3,
            ]);

            $catOther = Category::firstOrCreate(['slug' => 'other-products'], [
                'name' => 'Other Products',
                'description' => 'অন্যান্য কৃষি ও খামার পণ্য সামগ্রী',
                'image' => 'products/soil_enhancer.png',
                'status' => 'active',
                'sort_order' => 4,
            ]);

            // 1. Pure Turmeric Powder
            $pTurmeric = self::updateOrCreate(['slug' => 'pure-turmeric-powder-250g'], [
                'category_id' => $catSpices->id,
                'brand_id' => $b1->id,
                'name' => 'হলুদের গুঁড়া (Pure Turmeric Powder 250g)',
                'sku' => 'KB-HLD-250G',
                'short_description' => 'বাছাইকৃত উৎকৃষ্ট শুকনো হলুদ থেকে আধুনিক প্রযুক্তিতে প্রস্তুত ১০০% স্বাস্থ্যসম্মত ও খাঁটি।',
                'description' => "খাঁটি বাজার বিডি প্রাকৃতিক হলুদের গুঁড়া। ১০০% খাঁটি, কোনো প্রকার কৃত্রিম রঙ বা ভেজাল নেই。\n\nসব ধরনের তরকারি ও মাংস রান্নায় অসাধারণ প্রাকৃতিক রঙ ও ঘ্রাণ দেয়।\n\nনেট ওজন: ২৫০ গ্রাম।",
                'image' => 'products/holud-gura-250g.jpg',
                'regular_price' => 160.00,
                'sale_price' => 140.00,
                'stock' => 100,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 1,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pTurmeric->id, 'sku' => 'KB-HLD-250G'], [
                'name' => '২৫০ গ্রাম (250g)', 'size' => '250g', 'price' => 140.00, 'stock' => 50, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pTurmeric->id, 'sku' => 'KB-HLD-500G'], [
                'name' => '৫০০ গ্রাম (500g)', 'size' => '500g', 'price' => 270.00, 'stock' => 30, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pTurmeric->id, 'sku' => 'KB-HLD-1000G'], [
                'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 520.00, 'stock' => 20, 'status' => 'active'
            ]);
            ProductImage::updateOrCreate(['product_id' => $pTurmeric->id, 'image_path' => 'products/holud-gura-clean.jpg'], ['sort_order' => 1]);
            ProductImage::updateOrCreate(['product_id' => $pTurmeric->id, 'image_path' => 'products/holud-gura-detail.jpg'], ['sort_order' => 2]);

            // 2. Pure Chilli Powder
            $pChilli = self::updateOrCreate(['slug' => 'pure-chilli-powder-250g'], [
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
                'sort_order' => 2,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pChilli->id, 'sku' => 'KB-MRC-250G'], [
                'name' => '২৫০ গ্রাম (250g)', 'size' => '250g', 'price' => 160.00, 'stock' => 50, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pChilli->id, 'sku' => 'KB-MRC-500G'], [
                'name' => '৫০০ গ্রাম (500g)', 'size' => '500g', 'price' => 310.00, 'stock' => 30, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pChilli->id, 'sku' => 'KB-MRC-1000G'], [
                'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 600.00, 'stock' => 20, 'status' => 'active'
            ]);

            // 3. Pure Coriander Powder
            $pCoriander = self::updateOrCreate(['slug' => 'pure-coriander-powder-250g'], [
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
                'sort_order' => 3,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pCoriander->id, 'sku' => 'KB-DHN-250G'], [
                'name' => '২৫০ গ্রাম (250g)', 'size' => '250g', 'price' => 130.00, 'stock' => 50, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pCoriander->id, 'sku' => 'KB-DHN-500G'], [
                'name' => '৫০০ গ্রাম (500g)', 'size' => '500g', 'price' => 250.00, 'stock' => 30, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pCoriander->id, 'sku' => 'KB-DHN-1000G'], [
                'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 480.00, 'stock' => 20, 'status' => 'active'
            ]);

            // 4. Premium Chia Seeds
            $pChia = self::updateOrCreate(['slug' => 'chia-seeds-500g'], [
                'category_id' => $catSpices->id,
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
                'sort_order' => 4,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pChia->id, 'sku' => 'KB-CHIA-250G'], [
                'name' => '২৫০ গ্রাম জার (250g)', 'size' => '250g', 'price' => 240.00, 'stock' => 40, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pChia->id, 'sku' => 'KB-CHIA-500G'], [
                'name' => '৫০০ গ্রাম জার (500g)', 'size' => '500g', 'price' => 450.00, 'stock' => 40, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pChia->id, 'sku' => 'KB-CHIA-1000G'], [
                'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 850.00, 'stock' => 20, 'status' => 'active'
            ]);

            // 5. Multi Seeds & Nuts Mix
            $pMix = self::updateOrCreate(['slug' => 'multi-seeds-and-nuts-mix'], [
                'category_id' => $catSpices->id,
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
                'sort_order' => 5,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pMix->id, 'sku' => 'KB-SNM-250G'], [
                'name' => '২৫০ গ্রাম জার (250g)', 'size' => '250g', 'price' => 350.00, 'stock' => 50, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pMix->id, 'sku' => 'KB-SNM-500G'], [
                'name' => '৫০০ গ্রাম জার (500g)', 'size' => '500g', 'price' => 650.00, 'stock' => 50, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pMix->id, 'sku' => 'KB-SNM-1000G'], [
                'name' => '১০০০ গ্রাম / ১ কেজি (1000g)', 'size' => '1000g', 'price' => 1250.00, 'stock' => 30, 'status' => 'active'
            ]);
            ProductImage::updateOrCreate(['product_id' => $pMix->id, 'image_path' => 'products/multi-seeds-nuts-250g.jpg'], ['sort_order' => 1]);
            ProductImage::updateOrCreate(['product_id' => $pMix->id, 'image_path' => 'products/multi-seeds-nuts-1000g.jpg'], ['sort_order' => 2]);
            ProductImage::updateOrCreate(['product_id' => $pMix->id, 'image_path' => 'products/multi-seeds-nuts-500g-tall.jpg'], ['sort_order' => 3]);

            // 6. CFC Plus Combo Feed Concentrate
            $pCfc = self::updateOrCreate(['slug' => 'cfc-plus-combo-feed-concentrate'], [
                'category_id' => $catVet->id,
                'brand_id' => $b1->id,
                'name' => 'CFC Plus Combo Feed Concentrate',
                'sku' => 'KB-CFC-01',
                'short_description' => 'গবাদিপশুর স্বাস্থ্য সুরক্ষা, দুধ উৎপাদন বৃদ্ধি এবং দ্রুত ওজন বাড়ানোর প্রিমিয়াম ফর্মুলা।',
                'description' => 'সিএফসি প্লাস পাউডার গবাদিপশুর হজমশক্তি বৃদ্ধি, রোগ প্রতিরোধ ক্ষমতা বাড়ানো এবং দ্রুত শারীরিক বৃদ্ধির জন্য একটি অত্যন্ত কার্যকর পণ্য।',
                'image' => 'products/cfc_plus.png',
                'regular_price' => 800.00,
                'sale_price' => 800.00,
                'stock' => 100,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 6,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pCfc->id, 'sku' => 'KB-CFC-1KG'], [
                'name' => '1 KG Pack', 'size' => '1 KG', 'color' => 'Green', 'price' => 800.00, 'stock' => 100, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pCfc->id, 'sku' => 'KB-CFC-2KG'], [
                'name' => '2 KG Pack', 'size' => '2 KG', 'color' => 'Green', 'price' => 1400.00, 'stock' => 80, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pCfc->id, 'sku' => 'KB-CFC-3KG'], [
                'name' => '3 KG Pack', 'size' => '3 KG', 'color' => 'Yellow', 'price' => 2000.00, 'stock' => 60, 'status' => 'active'
            ]);

            // 7. Super Vet Calcium & Mineral Solution
            $pVet = self::updateOrCreate(['slug' => 'super-vet-calcium-mineral-solution'], [
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
                'sort_order' => 7,
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pVet->id, 'sku' => 'KB-VET-101-1L'], [
                'name' => '1 Liter Bottle', 'size' => '1 L', 'color' => 'White', 'price' => 580.00, 'stock' => 25, 'status' => 'active'
            ]);
            ProductVariant::updateOrCreate(['product_id' => $pVet->id, 'sku' => 'KB-VET-101-5L'], [
                'name' => '5 Liter Jar', 'size' => '5 L', 'color' => 'Blue', 'price' => 2500.00, 'stock' => 20, 'status' => 'active'
            ]);

            // 8. BioAqua Fish Growth Booster 500g
            self::updateOrCreate(['slug' => 'bioaqua-fish-growth-booster-500g'], [
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
                'sort_order' => 8,
            ]);

            // 9. Organic Farm Soil Enhancer 5KG
            self::updateOrCreate(['slug' => 'organic-farm-soil-enhancer-5kg'], [
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
                'sort_order' => 9,
            ]);

            // Deactivate test product
            self::where('slug', 'tcuf')->orWhere('name', 'Tcuf')->update(['status' => 'inactive']);
        } catch (\Throwable $e) {
            // Silently handle any DB exception to prevent 500 errors
        }
    }
}
