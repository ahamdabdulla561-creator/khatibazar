<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // bKash
            ['key' => 'bkash_status', 'value' => '1', 'group' => 'payment', 'label' => 'bKash Status', 'type' => 'text'],
            ['key' => 'bkash_number', 'value' => '01711-000000', 'group' => 'payment', 'label' => 'bKash Number', 'type' => 'text'],
            ['key' => 'bkash_type', 'value' => 'Personal', 'group' => 'payment', 'label' => 'bKash Account Type', 'type' => 'text'],
            ['key' => 'bkash_instruction', 'value' => 'bKash Personal নম্বর 01711-000000 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।', 'group' => 'payment', 'label' => 'bKash Instructions', 'type' => 'textarea'],

            // Nagad
            ['key' => 'nagad_status', 'value' => '1', 'group' => 'payment', 'label' => 'Nagad Status', 'type' => 'text'],
            ['key' => 'nagad_number', 'value' => '01822-000000', 'group' => 'payment', 'label' => 'Nagad Number', 'type' => 'text'],
            ['key' => 'nagad_type', 'value' => 'Personal', 'group' => 'payment', 'label' => 'Nagad Account Type', 'type' => 'text'],
            ['key' => 'nagad_instruction', 'value' => 'Nagad Personal নম্বর 01822-000000 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।', 'group' => 'payment', 'label' => 'Nagad Instructions', 'type' => 'textarea'],

            // Rocket
            ['key' => 'rocket_status', 'value' => '1', 'group' => 'payment', 'label' => 'Rocket Status', 'type' => 'text'],
            ['key' => 'rocket_number', 'value' => '01933-000000-8', 'group' => 'payment', 'label' => 'Rocket Number', 'type' => 'text'],
            ['key' => 'rocket_type', 'value' => 'Personal', 'group' => 'payment', 'label' => 'Rocket Account Type', 'type' => 'text'],
            ['key' => 'rocket_instruction', 'value' => 'Rocket Personal নম্বর 01933-000000-8 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।', 'group' => 'payment', 'label' => 'Rocket Instructions', 'type' => 'textarea'],

            // Cash on Delivery
            ['key' => 'cod_status', 'value' => '1', 'group' => 'payment', 'label' => 'COD Status', 'type' => 'text'],
            ['key' => 'cod_instruction', 'value' => 'পণ্য বুঝে পেয়ে ক্যাশ টাকা পরিশোধ করুন।', 'group' => 'payment', 'label' => 'COD Instructions', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
