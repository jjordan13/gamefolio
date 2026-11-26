<h1><?= \App\Core\I18n::t('register_title') ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <div class="form-group">
        <label for="login"><?= \App\Core\I18n::t('login_username') ?></label>
        <input type="text" id="login" name="login" required minlength="3" maxlength="50">
    </div>

    <div class="form-group">
        <label for="nickname"><?= \App\Core\I18n::t('register_nickname') ?></label>
        <input type="text" id="nickname" name="nickname" required maxlength="100">
    </div>

    <div class="form-group">
        <label for="password"><?= \App\Core\I18n::t('login_password') ?></label>
        <input type="password" id="password" name="password" required minlength="6">
    </div>

    <div class="form-group">
        <label for="password_confirm"><?= \App\Core\I18n::t('register_password_confirm') ?></label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>

    <button type="submit" class="btn"><?= \App\Core\I18n::t('register_submit') ?></button>
</form>

<p style="margin-top: 16px;">
    <?= \App\Core\I18n::t('register_has_account') ?>
    <a href="/login"><?= \App\Core\I18n::t('register_login_link') ?></a>
</p>