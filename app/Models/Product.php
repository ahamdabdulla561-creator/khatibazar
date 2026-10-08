<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'image',
        'regular_price',
        'sale_price',
        'discount_percent',
        'stock',
        'status',
        'is_featured',
        'is_super_offer',
        'sort_order',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_super_offer' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return 'https://placehold.co/400x400?text=Khati+Bazar';
        }
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . ltrim($this->image, '/'));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('status', 'active');
    }

    public function getEffectivePriceAttribute()
    {
        if ($this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->regular_price) {
            return $this->sale_price;
        }
        return $this->regular_price;
    }

    public function getDiscountPercentAttribute($value)
    {
        if ($value) {
            return $value;
        }
        if ($this->sale_price && $this->regular_price > 0 && $this->sale_price < $this->regular_price) {
            return round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }
        return 0;
    }

    public function inStock(): bool
    {
        if ($this->variants()->exists()) {
            return $this->activeVariants()->sum('stock') > 0;
        }
        return $this->stock > 0;
    }
}
