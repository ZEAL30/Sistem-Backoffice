<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'content',
        'data',
        'order',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get active sections ordered by order field
     */
    public static function getActiveSections()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get sections by type
     */
    public static function getByType($type)
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}

