<?php

return [
    /**
     * Fields that should allow (safe) HTML instead of being stripped.
     * Example: ['description', 'content', 'body']
     */
    'allow_html' => env('SANITIZE_ALLOW_HTML', 'description,content,body'),

    /**
     * If true and HTMLPurifier is installed, fields in allow_html
     * will be purified using HTMLPurifier. If false, the middleware
     * will fall back to strip_tags for those fields.
     */
    'use_html_purifier' => env('SANITIZE_USE_HTMLPURIFIER', true),

    /**
     * Whether to run htmlspecialchars() on non-allow_html fields
     * after strip_tags. Defaults true for extra safety.
     */
    'encode_non_html_fields' => env('SANITIZE_ENCODE', true),
];
