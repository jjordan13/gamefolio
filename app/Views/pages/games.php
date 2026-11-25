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
    <a href="/profiles">Все профили</a> |
    <a href="/register">Регистрация</a> |
    <a href="/login">Войти</a>
</nav>

<h1>Каталог игр</h1>

<?php if (empty($games)): ?>
    <p>Игры пока не добавлены.</p>
<?php else: ?>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <strong><?= htmlspecialchars($game->title) ?></strong>
                (<?= htmlspecialchars($game->engine ?? 'Движок не указан') ?>)
                — Автор: <?= htmlspecialchars($game->author_nickname) ?>
                <br>
                <em><?= htmlspecialchars($game->short_description ?? 'Без описания') ?></em>
                <br>
                <a href="/games/<?= $game->id ?>">Подробнее</a>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>