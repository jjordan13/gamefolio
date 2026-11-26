<h1>Регистрация в GameFolio</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <div class="form-group">
        <label for="login">Логин</label>
        <input type="text" id="login" name="login" required minlength="3" maxlength="50">
    </div>

    <div class="form-group">
        <label for="nickname">Никнейм</label>
        <input type="text" id="nickname" name="nickname" required maxlength="100">
    </div>

    <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required minlength="6">
    </div>

    <div class="form-group">
        <label for="password_confirm">Подтверждение пароля</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>

    <button type="submit" class="btn">Зарегистрироваться</button>
</form>

<p style="margin-top: 16px;">Уже есть аккаунт? <a href="/login">Войти</a></p>