<?php

namespace TalkBox\Database;

use PDO;
use MongoDB\Client as MongoClient;

class Database
{
    private static $connection = null;

    /**
     * Get a database connection based on config
     */
    public static function getConnection()
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        // Load config
        $config = require __DIR__ . '/../../config/database.php';
        $driver = $config['driver'] ?? 'mysql';

        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                self::$connection = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                break;

            case 'pgsql':
            case 'postgresql':
                self::$connection = new PDO(
                    "pgsql:host={$config['host']};dbname={$config['dbname']}",
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                break;

            case 'sqlite':
            case 'sqlite3':
                self::$connection = new PDO(
                    "sqlite:{$config['path']}",
                    null,
                    null,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                break;

            case 'mongodb':
                $uri = "mongodb://{$config['host']}:{$config['port']}";
                $client = new MongoClient($uri);
                self::$connection = $client->selectDatabase($config['dbname']);
                break;

            default:
                throw new \Exception("Unsupported database driver: {$driver}");
        }

        return self::$connection;
    }
}
