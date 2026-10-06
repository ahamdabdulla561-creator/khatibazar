<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'logo',
        'charge',
        'tracking_url_template',
        'notes',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'charge' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTrackingUrl($trackingCode)
    {
        if (empty($this->tracking_url_template) || empty($trackingCode)) {
            return null;
        }

        if (str_contains($this->tracking_url_template, '{tracking_code}')) {
            return str_replace('{tracking_code}', urlencode($trackingCode), $this->tracking_url_template);
        }

        return rtrim($this->tracking_url_template, '/') . '/' . urlencode($trackingCode);
    }
}
