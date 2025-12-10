<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'author_name',
        'author_email',
        'content',
        'is_approved',
        'ip_address',
        'user_agent',
        'spam_score',
        'spam_status',
        'is_flagged',
        'flag_reason',
        'last_comment_at',
        'contains_links',
        'char_count',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the post that this comment belongs to
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who posted this comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
