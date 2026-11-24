<?php
declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;

class Config
{
    private static ?self $instance = null;
    private array $values = [];

    private function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
        $this->values = $_ENV;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->values[$key] ?? $default;
    }
}