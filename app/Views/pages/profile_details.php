<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        /* Минимальные стили для структуры профиля */
        .profile-container { max-width: 940px; margin: 0 auto; font-family: sans-serif; color: #c7d5e0; background: #1b2838; padding: 20px; }
        .profile-banner { width: 100%; height: 250px; background-color: #2a475e; background-size: cover; background-position: center; border-radius: 4px; }
        .profile-info { display: flex; gap: 20px; margin-top: -60px; padding: 0 20px; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #1b2838; background-color: #171a21; background-size: cover; background-position: center; }
        .profile-details { flex: 1; padding-top: 70px; }
        .profile-details h1 { margin: 0 0 5px 0; color: #fff; }
        .profile-details .login { color: #8f98a0; font-size: 14px; margin-bottom: 15px; }
        .profile-details .bio { background: #171a21; padding: 15px; border-radius: 4px; margin-top: 15px; }
        .games-section { margin-top: 40px; }
        .game-item { background: #171a21; padding: 15px; margin-bottom: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
        .game-item a { color: #66c0f4; text-decoration: none; }
    </style>
</head>
<body>
<div class="profile-container">
    <nav>
        <a href="/">Главная</a> |
        <a href="/profiles">Все профили</a>
        <?php if (\App\Core\Session::userId() === $user->id): ?>
            | <a href="/profile/edit" style="color: #66c0f4;">Редактировать профиль</a>
        <?php endif; ?>
        <?php if (\App\Core\Session::isLoggedIn()): ?>
            | <a href="/settings">Настройки</a>
        <?php endif; ?>
    </nav>

    <!-- Баннер -->
    <?php
    $banner = !empty($user->banner_url) ? $user->banner_url : 'https://via.placeholder.com/940x250/2a475e/ffffff?text=No+Banner';
    ?>
    <div class="profile-banner" style="background-image: url('<?= htmlspecialchars($banner) ?>');"></div>

    <!-- Инфо-блок -->
    <div class="profile-info">
        <?php
        $avatar = !empty($user->avatar_url) ? $user->avatar_url : 'https://via.placeholder.com/120/171a21/ffffff?text=Avatar';
        ?>
        <div class="profile-avatar" style="background-image: url('<?= htmlspecialchars($avatar) ?>');"></div>

        <div class="profile-details">
            <h1><?= htmlspecialchars($user->nickname) ?></h1>
            <div class="login">@<?= htmlspecialchars($user->login) ?></div>

            <?php if (!empty($user->bio)): ?>
                <div class="bio">
                    <?= nl2br(htmlspecialchars($user->bio)) ?>
                </div>
            <?php endif; ?>

            <?php
            // Соцсети хранятся как JSON строка
            $socials = json_decode($user->social_links ?? '{}', true);
            if (!empty($socials)):
                ?>
                <div style="margin-top: 15px;">
                    <strong>Соцсети:</strong>
                    <ul>
                        <?php foreach ($socials as $name => $link): ?>
                            <li><a href="<?= htmlspecialchars($link) ?>" target="_blank"><?= htmlspecialchars($name) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Список игр автора -->
    <div class="games-section">
        <h2>Проекты разработчика (<?= count($games) ?>)</h2>
        <?php if (\App\Core\Session::userId() === $user->id): ?>
            <div class="game-item">
                <a href="/games/create">+ Добавить игру</a>
            </div>
        <?php endif; ?>

        <?php if (empty($games)): ?>
            <p>У этого разработчика пока нет опубликованных игр.</p>
        <?php else: ?>
            <?php foreach ($games as $game): ?>
                <div class="game-item">
                    <div>
                        <strong><a href="/games/<?= $game->id ?>"><?= htmlspecialchars($game->title) ?></a></strong>
                        <br>
                        <small style="color: #8f98a0;"><?= htmlspecialchars($game->engine ?? 'Движок не указан') ?> • <?= htmlspecialchars($game->genre ?? 'Жанр не указан') ?></small>
                    </div>
                    <div>
                        <em style="color: #c7d5e0;"><?= htmlspecialchars(mb_substr($game->short_description ?? '', 0, 100)) ?>...</em>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>