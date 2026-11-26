<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\Repositories\GameRepository;
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
        $query = trim((string) $request->get('q'));
        if ($query !== '') {
            $users = $this->userRepo->search($query);
        } else {
            $users = $this->userRepo->findAll();
        }

        $this->view->renderWithLayout('pages/profiles', [
            'pageTitle' => 'Все профили — GameFolio',
            'users' => $users,
            'currentQuery' => $query,
        ]);
    }

    public function show(Request $request, array $params): void
    {
        $id = (int) $params['id'];
        $user = $this->userRepo->findById($id);

        if (!$user) {
            echo "Пользователь не найден. <a href='/profiles'>Назад к списку</a>";
            return;
        }

        $gameRepo = new GameRepository();
        $games = $gameRepo->findByAuthor($id);

        $this->view->renderWithLayout('pages/profile_details', [
            'user' => $user,
            'games' => $games,
            'pageTitle' => htmlspecialchars($user->nickname) . ' — GameFolio'
        ]);
    }
}