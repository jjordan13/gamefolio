<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    public function getUri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function allPost(): array
    {
        return $_POST;
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }
}