<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Repositories\UserRepository;

class SettingsController
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
        $userId = Session::userId();
        $user = $this->userRepo->findById($userId);

        $this->view->render('pages/settings', [
            'user' => $user,
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
            'title' => 'Настройки аккаунта — GameFolio'
        ]);
    }

    public function updateLogin(Request $request, array $params): void
    {
        $userId = Session::userId();
        $newLogin = trim((string) $request->post('login'));

        if (strlen($newLogin) < 3 || strlen($newLogin) > 50) {
            Session::flash('error', 'Логин должен быть от 3 до 50 символов');
            Response::redirect('/settings');
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $newLogin)) {
            Session::flash('error', 'Логин может содержать только латинские буквы, цифры и _');
            Response::redirect('/settings');
            return;
        }

        if ($this->userRepo->existsByLoginExcept($newLogin, $userId)) {
            Session::flash('error', 'Этот логин уже занят');
            Response::redirect('/settings');
            return;
        }

        $this->userRepo->updateLogin($userId, $newLogin);
        Session::flash('success', 'Логин успешно изменён');
        Response::redirect('/settings');
    }

    public function updatePassword(Request $request, array $params): void
    {
        $userId = Session::userId();
        $oldPassword = (string) $request->post('old_password');
        $newPassword = (string) $request->post('new_password');
        $confirmPassword = (string) $request->post('confirm_password');

        $user = $this->userRepo->findById($userId);

        if (!password_verify($oldPassword, $user->password_hash)) {
            Session::flash('error', 'Неверный текущий пароль');
            Response::redirect('/settings');
            return;
        }

        if (strlen($newPassword) < 6) {
            Session::flash('error', 'Новый пароль должен быть не менее 6 символов');
            Response::redirect('/settings');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'Пароли не совпадают');
            Response::redirect('/settings');
            return;
        }

        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userRepo->updatePassword($userId, $passwordHash);

        Session::flash('success', 'Пароль успешно изменён');
        Response::redirect('/settings');
    }

    public function deleteAccount(Request $request, array $params): void
    {
        $userId = Session::userId();
        $password = (string) $request->post('password');

        $user = $this->userRepo->findById($userId);

        if (!password_verify($password, $user->password_hash)) {
            Session::flash('error', 'Неверный пароль');
            Response::redirect('/settings');
            return;
        }

        // Удаляем аккаунт (каскадно удалятся игры, комментарии, лайки)
        $this->userRepo->deleteAccount($userId);

        Session::destroy();
        Session::flash('success', 'Аккаунт удалён');
        Response::redirect('/');
    }
}