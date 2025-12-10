<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categorizable', 'categorizables', 'category_id', 'categorizable_id');
    }

    public function products()
    {
        // Product model may not exist yet; keep the relation ready
        return $this->morphedByMany(\App\Models\Product::class, 'categorizable', 'categorizables', 'category_id', 'categorizable_id');
    }
}
