<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return 'https://placehold.co/400x400?text=Khati+Bazar';
        }
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        $clean = ltrim($this->image_path, '/');
        if (file_exists(public_path('images/' . $clean))) {
            return asset('images/' . $clean);
        }
        if (file_exists(public_path('storage/' . $clean))) {
            return asset('storage/' . $clean);
        }
        return asset('storage/' . $clean);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
