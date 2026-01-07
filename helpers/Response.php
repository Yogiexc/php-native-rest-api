<?php
/**
 * RESPONSE HELPER
 * Update dengan logging
 */

class Response
{
    public static function success($data = null, $code = 200)
    {
        http_response_code($code);
        
        $response = [
            'success' => true,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log response
        Logger::response($code, $response);
        
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error($message = 'An error occurred', $code = 400)
    {
        http_response_code($code);
        
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log error
        Logger::error($message, ['status_code' => $code]);
        
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function created($data = null)
    {
        self::success($data, 201);
    }

    public static function noContent()
    {
        http_response_code(204);
        Logger::response(204, 'No content');
        exit;
    }
}