<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;

class MainController
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, array $params): void
    {
        $this->view->render('pages/home', [
            'title' => 'GameFolio - Главная',
            'features' => [
                'Портфолио разработчиков игр',
                'Карточки проектов с медиагалереей',
                'Теги движков и жанров',
                'Поиск и фильтрация',
            ],
        ]);
    }
}