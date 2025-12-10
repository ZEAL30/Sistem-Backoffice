<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class HtmlSanitizer
{
    /**
     * Sanitize HTML content using HTMLPurifier if available, otherwise use fallback.
     * Removes script/style blocks and dangerous attributes.
     */
    public static function purify(string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $html = str_replace("\0", '', $html);

        // Remove script/style blocks as safety pre-step
        $html = preg_replace('#<script[^>]*?>.*?</script>#is', '', $html);
        $html = preg_replace('#<style[^>]*?>.*?</style>#is', '', $html);

        // Check if HTMLPurifier is available and enabled
        if (class_exists('HTMLPurifier') && Config::get('sanitize.use_html_purifier', true)) {
            try {
                $config = \HTMLPurifier_Config::createDefault();
                $purifier = new \HTMLPurifier($config);
                return trim($purifier->purify($html));
            } catch (\Exception $e) {
                // fall back to fallback method if HTMLPurifier fails
                return self::fallbackSanitize($html);
            }
        }

        return self::fallbackSanitize($html);
    }

    /**
     * Fallback sanitization: allows a safe subset of tags and removes dangerous attributes.
     */
    protected static function fallbackSanitize(string $html): string
    {
        // Allow basic safe tags for content
        $allowed = '<p><br><a><strong><em><ul><ol><li><h1><h2><h3><blockquote><img><figure><figcaption><span><div>';
        $clean = strip_tags($html, $allowed);

        // Remove potentially dangerous attributes (on* attributes, javascript: URIs)
        $clean = preg_replace_callback('#<(\w+)([^>]*)>#', function ($m) {
            $tag = $m[1];
            $attrs = $m[2];

            // Remove on* event attributes
            $attrs = preg_replace('/on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $attrs);

            // Remove javascript: URIs from href/src
            $attrs = preg_replace_callback('/(href|src)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', function ($mm) {
                $val = $mm[2];
                // Strip quotes
                $valClean = trim($val, '\"\'');
                if (preg_match('/^\s*javascript:/i', $valClean)) {
                    return '';
                }
                return $mm[1] . '=' . $mm[2];
            }, $attrs);

            return "<" . $tag . $attrs . ">";
        }, $clean);

        return trim($clean);
    }
}
