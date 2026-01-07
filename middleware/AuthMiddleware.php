<?php
/**
 * AUTHENTICATION MIDDLEWARE
 * Bearer Token Authentication
 */

class AuthMiddleware
{
    // Hardcoded tokens (untuk demo)
    // Untuk production, simpan di database dengan hashing
    private static $validTokens = [
        'token_user_123' => ['user_id' => 1, 'name' => 'Admin'],
        'token_user_456' => ['user_id' => 2, 'name' => 'User'],
    ];

    /**
     * Check authentication
     * 
     * @return array User data jika authenticated
     */
    public static function authenticate()
    {
        $token = self::getToken();
        
        if (!$token) {
            Response::error('Unauthorized. Token is required.', 401);
        }
        
        if (!isset(self::$validTokens[$token])) {
            Response::error('Unauthorized. Invalid token.', 401);
        }
        
        return self::$validTokens[$token];
    }

    /**
     * Get token from request header
     */
    private static function getToken()
    {
        $headers = getallheaders();
        
        // Check Authorization header
        if (isset($headers['Authorization'])) {
            // Format: Bearer token_user_123
            $auth = $headers['Authorization'];
            
            if (preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Generate token (untuk production gunakan JWT)
     */
    public static function generateToken($userId)
    {
        return hash('sha256', $userId . time() . random_bytes(16));
    }
}