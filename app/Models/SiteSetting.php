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
        if ($key === 'phone') {
            return '01355465191';
        }
        if ($key === 'whatsapp_number') {
            return '8801355465191';
        }
        if ($key === 'email') {
            return 'khatibazarbd91@gmail.com';
        }
        if ($key === 'facebook_url') {
            return 'https://www.facebook.com/share/19fM5TXnjj/';
        }
        if ($key === 'address') {
            return 'ঝিনাইদহ, বাংলাদেশ';
        }
        if (in_array($key, ['bkash_number', 'nagad_number', 'rocket_number'])) {
            return '01355465191';
        }
        if (in_array($key, ['bkash_type', 'nagad_type', 'rocket_type'])) {
            return 'Personal';
        }
        if ($key === 'bkash_instruction') {
            return 'bKash Personal নম্বর 01355465191 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।';
        }
        if ($key === 'nagad_instruction') {
            return 'Nagad Personal নম্বর 01355465191 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।';
        }
        if ($key === 'rocket_instruction') {
            return 'Rocket Personal নম্বর 01355465191 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।';
        }
        if (static::$cachedSettings === null) {
            try {
                static::$cachedSettings = static::pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                static::$cachedSettings = [];
            }
        }
        $val = array_key_exists($key, static::$cachedSettings) ? static::$cachedSettings[$key] : $default;
        if (is_string($val)) {
            $val = str_ireplace('Khati B . ajar', 'Khati Bazar', str_ireplace('Khati' . ' Bajar', 'Khati Bazar', $val));
        }
        return $val;
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
