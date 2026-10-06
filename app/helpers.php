<?php

use App\Models\SiteSetting;

if (!function_exists('format_price')) {
    function format_price($amount): string
    {
        $symbol = site_setting('currency_symbol', '৳');
        return $symbol . ' ' . number_format((float)$amount, 0, '.', ',');
    }
}

if (!function_exists('site_setting')) {
    function site_setting($key, $default = null)
    {
        return SiteSetting::getByKey($key, $default);
    }
}
