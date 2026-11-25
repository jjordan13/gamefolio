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

<h1>Настройки аккаунта</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<h2>Смена логина</h2>
<form method="POST" action="/settings/login">
    <label>
        Новый логин:<br>
        <input type="text" name="login" value="<?= htmlspecialchars($user->login) ?>" required minlength="3" maxlength="50">
    </label>
    <br><br>
    <button type="submit">Изменить логин</button>
</form>

<hr>

<h2>Смена пароля</h2>
<form method="POST" action="/settings/password">
    <label>
        Текущий пароль:<br>
        <input type="password" name="old_password" required>
    </label>
    <br><br>

    <label>
        Новый пароль:<br>
        <input type="password" name="new_password" required minlength="6">
    </label>
    <br><br>

    <label>
        Подтверждение нового пароля:<br>
        <input type="password" name="confirm_password" required>
    </label>
    <br><br>

    <button type="submit">Изменить пароль</button>
</form>

<hr>

<h2 style="color: red;">Удаление аккаунта</h2>
<p>Это действие необратимо. Все ваши игры, комментарии и лайки будут удалены.</p>
<form method="POST" action="/settings/delete" onsubmit="return confirm('Вы уверены, что хотите удалить аккаунт? Это действие необратимо!');">
    <label>
        Введите пароль для подтверждения:<br>
        <input type="password" name="password" required>
    </label>
    <br><br>
    <button type="submit" style="background: red; color: white;">Удалить аккаунт</button>
</form>
</body>
</html>