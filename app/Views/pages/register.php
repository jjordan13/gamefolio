<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация — GameFolio</title>
</head>
<body>
<h1>Регистрация в GameFolio</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <label>
        Логин:<br>
        <input type="text" name="login" required minlength="3" maxlength="50">
    </label>
    <br><br>

    <label>
        Никнейм:<br>
        <input type="text" name="nickname" required maxlength="100">
    </label>
    <br><br>

    <label>
        Пароль:<br>
        <input type="password" name="password" required minlength="6">
    </label>
    <br><br>

    <label>
        Подтверждение пароля:<br>
        <input type="password" name="password_confirm" required>
    </label>
    <br><br>

    <button type="submit">Зарегистрироваться</button>
</form>

<p>Уже есть аккаунт? <a href="/login">Войти</a></p>
<p><a href="/">На главную</a></p>
</body>
</html>