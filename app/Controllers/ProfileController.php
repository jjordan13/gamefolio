<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\Repositories\UserRepository;

class ProfileController
{
    private View $view;
    private UserRepository $userRepo;

    public function __construct(View $view)
    {
        $this->view = $view;
        $this->userRepo = new UserRepository();
    }

    public function index(Request $request, array $params): void
    {
        $users = $this->userRepo->findAll();
        $this->view->render('pages/profiles', [
            'users' => $users,
            'title' => 'Все профили — GameFolio'
        ]);
    }

    // Страница одного профиля (GET /profiles/{id})
    public function show(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $user = $this->userRepo->findById($id);

        if (!$user) {
            echo "Пользователь не найден. <a href='/profiles'>Назад к списку</a>";
            return;
        }

        // Получаем игры этого автора
        $gameRepo = new \App\Models\Repositories\GameRepository();
        $games = $gameRepo->findByAuthor($id);

        $this->view->render('pages/profile_details', [
            'user' => $user,
            'games' => $games,
            'title' => htmlspecialchars($user->nickname) . ' — GameFolio'
        ]);
    }
}