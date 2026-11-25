<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Пожалуйста, войдите в систему для доступа к этой странице');
            Response::redirect('/login');
        }
    }
}