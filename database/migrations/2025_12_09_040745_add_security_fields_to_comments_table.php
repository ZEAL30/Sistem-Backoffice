<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->index(); // Track IP for spam detection
            $table->string('user_agent')->nullable(); // Track user agent
            $table->integer('spam_score')->default(0); // Spam detection score (0-100)
            $table->string('spam_status')->default('clean')->index(); // clean, suspicious, spam
            $table->boolean('is_flagged')->default(false)->index(); // Manual flag by admin
            $table->text('flag_reason')->nullable(); // Reason for flagging
            $table->timestamp('last_comment_at')->nullable(); // Track last comment time for rate limiting
            $table->boolean('contains_links')->default(false); // Flag if comment has external links
            $table->integer('char_count')->default(0); // Character count for analysis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'user_agent',
                'spam_score',
                'spam_status',
                'is_flagged',
                'flag_reason',
                'last_comment_at',
                'contains_links',
                'char_count'
            ]);
        });
    }
};
