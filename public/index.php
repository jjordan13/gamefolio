<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Config;
use App\Core\I18n;
use App\Core\Request;
use App\Core\Response;
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

// Инициализация ядра
Config::getInstance();
Session::start();

// Тема и язык из cookie
$lang = $_COOKIE['lang'] ?? 'ru';
I18n::init($lang);

$router = new Router();
$view = new View();
$authMiddleware = new AuthMiddleware();

// ===== ПУБЛИЧНЫЕ РОУТЫ =====

// Главная
$router->get('/', [MainController::class, 'index']);

// Аутентификация
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Игры — конкретные пути ДО /games/{id}
$router->get('/games', [GameController::class, 'index']);
$router->get('/games/create', [GameController::class, 'createForm'], [$authMiddleware]);
$router->post('/games/create', [GameController::class, 'create'], [$authMiddleware]);

// Игры — параметрические
$router->get('/games/{id}', [GameController::class, 'show']);
$router->get('/games/{id}/edit', [GameController::class, 'editForm'], [$authMiddleware]);
$router->post('/games/{id}/edit', [GameController::class, 'update'], [$authMiddleware]);
$router->post('/games/{id}/delete', [GameController::class, 'delete'], [$authMiddleware]);
$router->post('/games/{id}/like', [GameController::class, 'toggleLike'], [$authMiddleware]);
$router->post('/games/{id}/comment', [GameController::class, 'addComment'], [$authMiddleware]);

// Профили — конкретные пути ДО /profiles/{id}
$router->get('/profiles', [ProfileController::class, 'index']);
$router->get('/profile/edit', [ProfileEditController::class, 'editForm'], [$authMiddleware]);
$router->post('/profile/edit', [ProfileEditController::class, 'update'], [$authMiddleware]);

// Профили — параметрические
$router->get('/profiles/{id}', [ProfileController::class, 'show']);

// Комментарии (удаление)
$router->post('/comments/{comment_id}/delete', [GameController::class, 'deleteComment'], [$authMiddleware]);

// Предпочтения (тема/язык) — публичные
$router->post('/preferences/theme', [PreferencesController::class, 'setTheme']);
$router->post('/preferences/lang', [PreferencesController::class, 'setLang']);

// ===== ЗАЩИЩЁННЫЕ РОУТЫ =====

$router->get('/dashboard', [DashboardController::class, 'index'], [$authMiddleware]);
$router->get('/settings', [SettingsController::class, 'index'], [$authMiddleware]);
$router->post('/settings/login', [SettingsController::class, 'updateLogin'], [$authMiddleware]);
$router->post('/settings/password', [SettingsController::class, 'updatePassword'], [$authMiddleware]);
$router->post('/settings/delete', [SettingsController::class, 'deleteAccount'], [$authMiddleware]);

// ===== ОБРАБОТКА ЗАПРОСА С TRY-CATCH =====

try {
    $router->dispatch(new Request(), $view);
} catch (\Throwable $e) {
    if (Config::getInstance()->get('APP_DEBUG') === 'true') {
        // Режим разработки — показываем подробную ошибку
        http_response_code(500);
        echo '<pre style="background: #000; color: #fff; padding: 20px; font-family: monospace;">';
        echo '<h2 style="color: #ff6b6b;">Ошибка</h2>';
        echo 'Message: ' . htmlspecialchars($e->getMessage()) . "\n";
        echo 'File: ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . "\n";
        echo 'Trace: ' . htmlspecialchars($e->getTraceAsString());
        echo '</pre>';
    } else {
        // Продакшен — красивая страница 500
        Response::serverError();
    }
}