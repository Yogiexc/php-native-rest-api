<?php
/**
 * REQUEST HELPER
 */

class Request
{
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function uri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/');
        return $uri ?: '/';
    }

    public static function body()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return $data ?: [];
    }

    public static function input($key, $default = null)
    {
        $body = self::body();
        return $body[$key] ?? $default;
    }

    public static function query($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public static function isJson()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        return strpos($contentType, 'application/json') !== false;
    }
}