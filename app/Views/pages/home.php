<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <header>
        <h1>GameFolio</h1>
        <nav>
            <a href="/">Главная</a>
            <a href="/games">Все игры</a>
            <a href="/profiles">Все профили</a>
            <?php if (\App\Core\Session::isLoggedIn()): ?>
                <span>Привет, <?= htmlspecialchars($_SESSION['user_nickname'] ?? 'пользователь') ?>!</span> |
                <a href="/logout">Выйти</a>
            <?php else: ?>
                <a href="/register">Регистрация</a> |
                <a href="/login">Войти</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Не просто сайт для портфолио!</h2>
        <h3>Здесь живут идеи и люди, которые их рождают!</h3>
        <p>Возможности платформы:</p>
        <ul>
            <?php foreach ($features as $feature): ?>
                <li><?= htmlspecialchars($feature) ?></li>
            <?php endforeach; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2026 GameFolio. Прав у нас нет.</p>
    </footer>
</body>
</html>