<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Config;
use App\Core\I18n;
use App\Core\Request;
use App\Core\Router;
use App\Core\Session;
use App\Core\Theme;
use App\Core\View;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\GameController;
use App\Controllers\MainController;
use App\Controllers\PreferencesController;
use App\Controllers\ProfileController;
use App\Controllers\ProfileEditController;
use App\Controllers\SettingsController;
use App\Middleware\AuthMiddleware;

Config::getInstance();
Session::start();

$lang = $_COOKIE['lang'] ?? 'ru';
I18n::init($lang);

$router = new Router();
$view = new View();
$authMiddleware = new AuthMiddleware();

// Публичные роуты
$router->get('/', [MainController::class, 'index']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/games', [GameController::class, 'index']);
$router->get('/games/{id}', [GameController::class, 'show']);
$router->get('/profiles', [ProfileController::class, 'index']);
$router->get('/profiles/{id}', [ProfileController::class, 'show']);

// Предпочтения (тема/язык)
$router->post('/preferences/theme', [PreferencesController::class, 'setTheme']);
$router->post('/preferences/lang', [PreferencesController::class, 'setLang']);

// Защищённые роуты
$router->get('/dashboard', [DashboardController::class, 'index'], [$authMiddleware]);
$router->get('/profile/edit', [ProfileEditController::class, 'editForm'], [$authMiddleware]);
$router->post('/profile/edit', [ProfileEditController::class, 'update'], [$authMiddleware]);
$router->get('/games/create', [GameController::class, 'createForm'], [$authMiddleware]);
$router->post('/games/create', [GameController::class, 'create'], [$authMiddleware]);
$router->get('/games/{id}/edit', [GameController::class, 'editForm'], [$authMiddleware]);
$router->post('/games/{id}/edit', [GameController::class, 'update'], [$authMiddleware]);
$router->post('/games/{id}/delete', [GameController::class, 'delete'], [$authMiddleware]);
$router->post('/games/{id}/like', [GameController::class, 'toggleLike'], [$authMiddleware]);
$router->post('/games/{id}/comment', [GameController::class, 'addComment'], [$authMiddleware]);
$router->post('/comments/{comment_id}/delete', [GameController::class, 'deleteComment'], [$authMiddleware]);
$router->get('/settings', [SettingsController::class, 'index'], [$authMiddleware]);
$router->post('/settings/login', [SettingsController::class, 'updateLogin'], [$authMiddleware]);
$router->post('/settings/password', [SettingsController::class, 'updatePassword'], [$authMiddleware]);
$router->post('/settings/delete', [SettingsController::class, 'deleteAccount'], [$authMiddleware]);

$router->dispatch(new Request(), $view);