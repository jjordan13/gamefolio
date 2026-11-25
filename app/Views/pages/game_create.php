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
</nav>

<h1>Добавить новую игру</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/games/create" enctype="multipart/form-data">
    <h3>Название игры *</h3>
    <input type="text" name="title" required maxlength="255">

    <h3>Краткое описание</h3>
    <textarea name="short_description" rows="3" cols="50" maxlength="500"></textarea>

    <h3>Полное описание</h3>
    <textarea name="full_description" rows="10" cols="80"></textarea>

    <h3>Движок</h3>
    <input type="text" name="engine" maxlength="100" placeholder="Unity, Unreal Engine, Godot...">

    <h3>Жанр</h3>
    <input type="text" name="genre" maxlength="100" placeholder="Action, RPG, Strategy...">

    <h3>Медиафайлы (скриншоты, GIF)</h3>
    <input type="file" name="media[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
    <p><small>Можно выбрать несколько файлов. Первый файл станет обложкой.</small></p>

    <br><br>
    <button type="submit">Создать игру</button>
    <a href="/games">Отмена</a>
</form>
</body>
</html>