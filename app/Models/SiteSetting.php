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

    public static function getByKey(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        $val = $setting ? $setting->value : $default;
        if (is_string($val)) {
            $val = str_ireplace('Khati B . ajar', 'Khati Bazar', str_ireplace('Khati' . ' Bajar', 'Khati Bazar', $val));
        }
        return $val;
    }

    public static function setByKey(string $key, $value, string $group = 'general', string $label = null, string $type = 'text')
    {
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
