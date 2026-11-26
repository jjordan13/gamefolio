<h1>Вход в GameFolio</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/login">
    <div class="form-group">
        <label for="login">Логин</label>
        <input type="text" id="login" name="login" required>
    </div>

    <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="remember" value="1">
            Запомнить меня
        </label>
    </div>

    <button type="submit" class="btn">Войти</button>
</form>

<p style="margin-top: 16px;">Нет аккаунта? <a href="/register">Зарегистрироваться</a></p>