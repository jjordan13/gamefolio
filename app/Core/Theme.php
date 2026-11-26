<?php
declare(strict_types=1);

namespace App\Core;

class Theme
{
    private const ALLOWED = ['light', 'dark'];
    private const DEFAULT = 'dark';

    public static function current(): string
    {
        $fromCookie = $_COOKIE['theme'] ?? null;
        if (in_array($fromCookie, self::ALLOWED, true)) {
            return $fromCookie;
        }
        return self::DEFAULT;
    }

    public static function set(string $theme): void
    {
        if (!in_array($theme, self::ALLOWED, true)) {
            return;
        }
        setcookie('theme', $theme, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => '/',
            'samesite' => 'Lax',
        ]);
        $_COOKIE['theme'] = $theme;
    }
}