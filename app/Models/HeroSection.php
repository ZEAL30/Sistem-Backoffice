<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_text',
        'title',
        'description',
        'button_text',
        'button_url',
        'image',
        'background_gradient',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active hero section
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->first();
    }
}

