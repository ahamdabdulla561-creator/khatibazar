<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SuperOffersSeeder extends Seeder
{
    public function run()
    {
        $catVet = Category::where('slug', 'veterinary-medicine')->first()->id ?? 1;
        $catFish = Category::where('slug', 'fish-medicine')->first()->id ?? 2;
        $catCfc = Category::where('slug', 'cfc-plus-combo')->first()->id ?? 3;

        $productsData = [
            ['name' => 'BioAqua Fish Growth Booster 500g', 'slug' => 'bioaqua-fish-growth-booster-500g', 'category_id' => $catFish, 'regular_price' => 800, 'sale_price' => 700, 'is_super_offer' => 1, 'stock' => 50],
            ['name' => 'CFC Plus Combo Feed Concentrate 1kg', 'slug' => 'cfc-plus-combo-feed-concentrate-1kg', 'category_id' => $catCfc, 'regular_price' => 800, 'sale_price' => 750, 'is_super_offer' => 1, 'stock' => 100],
            ['name' => 'Bio-Blue Spray for Animal Wounds 200ml', 'slug' => 'bio-blue-spray-for-animal-wounds', 'category_id' => $catVet, 'regular_price' => 450, 'sale_price' => 390, 'is_super_offer' => 1, 'stock' => 40],
            ['name' => 'Lactiva Cattle Milk Growth Powder 1kg', 'slug' => 'lactiva-cattle-milk-growth-powder-1kg', 'category_id' => $catVet, 'regular_price' => 650, 'sale_price' => 580, 'is_super_offer' => 1, 'stock' => 60],
            ['name' => 'Aqua-Prob+ Soil & Water Probiotic 1kg', 'slug' => 'aqua-prob-soil-water-probiotic-1kg', 'category_id' => $catFish, 'regular_price' => 550, 'sale_price' => 490, 'is_super_offer' => 1, 'stock' => 30],
            ['name' => 'Argunil Fish Parasite Treatment 100ml', 'slug' => 'argunil-fish-parasite-treatment-100ml', 'category_id' => $catFish, 'regular_price' => 600, 'sale_price' => 530, 'is_super_offer' => 1, 'stock' => 45],
            ['name' => 'Reegain Fish Growth Supplement 1kg', 'slug' => 'reegain-fish-growth-supplement-1kg', 'category_id' => $catFish, 'regular_price' => 650, 'sale_price' => 590, 'is_super_offer' => 1, 'stock' => 70],
            ['name' => 'Cal-D-Phos Cattle Calcium Syrup 1L', 'slug' => 'cal-d-phos-cattle-calcium-syrup-1l', 'category_id' => $catVet, 'regular_price' => 420, 'sale_price' => 380, 'is_super_offer' => 1, 'stock' => 80],
            ['name' => 'Vet-Mineral Premix 500g', 'slug' => 'vet-mineral-premix-500g', 'category_id' => $catVet, 'regular_price' => 350, 'sale_price' => 310, 'is_super_offer' => 1, 'stock' => 90],
            ['name' => 'Fish-Vita Vitamin Premix 250g', 'slug' => 'fish-vita-vitamin-premix-250g', 'category_id' => $catFish, 'regular_price' => 280, 'sale_price' => 240, 'is_super_offer' => 1, 'stock' => 50],
            ['name' => 'Super CFC Plus 3kg Big Saver Pack', 'slug' => 'super-cfc-plus-3kg-big-saver-pack', 'category_id' => $catCfc, 'regular_price' => 2000, 'sale_price' => 1850, 'is_super_offer' => 1, 'stock' => 25],
            ['name' => 'Cattle Liver Tonic 1L', 'slug' => 'cattle-liver-tonic-1l', 'category_id' => $catVet, 'regular_price' => 480, 'sale_price' => 420, 'is_super_offer' => 1, 'stock' => 35],
            ['name' => 'Pond Oxygen Tablets 1kg', 'slug' => 'pond-oxygen-tablets-1kg', 'category_id' => $catFish, 'regular_price' => 520, 'sale_price' => 460, 'is_super_offer' => 1, 'stock' => 60],
            ['name' => 'Aqua Clean Pond Sanitizer 500ml', 'slug' => 'aqua-clean-pond-sanitizer-500ml', 'category_id' => $catFish, 'regular_price' => 390, 'sale_price' => 340, 'is_super_offer' => 1, 'stock' => 40],
            ['name' => 'Biofit CFC Plus Feed Supplement 2kg', 'slug' => 'biofit-cfc-plus-feed-supplement-2kg', 'category_id' => $catCfc, 'regular_price' => 1500, 'sale_price' => 1380, 'is_super_offer' => 1, 'stock' => 20],
            ['name' => 'Poultry Growth Booster 1kg', 'slug' => 'poultry-growth-booster-1kg', 'category_id' => $catVet, 'regular_price' => 580, 'sale_price' => 510, 'is_super_offer' => 1, 'stock' => 55],
            ['name' => 'Mastitis Care Teat Spray 250ml', 'slug' => 'mastitis-care-teat-spray-250ml', 'category_id' => $catVet, 'regular_price' => 320, 'sale_price' => 280, 'is_super_offer' => 1, 'stock' => 65],
            ['name' => 'Dewormer Vet Bolus 10 Tablets', 'slug' => 'dewormer-vet-bolus-10-tablets', 'category_id' => $catVet, 'regular_price' => 220, 'sale_price' => 190, 'is_super_offer' => 1, 'stock' => 100],
            ['name' => 'Fish Plankton Growth Enhancer 1kg', 'slug' => 'fish-plankton-growth-enhancer-1kg', 'category_id' => $catFish, 'regular_price' => 720, 'sale_price' => 650, 'is_super_offer' => 1, 'stock' => 40],
            ['name' => 'Bio Aqua Plus Feed Attractant 500g', 'slug' => 'bio-aqua-plus-feed-attractant-500g', 'category_id' => $catFish, 'regular_price' => 460, 'sale_price' => 410, 'is_super_offer' => 1, 'stock' => 50],
        ];

        foreach ($productsData as $p) {
            Product::updateOrCreate(
                ['slug' => $p['slug']],
                array_merge($p, [
                    'status' => 'active',
                    'is_featured' => 1,
                    'description' => '100% genuine quality product for your farm.',
                    'sku' => strtoupper(Str::random(8)),
                    'sort_order' => 1
                ])
            );
        }
    }
}
