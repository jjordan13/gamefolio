<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Repositories\UserRepository;

class AuthController
{
    private View $view;
    private UserRepository $userRepo;

    public function __construct(View $view)
    {
        $this->view = $view;
        $this->userRepo = new UserRepository();
    }

    public function registerForm(Request $request, array $params): void
    {
        $this->view->render('pages/register', [
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
        ]);
    }

    public function register(Request $request, array $params): void
    {
        $login = trim((string) $request->post('login'));
        $nickname = trim((string) $request->post('nickname'));
        $password = (string) $request->post('password');
        $passwordConfirm = (string) $request->post('password_confirm');

        if (strlen($login) < 3 || strlen($login) > 50) {
            Session::flash('error', 'Логин должен быть от 3 до 50 символов');
            Response::redirect('/register');
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
            Session::flash('error', 'Логин может содержать только латинские буквы, цифры и _');
            Response::redirect('/register');
            return;
        }

        if (strlen($nickname) < 2 || strlen($nickname) > 100) {
            Session::flash('error', 'Никнейм должен быть от 2 до 100 символов');
            Response::redirect('/register');
            return;
        }

        if (strlen($password) < 6) {
            Session::flash('error', 'Пароль должен быть не менее 6 символов');
            Response::redirect('/register');
            return;
        }

        if ($password !== $passwordConfirm) {
            Session::flash('error', 'Пароли не совпадают');
            Response::redirect('/register');
            return;
        }

        if ($this->userRepo->existsByLogin($login)) {
            Session::flash('error', 'Пользователь с таким логином уже существует');
            Response::redirect('/register');
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userRepo->save($login, $passwordHash, $nickname);

        Session::flash('success', 'Регистрация прошла успешно! Теперь вы можете войти.');
        Response::redirect('/login');
    }

    public function loginForm(Request $request, array $params): void
    {
        $this->view->render('pages/login', [
            'error' => Session::flash('error'),
            'success' => Session::flash('success'),
        ]);
    }

    public function login(Request $request, array $params): void
    {
        $login = trim((string) $request->post('login'));
        $password = (string) $request->post('password');
        $remember = $request->post('remember') === '1';

        if (empty($login) || empty($password)) {
            Session::flash('error', 'Заполните все поля');
            Response::redirect('/login');
            return;
        }

        $user = $this->userRepo->findByLogin($login);

        if ($user === null) {
            Session::flash('error', 'Пользователь не найден');
            Response::redirect('/login');
            return;
        }

        if (!password_verify($password, $user->password_hash)) {
            Session::flash('error', 'Неверный пароль');
            Response::redirect('/login');
            return;
        }

        Session::regenerate();
        Session::set('user_id', $user->id);
        Session::set('user_nickname', $user->nickname);

        if ($remember) {
            $lifetime = 60 * 60 * 24 * 30;
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    session_id(),
                    time() + $lifetime,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
        }

        Response::redirect('/');
    }

    public function logout(Request $request, array $params): void
    {
        Session::destroy();
        Response::redirect('/');
    }
}