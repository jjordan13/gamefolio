<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
<nav>
    <a href="/">Главная</a> |
    <a href="/games/<?= $game['id'] ?>">К странице игры</a>
</nav>

<h1>Редактирование игры</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/games/<?= $game['id'] ?>/edit" enctype="multipart/form-data">
    <h3>Название игры *</h3>
    <input type="text" name="title" value="<?= htmlspecialchars($game['title']) ?>" required maxlength="255">

    <h3>Краткое описание</h3>
    <textarea name="short_description" rows="3" cols="50" maxlength="500"><?= htmlspecialchars($game['short_description'] ?? '') ?></textarea>

    <h3>Полное описание</h3>
    <textarea name="full_description" rows="10" cols="80"><?= htmlspecialchars($game['full_description'] ?? '') ?></textarea>

    <h3>Движок</h3>
    <input type="text" name="engine" value="<?= htmlspecialchars($game['engine'] ?? '') ?>" maxlength="100">

    <h3>Жанр</h3>
    <input type="text" name="genre" value="<?= htmlspecialchars($game['genre'] ?? '') ?>" maxlength="100">

    <h3>Медиагалерея</h3>
    <?php if (empty($game['media'])): ?>
        <p>Медиафайлов пока нет.</p>
    <?php else: ?>
        <?php foreach ($game['media'] as $media): ?>
            <div>
                <img src="<?= htmlspecialchars($media['url']) ?>" alt="" width="160">
                <label>
                    <input type="checkbox" name="delete_media[]" value="<?= $media['id'] ?>">
                    Удалить
                </label>
                <?php if ($media['is_cover']): ?>
                    <span>(обложка)</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h3>Добавить медиафайлы</h3>
    <input type="file" name="media[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>

    <br><br>
    <button type="submit">Сохранить изменения</button>
    <a href="/games/<?= $game['id'] ?>">Отмена</a>
</form>
</body>
</html>