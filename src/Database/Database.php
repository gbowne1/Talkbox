<?php

namespace TalkBox\Database;

use PDO;
use Exception;

class Database
{
    private static $connection = null;

    /**
     * Get a MySQL database connection
     */
    public static function getConnection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $config = require __DIR__ . '/../../config/database.php';

        try {
            self::$connection = new PDO(
                "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (Exception $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }

        return self::$connection;
    }
}
