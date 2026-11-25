<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
<nav>
    <a href="/">Главная</a> |
    <a href="/games">Все игры</a> |
    <a href="/profiles">Все профили</a>
    <?php if (\App\Core\Session::isLoggedIn()): ?>
        | <a href="/settings">Настройки</a>
    <?php endif; ?>
</nav>

<h1>Разработчики на GameFolio</h1>

<?php if (empty($users)): ?>
    <p>Пока никто не зарегистрировался.</p>
<?php else: ?>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <strong><?= htmlspecialchars($user->nickname) ?></strong>
                (логин: <?= htmlspecialchars($user->login) ?>)
                <br>
                <em><?= htmlspecialchars($user->bio ?? 'Нет описания') ?></em>
                <br>
                <a href="/profiles/<?= $user->id ?>">Открыть профиль</a>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>