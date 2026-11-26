<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\I18n;
use App\Core\Request;
use App\Core\View;
use App\Models\Repositories\GameRepository;
use App\Models\Repositories\UserRepository;

class MainController
{
    private View $view;
    private GameRepository $gameRepo;
    private UserRepository $userRepo;

    public function __construct(View $view)
    {
        $this->view = $view;
        $this->gameRepo = new GameRepository();
        $this->userRepo = new UserRepository();
    }

    public function index(Request $request, array $params): void
    {
        // Получаем последние 6 игр и 6 профилей для главной страницы
        $recentGames = $this->gameRepo->findAllWithAuthor();
        $recentGames = array_slice($recentGames, 0, 6);

        $recentUsers = $this->userRepo->findAll();
        $recentUsers = array_slice($recentUsers, 0, 6);

        $this->view->renderWithLayout('pages/home', [
            'pageTitle' => I18n::t('site_name'),
            'features' => [
                I18n::t('feature_portfolio'),
                I18n::t('feature_cards'),
                I18n::t('feature_tags'),
                I18n::t('feature_search'),
            ],
            'recentGames' => $recentGames,
            'recentUsers' => $recentUsers,
        ]);
    }
}