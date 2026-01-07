<?php
/**
 * RATE LIMITER MIDDLEWARE
 * Limit requests per IP address
 */

class RateLimiter
{
    private static $storage = [];
    private static $maxAttempts = 60; // Max requests
    private static $decayMinutes = 1; // Per 1 minute

    /**
     * Check rate limit
     * 
     * @param string $key Identifier (IP address)
     * @return void
     */
    public static function check($key = null)
    {
        if ($key === null) {
            $key = self::getClientIP();
        }
        
        $now = time();
        
        // Clean expired entries
        self::cleanExpired($now);
        
        // Check if key exists
        if (!isset(self::$storage[$key])) {
            self::$storage[$key] = [
                'count' => 0,
                'reset_at' => $now + (self::$decayMinutes * 60)
            ];
        }
        
        $data = self::$storage[$key];
        
        // Reset if expired
        if ($now > $data['reset_at']) {
            self::$storage[$key] = [
                'count' => 1,
                'reset_at' => $now + (self::$decayMinutes * 60)
            ];
            
            self::setHeaders(self::$maxAttempts - 1, $data['reset_at']);
            return;
        }
        
        // Check limit
        if ($data['count'] >= self::$maxAttempts) {
            $retryAfter = $data['reset_at'] - $now;
            
            http_response_code(429);
            header("Retry-After: $retryAfter");
            
            echo json_encode([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter . ' seconds'
            ], JSON_PRETTY_PRINT);
            
            exit;
        }
        
        // Increment counter
        self::$storage[$key]['count']++;
        
        // Set headers
        $remaining = self::$maxAttempts - self::$storage[$key]['count'];
        self::setHeaders($remaining, $data['reset_at']);
    }

    /**
     * Get client IP address
     */
    private static function getClientIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Check for proxy
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        return $ip;
    }

    /**
     * Set rate limit headers
     */
    private static function setHeaders($remaining, $resetAt)
    {
        header("X-RateLimit-Limit: " . self::$maxAttempts);
        header("X-RateLimit-Remaining: $remaining");
        header("X-RateLimit-Reset: $resetAt");
    }

    /**
     * Clean expired entries
     */
    private static function cleanExpired($now)
    {
        foreach (self::$storage as $key => $data) {
            if ($now > $data['reset_at']) {
                unset(self::$storage[$key]);
            }
        }
    }

    /**
     * Configure limits
     */
    public static function configure($maxAttempts, $decayMinutes)
    {
        self::$maxAttempts = $maxAttempts;
        self::$decayMinutes = $decayMinutes;
    }
}