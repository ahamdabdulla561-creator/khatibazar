<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Khati Bajar — Pure Agricultural & Veterinary Supplies',
                'subtitle' => 'Trusted digital marketplace for livestock medicines, CFC plus combo feeds, and fish supplements.',
                'badge_text' => '100% Genuine Farm Products',
                'background_image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1000&auto=format&fit=crop',
                'button_text' => 'Shop Now',
                'button_link' => '/products',
                'position' => 'hero_main',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'title' => 'CFC Plus Combo Feed Concentrate',
                'subtitle' => 'Boost animal immunity, milk production, and overall farm profitability.',
                'badge_text' => 'Special Combo Package',
                'background_image' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1000&auto=format&fit=crop',
                'button_text' => 'View Combo Deals',
                'button_link' => '/category/cfc-plus-combo',
                'position' => 'hero_main',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'title' => 'Pure Fish Medicine & Water Treatment',
                'subtitle' => 'Original vitamins, growth boosters, and fish farming health solutions.',
                'badge_text' => 'Fast Delivery Nationwide',
                'background_image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=1000&auto=format&fit=crop',
                'button_text' => 'Browse All Products',
                'button_link' => '/products',
                'position' => 'hero_main',
                'status' => 'active',
                'sort_order' => 3,
            ]
        ];

        foreach ($banners as $b) {
            Banner::updateOrCreate(['title' => $b['title']], $b);
        }
    }
}
