<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'sort_order',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . ltrim($this->image, '/'));
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('status', 'active');
    }

    protected static function booted()
    {
        static::addGlobalScope('excludeNutsSeeds', function ($builder) {
            if (!request()->is('admin*')) {
                $builder->where('slug', '!=', 'nuts-seeds')
                        ->where('name', 'not like', '%Nuts%')
                        ->where('name', 'not like', '%বাদাম%');
            }
        });
    }

    protected static $cachedActive = null;

    public static function getActiveCached()
    {
        if (static::$cachedActive === null) {
            static::$cachedActive = static::where('status', 'active')
                ->where('slug', '!=', 'nuts-seeds')
                ->where('name', 'not like', '%Nuts%')
                ->where('name', 'not like', '%বাদাম%')
                ->orderBy('sort_order', 'asc')
                ->get();
        }
        return static::$cachedActive;
    }
}
