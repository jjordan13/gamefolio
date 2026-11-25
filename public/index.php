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

Config::getInstance();
Session::start();

$router = new Router();
$view = new View();

$router->get('/', [MainController::class, 'index']);

// ВРЕМЕННЫЙ ТЕСТОВЫЙ РОУТ
//$router->get('/debug', function() {
//    $gameRepo = new GameRepository();
//    $games = $gameRepo->findAll();
//
//    echo "<pre>";
//    print_r($games);
//    echo "</pre>";
//    exit;
//});

$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$authMiddleware = new AuthMiddleware();

// Этот роут защищён middleware. Неавторизованных пользователей он редиректнет на /login
$router->get('/dashboard', [DashboardController::class, 'index'], [$authMiddleware]);

$router->dispatch(new Request(), $view);