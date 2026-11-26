<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Theme;

class PreferencesController
{
    public function setTheme(Request $request, array $params): void
    {
        $theme = (string) $request->post('theme');
        Theme::set($theme);
        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function setLang(Request $request, array $params): void
    {
        $lang = (string) $request->post('lang');
        setcookie('lang', $lang, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => '/',
            'samesite' => 'Lax',
        ]);
        $_COOKIE['lang'] = $lang;
        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}