<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $pattern, mixed $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function get(string $pattern, mixed $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, mixed $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    public function dispatch(Request $request, View $view): void
    {
        $uri = $request->getUri();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['pattern'], $uri);
            if ($params !== null) {
                $handler = $route['handler'];

                // Если handler — массив [Класс, метод], создаём контроллер
                if (is_array($handler)) {
                    [$controllerClass, $action] = $handler;
                    $controller = new $controllerClass($view);
                    $controller->$action($request, $params);
                }
                // Если handler — Closure или обычная функция
                else {
                    $handler($request, $params);
                }
                return;
            }
        }

        Response::notFound();
    }

    private function match(string $pattern, string $uri): ?array
    {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return $params;
        }

        return null;
    }
}