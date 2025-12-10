<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class SanitizeInput
{
    /**
     * Handle an incoming request: sanitize all string inputs.
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        $allowHtml = Config::get('sanitize.allow_html', '');
        // normalize allow_html to array of keys
        $allowHtml = is_array($allowHtml) ? $allowHtml : array_filter(array_map('trim', explode(',', (string) $allowHtml)));

        $usePurifier = Config::get('sanitize.use_html_purifier', true) && class_exists('HTMLPurifier');

        $sanitized = $this->sanitizeArray($input, $allowHtml, $usePurifier);

        // Merge sanitized input back into the request
        $request->merge($sanitized);

        return $next($request);
    }

    /**
     * Recursively sanitize an array of inputs.
     * Skip UploadedFile instances and non-scalar values that are not arrays.
     */
    protected function sanitizeArray(array $data, array $allowHtml = [], bool $usePurifier = false): array
    {
        $out = [];

        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                // don't modify uploaded files
                $out[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                $out[$key] = $this->sanitizeArray($value, $allowHtml, $usePurifier);
                continue;
            }

            if (is_string($value)) {
                // If field is allowed to have HTML, treat differently
                if (in_array($key, $allowHtml, true)) {
                    $out[$key] = $this->sanitizeHtmlField($value, $usePurifier);
                } else {
                    $out[$key] = $this->sanitizeValue($value);
                }
                continue;
            }

            // leave other types as-is (int, bool, null, objects)
            $out[$key] = $value;
        }

        return $out;
    }

    /**
     * Sanitize a single string value.
     * - Remove null bytes
     * - Strip tags (remove HTML/JS)
     * - Encode special characters to HTML entities
     */
    protected function sanitizeValue(string $value): string
    {
        // remove null bytes
        $value = str_replace("\0", '', $value);

        // remove script and style blocks entirely
        $value = preg_replace('#<script[^>]*?>.*?</script>#is', '', $value);
        $value = preg_replace('#<style[^>]*?>.*?</style>#is', '', $value);

        // strip any remaining HTML tags
        $value = strip_tags($value);

        // encode special chars (prevents HTML injection when using unescaped output)
        if (Config::get('sanitize.encode_non_html_fields', true)) {
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        // optional: collapse excessive whitespace
        $value = preg_replace('/\s{2,}/', ' ', $value);

        return trim($value);
    }

    /**
     * Sanitize a field that is allowed to contain HTML.
     * If HTMLPurifier is available and enabled, use it; otherwise fall back to strip_tags.
     */
    protected function sanitizeHtmlField(string $value, bool $usePurifier): string
    {
        // remove null bytes
        $value = str_replace("\0", '', $value);

        // remove script/style blocks as a safety pre-step
        $value = preg_replace('#<script[^>]*?>.*?</script>#is', '', $value);
        $value = preg_replace('#<style[^>]*?>.*?</style>#is', '', $value);

        if ($usePurifier) {
            // instantiate HTMLPurifier with default config
            $config = \HTMLPurifier_Config::createDefault();
            $purifier = new \HTMLPurifier($config);
            $clean = $purifier->purify($value);
            return trim($clean);
        }

        // fallback: allow a safe subset of tags by using strip_tags with list
        // basic tags often used in content
        $allowed = '<p><br><a><strong><em><ul><ol><li><h1><h2><h3><blockquote><img><figure><figcaption><span><div>';
        $clean = strip_tags($value, $allowed);

        // remove potentially dangerous attributes (on* attributes, javascript: URIs)
        $clean = preg_replace_callback('#<(\w+)([^>]*)>#', function ($m) {
            $tag = $m[1];
            $attrs = $m[2];
            // remove on* attributes and javascript: in href/src
            $attrs = preg_replace('/on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $attrs);
            $attrs = preg_replace('/(href|src)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', function ($mm) {
                $val = $mm[2];
                // strip quotes
                $valClean = trim($val, '\"\'');
                if (preg_match('/^\s*javascript:/i', $valClean)) {
                    return '';
                }
                return $mm[1] . '=' . $mm[2];
            }, $attrs);
            return "<".$tag.$attrs.">";
        }, $clean);

        return trim($clean);
    }
}
