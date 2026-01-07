<?php
/**
 * DATABASE CONFIGURATION
 */

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                // SQLite
                $dbPath = __DIR__ . '/../database.sqlite';
                self::$connection = new PDO('sqlite:' . $dbPath);
                
                // MySQL (uncomment jika pakai MySQL)
                // $host = '127.0.0.1';
                // $dbname = 'php_rest_api';
                // $username = 'root';
                // $password = '';
                // self::$connection = new PDO(
                //     "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                //     $username,
                //     $password
                // );
                
                self::$connection->setAttribute(
                    PDO::ATTR_ERRMODE, 
                    PDO::ERRMODE_EXCEPTION
                );
                
                self::$connection->setAttribute(
                    PDO::ATTR_DEFAULT_FETCH_MODE, 
                    PDO::FETCH_ASSOC
                );
                
                self::initializeDatabase();
                
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        
        return self::$connection;
    }

    private static function initializeDatabase()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        self::$connection->exec($sql);
    }
}