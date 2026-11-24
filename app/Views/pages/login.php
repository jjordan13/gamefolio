<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход — GameFolio</title>
</head>
<body>
<h1>Вход в GameFolio</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/login">
    <label>
        Логин:<br>
        <input type="text" name="login" required>
    </label>
    <br><br>

    <label>
        Пароль:<br>
        <input type="password" name="password" required>
    </label>
    <br><br>

    <label>
        <input type="checkbox" name="remember" value="1">
        Запомнить меня
    </label>
    <br><br>

    <button type="submit">Войти</button>
</form>

<p>Нет аккаунта? <a href="/register">Зарегистрироваться</a></p>
<p><a href="/">На главную</a></p>
</body>
</html>