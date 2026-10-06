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

    public function scopeActiveHero($query)
    {
        return $query->where('position', 'hero_main')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc');
    }
}
