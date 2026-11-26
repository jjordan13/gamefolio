<h1>Настройки аккаунта</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<div class="card">
    <h2>Смена логина</h2>
    <form method="POST" action="/settings/login">
        <div class="form-group">
            <label for="login">Новый логин</label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($user->login) ?>" required minlength="3" maxlength="50">
        </div>
        <button type="submit" class="btn">Изменить логин</button>
    </form>
</div>

<div class="card">
    <h2>Смена пароля</h2>
    <form method="POST" action="/settings/password">
        <div class="form-group">
            <label for="old_password">Текущий пароль</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Новый пароль</label>
            <input type="password" id="new_password" name="new_password" required minlength="6">
        </div>
        <div class="form-group">
            <label for="confirm_password">Подтверждение нового пароля</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn">Изменить пароль</button>
    </form>
</div>

<div class="card" style="border-color: var(--error);">
    <h2 style="color: var(--error);">Удаление аккаунта</h2>
    <p>Это действие необратимо. Все ваши игры, комментарии и лайки будут удалены.</p>
    <form method="POST" action="/settings/delete"
          onsubmit="return confirm('Вы уверены, что хотите удалить аккаунт? Это действие необратимо!');">
        <div class="form-group">
            <label for="password">Введите пароль для подтверждения</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-danger">Удалить аккаунт</button>
    </form>
</div>