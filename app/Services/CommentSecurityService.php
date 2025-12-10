<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentSecurityService
{
    // Anti-spam configuration
    const MAX_COMMENTS_PER_HOUR_PER_IP = 5;
    const MAX_COMMENTS_PER_HOUR_PER_EMAIL = 3;
    const MIN_TIME_BETWEEN_COMMENTS = 10; // seconds
    const SPAM_SCORE_THRESHOLD = 70; // 0-100, anything above is flagged as spam
    const SUSPICIOUS_SCORE_THRESHOLD = 40; // Flag as suspicious

    /**
     * Validate comment for spam and security issues
     */
    public function validateComment(Request $request, string $content): array
    {
        $spam_score = 0;
        $issues = [];

        // Get IP and user agent
        $ip_address = $this->getClientIp($request);
        $user_agent = $request->userAgent() ?? 'Unknown';

        // 1. Check rate limiting by IP
        if ($this->isRateLimitedByIp($ip_address)) {
            $spam_score += 30;
            $issues[] = 'Too many comments from your IP address. Please wait.';
        }

        // 2. Check rate limiting by email
        if ($this->isRateLimitedByEmail($request->input('author_email'))) {
            $spam_score += 25;
            $issues[] = 'Too many comments from this email. Please wait.';
        }

        // 3. Check for rapid successive comments
        if ($this->isTooRapidComment($ip_address)) {
            $spam_score += 20;
            $issues[] = 'Please wait a few seconds before posting another comment.';
        }

        // 4. Check for excessive links
        if ($this->hasExcessiveLinks($content)) {
            $spam_score += 35;
            $issues[] = 'Your comment contains too many external links.';
        }

        // 5. Check for spam keywords and patterns
        $spam_patterns = $this->detectSpamPatterns($content);
        if ($spam_patterns > 0) {
            $spam_score += $spam_patterns;
            $issues[] = 'Your comment contains suspicious content patterns.';
        }

        // 6. Check for HTML/Script injection
        if ($this->containsHtmlScript($content)) {
            $spam_score += 50;
            $issues[] = 'HTML or script content is not allowed.';
        }

        // 7. Check content quality
        if ($this->isPoorQualityContent($content)) {
            $spam_score += 15;
            $issues[] = 'Comment content quality is too low (repetitive characters, etc).';
        }

        // 8. Check for duplicate comments
        if ($this->isDuplicateComment($content)) {
            $spam_score += 40;
            $issues[] = 'This comment appears to be a duplicate.';
        }

        // 9. Check suspicious patterns in author name
        if ($this->hasSuspiciousAuthorName($request->input('author_name'))) {
            $spam_score += 10;
        }

        // Cap spam score at 100
        $spam_score = min($spam_score, 100);

        return [
            'spam_score' => $spam_score,
            'spam_status' => $this->getSpamStatus($spam_score),
            'is_allowed' => empty($issues) && $spam_score < self::SPAM_SCORE_THRESHOLD,
            'issues' => $issues,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
        ];
    }

    /**
     * Get client IP address (handles proxies, VPN, etc)
     */
    private function getClientIp(Request $request): string
    {
        // Check various headers untuk proxy/load balancer
        $headers = [
            'HTTP_CF_CONNECTING_IP',      // Cloudflare
            'HTTP_X_FORWARDED_FOR',       // Standard proxy header (ambil yang pertama)
            'HTTP_X_FORWARDED',           // Alternative
            'HTTP_FORWARDED_FOR',         // Non-standard
            'HTTP_FORWARDED',             // RFC 7239
            'HTTP_CLIENT_IP',             // Client IP from proxy
            'HTTP_X_CLUSTER_CLIENT_IP',   // OpenStack/Rackspace
        ];

        foreach ($headers as $header) {
            if (!empty($request->server($header))) {
                $ip = $request->server($header);
                // Jika multiple IPs (X-Forwarded-For), ambil yang pertama
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                // Validasi format IP dan pastikan bukan IP lokal/private
                if ($this->isValidPublicIp($ip)) {
                    return $ip;
                }
            }
        }

        // Fallback ke IP dari request jika tidak ada header proxy
        $ip = $request->ip();
        return $this->isValidPublicIp($ip) ? $ip : $ip;
    }

    /**
     * Validasi bahwa IP adalah IP publik yang valid (bukan lokal/private)
     */
    private function isValidPublicIp(string $ip): bool
    {
        // Filter private IP ranges
        $private_ips = [
            '127.0.0.0/8',      // Loopback
            '10.0.0.0/8',       // Private class A
            '172.16.0.0/12',    // Private class B
            '192.168.0.0/16',   // Private class C
            '169.254.0.0/16',   // Link-local
            '255.255.255.255',  // Broadcast
            '0.0.0.0',          // Invalid
        ];

        // Return true jika IP valid dan bukan private range
        return filter_var($ip, FILTER_VALIDATE_IP) && !$this->isPrivateIp($ip);
    }

    /**
     * Check apakah IP termasuk private range
     */
    private function isPrivateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    /**
     * Check if IP is rate limited
     */
    private function isRateLimitedByIp(string $ip): bool
    {
        $key = "comment_rate_limit_ip_{$ip}";
        $count = Cache::get($key, 0);
        return $count >= self::MAX_COMMENTS_PER_HOUR_PER_IP;
    }

    /**
     * Check if email is rate limited
     */
    private function isRateLimitedByEmail(string $email): bool
    {
        $key = "comment_rate_limit_email_{$email}";
        $count = Cache::get($key, 0);
        return $count >= self::MAX_COMMENTS_PER_HOUR_PER_EMAIL;
    }

    /**
     * Check if comment is posted too quickly
     */
    private function isTooRapidComment(string $ip): bool
    {
        $key = "last_comment_time_{$ip}";
        $lastTime = Cache::get($key);

        if ($lastTime === null) {
            return false;
        }

        return (time() - $lastTime) < self::MIN_TIME_BETWEEN_COMMENTS;
    }

    /**
     * Check for excessive external links
     */
    private function hasExcessiveLinks(string $content): bool
    {
        $linkCount = substr_count($content, 'http://') + substr_count($content, 'https://');
        return $linkCount > 2; // Allow max 2 links
    }

    /**
     * Detect spam patterns (keywords, repeated characters, etc)
     */
    private function detectSpamPatterns(string $content): int
    {
        $score = 0;

        // Check for spam keywords
        $spam_keywords = [
            'viagra', 'cialis', 'casino', 'poker', 'lottery',
            'click here', 'buy now', 'limited offer', 'act now',
            'bitcoin', 'cryptocurrency', 'forex', 'trading',
            'make money fast', 'earn money', 'get rich',
        ];

        $lower_content = strtolower($content);
        foreach ($spam_keywords as $keyword) {
            if (strpos($lower_content, $keyword) !== false) {
                $score += 15;
            }
        }

        // Check for repeated characters (aaaa, !!!!, etc)
        if (preg_match('/(.)\1{4,}/', $content)) {
            $score += 10;
        }

        // Check for excessive punctuation
        $punctuation_count = preg_match_all('/[!?*]{2,}/', $content);
        if ($punctuation_count > 2) {
            $score += 10;
        }

        return min($score, 40); // Cap at 40
    }

    /**
     * Check for HTML/Script injection
     */
    private function containsHtmlScript(string $content): bool
    {
        // Check for HTML tags
        if (preg_match('/<[^>]+>/', $content)) {
            return true;
        }

        // Check for common script patterns
        if (preg_match('/javascript:/i', $content)) {
            return true;
        }

        // Check for SQL injection patterns
        if (preg_match("/(union|select|insert|update|delete|drop|create|alter)/i", $content)) {
            return true;
        }

        return false;
    }

    /**
     * Check for poor quality content
     */
    private function isPoorQualityContent(string $content): bool
    {
        // Check if content is too short (already handled by validation)
        if (strlen($content) < 5) {
            return true;
        }

        // Check if content is mostly spaces/special chars
        $alpha_count = strlen(preg_replace('/[^a-zA-Z0-9]/', '', $content));
        if ($alpha_count / strlen($content) < 0.5) {
            return true;
        }

        // Check for repetitive content
        if (preg_match('/(.{5,})\1{2,}/', $content)) {
            return true;
        }

        return false;
    }

    /**
     * Check for duplicate comments
     */
    private function isDuplicateComment(string $content): bool
    {
        // Check if exact same comment exists in last 24 hours
        $exists = Comment::where('content', $content)
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        return $exists;
    }

    /**
     * Check for suspicious author name
     */
    private function hasSuspiciousAuthorName(string $name): bool
    {
        // Check for spam-like names
        if (strlen($name) < 2 || strlen($name) > 255) {
            return true;
        }

        // Check for excessive numbers
        $numbers = strlen(preg_replace('/[^0-9]/', '', $name));
        if ($numbers / strlen($name) > 0.7) {
            return true;
        }

        // Check for repeating characters
        if (preg_match('/(.)\1{3,}/', $name)) {
            return true;
        }

        return false;
    }

    /**
     * Get spam status based on score
     */
    private function getSpamStatus(int $score): string
    {
        if ($score >= self::SPAM_SCORE_THRESHOLD) {
            return 'spam';
        }
        if ($score >= self::SUSPICIOUS_SCORE_THRESHOLD) {
            return 'suspicious';
        }
        return 'clean';
    }

    /**
     * Update rate limiting cache
     */
    public function recordCommentSubmission(string $ip, string $email): void
    {
        // Record IP rate limit
        $ip_key = "comment_rate_limit_ip_{$ip}";
        $ip_count = Cache::get($ip_key, 0);
        Cache::put($ip_key, $ip_count + 1, now()->addHour());

        // Record email rate limit
        $email_key = "comment_rate_limit_email_{$email}";
        $email_count = Cache::get($email_key, 0);
        Cache::put($email_key, $email_count + 1, now()->addHour());

        // Record last comment time
        $time_key = "last_comment_time_{$ip}";
        Cache::put($time_key, time(), now()->addMinutes(5));
    }
}
