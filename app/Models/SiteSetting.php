<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'type',
    ];

    protected static $cachedSettings = null;

    public static function getByKey(string $key, $default = null)
    {
        if (static::$cachedSettings === null) {
            try {
                static::$cachedSettings = static::pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                static::$cachedSettings = [];
            }
        }

        if (array_key_exists($key, static::$cachedSettings) && static::$cachedSettings[$key] !== null && static::$cachedSettings[$key] !== '') {
            return static::$cachedSettings[$key];
        }

        if ($default !== null) {
            return $default;
        }

        $fallbacks = [
            'site_name' => 'Khati Bazar',
            'phone' => '01355465191',
            'whatsapp_number' => '8801355465191',
            'email' => 'khatibazarbd91@gmail.com',
            'facebook_url' => 'https://www.facebook.com/share/19fM5TXnjj/',
            'address' => 'ঝিনাইদহ, বাংলাদেশ',
            'inside_dhaka_charge' => '150',
            'outside_dhaka_charge' => '150',
            'currency_symbol' => '৳',
            'bkash_number' => '01355465191',
            'nagad_number' => '01355465191',
            'rocket_number' => '01355465191',
            'bkash_type' => 'Personal',
            'nagad_type' => 'Personal',
            'rocket_type' => 'Personal',
            'bkash_instruction' => 'bKash নম্বরে Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।',
            'nagad_instruction' => 'Nagad নম্বরে Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।',
            'rocket_instruction' => 'Rocket নম্বরে Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।',
        ];

        return $fallbacks[$key] ?? null;
    }

    public static function setByKey(string $key, $value, string $group = 'general', string $label = null, string $type = 'text')
    {
        static::$cachedSettings = null;
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'label' => $label ?? ucfirst(str_replace('_', ' ', $key)),
                'type' => $type,
            ]
        );
    }
}
