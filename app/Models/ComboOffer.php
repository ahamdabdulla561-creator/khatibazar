<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'badge_text',
        'image',
        'offer_badge_text',
        'offer_text',
        'price',
        'link',
        'product_id',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
