<?php
declare(strict_types=1);

namespace App\Core;

class Response
{
    public static function redirect(string $url, int $code = 302): void
    {
        http_response_code($code);
        header('Location: ' . $url);
        exit;
    }

    public static function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function notFound(): void
    {
        http_response_code(404);
        echo '404 — Страница не найдена';
        exit;
    }
}