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
        $view = new View();
        $view->renderWithLayout('pages/errors/404', ['pageTitle' => '404 — Страница не найдена']);
        exit;
    }

    public static function forbidden(): void
    {
        http_response_code(403);
        $view = new View();
        $view->renderWithLayout('pages/errors/403', ['pageTitle' => '403 — Доступ запрещён']);
        exit;
    }

    public static function serverError(): void
    {
        http_response_code(500);
        $view = new View();
        $view->renderWithLayout('pages/errors/500', ['pageTitle' => '500 — Внутренняя ошибка']);
        exit;
    }
}