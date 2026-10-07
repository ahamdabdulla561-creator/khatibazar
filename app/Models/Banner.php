<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'image',
        'background_image',
        'button_text',
        'button_link',
        'position',
        'status',
        'sort_order',
    ];

    protected $appends = ['image_url', 'bg_image_url'];

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

    public function getBgImageUrlAttribute()
    {
        if (!empty($this->background_image)) {
            if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
                return $this->background_image;
            }
            return asset('storage/' . ltrim($this->background_image, '/'));
        }
        return $this->image_url ?: 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1000&auto=format&fit=crop';
    }

    public function scopeActiveHero($query)
    {
        return $query->where('position', 'hero_main')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc');
    }
}
