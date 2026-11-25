<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
<nav>
    <a href="/">Главная</a> |
    <a href="/profiles/<?= $user->id ?>">Мой профиль</a> |
    <a href="/logout">Выйти</a>
</nav>

<h1>Редактирование профиля</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/profile/edit" enctype="multipart/form-data">
    <h3>Никнейм</h3>
    <input type="text" name="nickname" value="<?= htmlspecialchars($user->nickname) ?>" required maxlength="100">

    <h3>О себе</h3>
    <textarea name="bio" rows="5" cols="50"><?= htmlspecialchars($user->bio ?? '') ?></textarea>

    <h3>Аватар</h3>
    <?php if ($user->avatar_url): ?>
        <p>Текущий аватар:</p>
        <img src="<?= htmlspecialchars($user->avatar_url) ?>" alt="Avatar" style="max-width: 150px; border-radius: 50%;">
    <?php endif; ?>
    <br>
    <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp">

    <h3>Баннер</h3>
    <?php if ($user->banner_url): ?>
        <p>Текущий баннер:</p>
        <img src="<?= htmlspecialchars($user->banner_url) ?>" alt="Banner" style="max-width: 100%;">
    <?php endif; ?>
    <br>
    <input type="file" name="banner" accept="image/jpeg,image/png,image/gif,image/webp">

    <br><br>
    <button type="submit">Сохранить изменения</button>
</form>
</body>
</html>