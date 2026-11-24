<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    private string $templatesPath;

    public function __construct()
    {
        $this->templatesPath = dirname(__DIR__) . '/Views/';
    }

    public function render(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $file = $this->templatesPath . $template . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: $template");
        }

        require $file;
    }
}