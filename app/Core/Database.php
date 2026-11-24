<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = Config::getInstance();
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $config->get('DB_HOST'),
                $config->get('DB_PORT'),
                $config->get('DB_NAME')
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config->get('DB_USER'),
                    $config->get('DB_PASS'),
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}