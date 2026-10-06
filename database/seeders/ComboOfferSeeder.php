<?php

namespace Database\Seeders;

use App\Models\ComboOffer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ComboOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('status', 'active')->get();

        $offersData = [
            ['title' => 'Special Feed Package', 'price' => 340.00, 'offer_text' => '৳ 340'],
            ['title' => 'CFC Plus Booster', 'price' => 460.00, 'offer_text' => '৳ 460'],
            ['title' => 'Vitamin Mineral Mix', 'price' => 420.00, 'offer_text' => '৳ 420'],
            ['title' => 'Fish Growth Medicine', 'price' => 1850.00, 'offer_text' => '৳ 1,850'],
            ['title' => 'Poultry Vaccine Supplement', 'price' => 240.00, 'offer_text' => '৳ 240'],
            ['title' => 'Organic Farm Feed 1kg', 'price' => 310.00, 'offer_text' => '৳ 310'],
            ['title' => 'CFC Super Combo Pack', 'price' => 410.00, 'offer_text' => '৳ 410'],
            ['title' => 'Livestock Calcium Feed', 'price' => 650.00, 'offer_text' => '৳ 650'],
            ['title' => 'Fish Oxygen Powder', 'price' => 190.00, 'offer_text' => '৳ 190'],
            ['title' => 'Dairy Protein Concentrate', 'price' => 280.00, 'offer_text' => '৳ 280'],
            ['title' => 'Aqua Care Supplement', 'price' => 510.00, 'offer_text' => '৳ 510'],
            ['title' => 'CFC Jumbo Farm Combo', 'price' => 1380.00, 'offer_text' => '৳ 1,380'],
        ];

        foreach ($offersData as $idx => $data) {
            $product = $products->skip($idx % max(1, $products->count()))->first();

            ComboOffer::updateOrCreate(
                ['title' => $data['title']],
                [
                    'badge_text' => 'BIG COMBO OFFER',
                    'image' => $product ? $product->image : null,
                    'offer_badge_text' => 'BIG OFFER',
                    'offer_text' => $data['offer_text'],
                    'price' => $data['price'],
                    'link' => $product ? route('products.show', $product->slug) : '/products',
                    'product_id' => $product ? $product->id : null,
                    'status' => 'active',
                    'sort_order' => $idx + 1,
                ]
            );
        }
    }
}
