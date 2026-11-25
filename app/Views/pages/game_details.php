<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        /* Минимальные стили для имитации Steam, чтобы было понятно, где что */
        .steam-container { max-width: 940px; margin: 0 auto; font-family: sans-serif; color: #c7d5e0; background: #1b2838; padding: 20px; }
        .steam-header { border-bottom: 1px solid #2a475e; padding-bottom: 10px; margin-bottom: 20px; }
        .steam-main { display: flex; gap: 20px; }
        .steam-gallery { flex: 2; background: #000; height: 350px; display: flex; align-items: center; justify-content: center; }
        .steam-gallery img { max-width: 100%; max-height: 100%; }
        .steam-info { flex: 1; background: #171a21; padding: 15px; }
        .steam-desc { margin-top: 20px; background: #171a21; padding: 15px; }
        .tag { display: inline-block; background: #2a475e; padding: 2px 8px; margin: 2px; border-radius: 2px; font-size: 12px; }
        .media-list img { max-width: 100px; margin-right: 5px; }
    </style>
</head>
<body>
<div class="steam-container">
    <nav>
        <a href="/">Главная</a> | <a href="/games">Все игры</a>
    </nav>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="steam-header">
        <h1><?= htmlspecialchars($game['title']) ?></h1>
        <p>Разработчик: <strong><?= htmlspecialchars($game['author_nickname']) ?></strong></p>
    </div>

    <div class="steam-main">
        <!-- Левая часть: Галерея -->
        <div class="steam-gallery">
            <?php
            // Берем первое медиа как обложку/главное фото
            $cover = !empty($game['media']) ? $game['media'][0]['url'] : 'https://via.placeholder.com/600x350?text=No+Image';
            ?>
            <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
        </div>

        <!-- Правая часть: Карточка с инфой -->
        <div class="steam-info">
            <h3>Об игре</h3>
            <?php if (\App\Core\Session::userId() === (int) $game['author_id']): ?>
                <div>
                    <a href="/games/<?= $game['id'] ?>/edit">Редактировать игру</a>

                    <form method="POST" action="/games/<?= $game['id'] ?>/delete"
                          onsubmit="return confirm('Удалить игру безвозвратно?');">
                        <button type="submit">Удалить игру</button>
                    </form>
                </div>
            <?php endif; ?>
            <p><?= htmlspecialchars($game['short_description'] ?? 'Описание отсутствует') ?></p>

            <p><strong>Движок:</strong> <?= htmlspecialchars($game['engine'] ?? 'Не указан') ?></p>
            <p><strong>Жанр:</strong> <?= htmlspecialchars($game['genre'] ?? 'Не указан') ?></p>

            <h4>Теги:</h4>
            <div>
                <?php foreach ($game['tags'] as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag['name']) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Нижняя часть: Подробное описание и остальные медиа -->
    <div class="steam-desc">
        <h2>Подробное описание</h2>
        <p><?= nl2br(htmlspecialchars($game['full_description'] ?? 'Подробное описание не добавлено.')) ?></p>

        <h3>Медиагалерея</h3>
        <div class="media-list">
            <?php foreach ($game['media'] as $media): ?>
                <img src="<?= htmlspecialchars($media['url']) ?>" alt="media">
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>