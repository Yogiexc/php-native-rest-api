<?php
/**
 * FRONT CONTROLLER - Entry Point
 * Semua request masuk lewat file ini
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

// Autoload
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/Response.php';
require_once __DIR__ . '/helpers/Request.php';
require_once __DIR__ . '/middleware/JsonMiddleware.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/helpers/Paginator.php';
require_once __DIR__ . '/helpers/Validator.php';
require_once __DIR__ . '/middleware/RateLimiter.php';

// Global Error Handler
set_exception_handler(function($exception) {
    Response::error($exception->getMessage(), 500);
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Apply JSON Middleware
JsonMiddleware::handle();
RateLimiter::check();

// Get method dan URI
$method = Request::method();
$uri = Request::uri();

// Load routes
require_once __DIR__ . '/routes.php';

// 404 Not Found
Response::error('Route not found', 404);