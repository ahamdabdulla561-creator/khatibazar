<?php

namespace Database\Seeders;

use App\Models\CourierService;
use Illuminate\Database\Seeder;

class CourierServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couriers = [
            [
                'name' => 'Sundarban Courier Service',
                'code' => 'sundarban',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => 'https://www.sundarbancourier.com.bd/tracking?id={tracking_code}',
                'notes' => 'সারাদেশে শাখা ভিত্তিক দ্রুত কুরিয়ার ডেলিভারি সার্ভিস।',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => 'SA Paribahan',
                'code' => 'sa_paribahan',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => null,
                'notes' => 'এস এ পরিবহন পার্সেল ও কুরিয়ার সার্ভিস।',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => 'Pathao Courier',
                'code' => 'pathao',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => 'https://pathao.com/tracking/?consignment_id={tracking_code}',
                'notes' => 'পাঠাও এক্সপ্রেস হোম ডেলিভারি সার্ভিস।',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'name' => 'Steadfast Courier',
                'code' => 'steadfast',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => 'https://steadfast.com.bd/t/{tracking_code}',
                'notes' => 'স্টেডফাস্ট কুরিয়ার ফাস্ট ও সেফ ডেলিভারি।',
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'name' => 'RedX',
                'code' => 'redx',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => 'https://redx.com.bd/track-order/?trackingId={tracking_code}',
                'notes' => 'রেডএক্স লজিস্টিকস ও কুরিয়ার।',
                'status' => 'active',
                'sort_order' => 5,
            ],
            [
                'name' => 'Paperfly',
                'code' => 'paperfly',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => 'https://paperfly.com.bd/tracking/{tracking_code}',
                'notes' => 'পেপারফ্লাই স্মার্ট লজিস্টিকস।',
                'status' => 'active',
                'sort_order' => 6,
            ],
            [
                'name' => 'eCourier',
                'code' => 'ecourier',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => 'https://ecourier.com.bd/track/?id={tracking_code}',
                'notes' => 'ই-কুরিয়ার ডিজিটাল ডেলিভারি সমাধান।',
                'status' => 'active',
                'sort_order' => 7,
            ],
            [
                'name' => 'AJR Courier',
                'code' => 'ajr',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => null,
                'notes' => 'এজেআর কুরিয়ার ও পরিবহন সেবা।',
                'status' => 'active',
                'sort_order' => 8,
            ],
            [
                'name' => 'Janani Express',
                'code' => 'janani',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => null,
                'notes' => 'জননী এক্সপ্রেস পার্সেল সার্ভিস।',
                'status' => 'active',
                'sort_order' => 9,
            ],
            [
                'name' => 'Karatoa Courier Service',
                'code' => 'karatoa',
                'logo' => null,
                'charge' => 0.00,
                'tracking_url_template' => null,
                'notes' => 'করতোয়া কুরিয়ার ও ট্রান্সপোর্ট সার্ভিস।',
                'status' => 'active',
                'sort_order' => 10,
            ],
        ];

        foreach ($couriers as $courierData) {
            CourierService::updateOrCreate(
                ['code' => $courierData['code']],
                $courierData
            );
        }
    }
}
