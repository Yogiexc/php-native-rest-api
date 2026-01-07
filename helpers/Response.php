<?php
/**
 * RESPONSE HELPER
 */

class Response
{
    public static function success($data = null, $code = 200)
    {
        http_response_code($code);
        
        echo json_encode([
            'success' => true,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        exit;
    }

    public static function error($message = 'An error occurred', $code = 400)
    {
        http_response_code($code);
        
        echo json_encode([
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        exit;
    }

    public static function created($data = null)
    {
        self::success($data, 201);
    }

    public static function noContent()
    {
        http_response_code(204);
        exit;
    }
}