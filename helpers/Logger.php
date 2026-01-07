<?php
/**
 * LOGGING SYSTEM
 * Daily log rotation
 */

class Logger
{
    private static $logDir = __DIR__ . '/../logs';
    private static $dateFormat = 'Y-m-d H:i:s';

    /**
     * Log info message
     */
    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log error message
     */
    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log warning message
     */
    public static function warning($message, $context = [])
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log debug message
     */
    public static function debug($message, $context = [])
    {
        self::log('DEBUG', $message, $context);
    }

    /**
     * Log HTTP request
     */
    public static function request()
    {
        $data = [
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REQUEST_URI'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        self::log('REQUEST', 'Incoming request', $data);
    }

    /**
     * Log HTTP response
     */
    public static function response($statusCode, $data)
    {
        $context = [
            'status_code' => $statusCode,
            'response' => is_array($data) ? json_encode($data) : $data
        ];
        
        self::log('RESPONSE', 'Outgoing response', $context);
    }

    /**
     * Main log method
     */
    private static function log($level, $message, $context = [])
    {
        // Create logs directory if not exists
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
        
        // Format log entry
        $timestamp = date(self::$dateFormat);
        $contextStr = !empty($context) ? json_encode($context) : '';
        
        $logEntry = sprintf(
            "[%s] [%s] %s %s\n",
            $timestamp,
            $level,
            $message,
            $contextStr
        );
        
        // Determine log file (daily rotation)
        $logFile = self::$logDir . '/' . date('Y-m-d') . '.log';
        
        // Write to file
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }

    /**
     * Get logs by date
     */
    public static function getLogs($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        $logFile = self::$logDir . '/' . $date . '.log';
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $logs = file($logFile, FILE_IGNORE_NEW_LINES);
        return $logs;
    }

    /**
     * Clear old logs (older than X days)
     */
    public static function clearOldLogs($days = 7)
    {
        $files = glob(self::$logDir . '/*.log');
        $now = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * $days) {
                    unlink($file);
                }
            }
        }
    }
}