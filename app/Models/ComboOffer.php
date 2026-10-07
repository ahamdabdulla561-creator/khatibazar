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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset('storage/' . ltrim($this->image, '/'));
        }
        if ($this->product && $this->product->image_url) {
            return $this->product->image_url;
        }
        return 'https://placehold.co/200x200?text=Khati+Bajar';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
