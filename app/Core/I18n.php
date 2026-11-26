<?php
declare(strict_types=1);

namespace App\Core;

class I18n
{
    private static array $dict = [];
    private static string $lang = 'ru';

    public static function init(string $lang): void
    {
        self::$lang = in_array($lang, ['ru', 'en'], true) ? $lang : 'ru';
        $file = dirname(__DIR__, 2) . '/lang/' . self::$lang . '.php';
        if (file_exists($file)) {
            self::$dict = require $file;
        }
    }

    public static function t(string $key): string
    {
        return self::$dict[$key] ?? $key;
    }

    public static function currentLang(): string
    {
        return self::$lang;
    }
}