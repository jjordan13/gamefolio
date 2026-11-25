<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Core\View;

class DashboardController
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, array $params): void
    {
        $this->view->render('pages/dashboard', [
            'nickname' => Session::get('user_nickname', 'Гость'),
        ]);
    }
}