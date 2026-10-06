<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_district',
        'shipping_upazila',
        'shipping_address',
        'delivery_area',
        'subtotal',
        'discount_amount',
        'delivery_charge',
        'grand_total',
        'payment_method',
        'courier_service_id',
        'courier_name',
        'courier_tracking_id',
        'sender_number',
        'transaction_id',
        'payment_screenshot',
        'payment_status',
        'order_status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courierService()
    {
        return $this->belongsTo(CourierService::class, 'courier_service_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class)->orderBy('created_at', 'asc');
    }
}
