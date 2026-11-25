<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Config;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Session;
use App\Core\View;
use App\Controllers\MainController;
use App\Models\Repositories\GameRepository;
use App\Models\Repositories\UserRepository;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Middleware\AuthMiddleware;
use App\Controllers\GameController;
use App\Controllers\ProfileController;
use App\Controllers\ProfileEditController;
use App\Controllers\SettingsController;


Config::getInstance();
Session::start();

$router = new Router();
$view = new View();
$authMiddleware = new AuthMiddleware();

$router->get('/', [MainController::class, 'index']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Этот роут защищён middleware. Неавторизованных пользователей он редиректнет на /login
$router->get('/dashboard', [DashboardController::class, 'index'], [$authMiddleware]);
$router->get('/profile/edit', [ProfileEditController::class, 'editForm'], [$authMiddleware]);
$router->post('/profile/edit', [ProfileEditController::class, 'update'], [$authMiddleware]);

$router->get('/games', [GameController::class, 'index']);
// CRUD игр (только для авторизованных)
$router->get('/games/create', [GameController::class, 'createForm'], [$authMiddleware]);
$router->post('/games/create', [GameController::class, 'create'], [$authMiddleware]);
$router->get('/games/{id}/edit', [GameController::class, 'editForm'], [$authMiddleware]);
$router->post('/games/{id}/edit', [GameController::class, 'update'], [$authMiddleware]);
$router->post('/games/{id}/delete', [GameController::class, 'delete'], [$authMiddleware]);

$router->get('/profiles', [ProfileController::class, 'index']);
$router->get('/settings', [SettingsController::class, 'index'], [$authMiddleware]);
$router->post('/settings/login', [SettingsController::class, 'updateLogin'], [$authMiddleware]);
$router->post('/settings/password', [SettingsController::class, 'updatePassword'], [$authMiddleware]);
$router->post('/settings/delete', [SettingsController::class, 'deleteAccount'], [$authMiddleware]);

$router->get('/games/{id}', [GameController::class, 'show']);
$router->get('/profiles/{id}', [ProfileController::class, 'show']);

// Лайки и комментарии (публичный просмотр уже в /games/{id})
$router->post('/games/{id}/like', [GameController::class, 'toggleLike'], [$authMiddleware]);
$router->post('/games/{id}/comment', [GameController::class, 'addComment'], [$authMiddleware]);
$router->post('/comments/{comment_id}/delete', [GameController::class, 'deleteComment'], [$authMiddleware]);



$router->dispatch(new Request(), $view);