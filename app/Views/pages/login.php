<h1><?= \App\Core\I18n::t('login_title') ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/login">
    <div class="form-group">
        <label for="login"><?= \App\Core\I18n::t('login_username') ?></label>
        <input type="text" id="login" name="login" required>
    </div>

    <div class="form-group">
        <label for="password"><?= \App\Core\I18n::t('login_password') ?></label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="remember" value="1">
            <?= \App\Core\I18n::t('login_remember') ?>
        </label>
    </div>

    <button type="submit" class="btn"><?= \App\Core\I18n::t('login_submit') ?></button>
</form>

<p style="margin-top: 16px;">
    <?= \App\Core\I18n::t('login_no_account') ?>
    <a href="/register"><?= \App\Core\I18n::t('login_register_link') ?></a>
</p>