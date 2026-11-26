<h1><?= \App\Core\I18n::t('settings_title') ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<div class="card">
    <h2><?= \App\Core\I18n::t('settings_change_login') ?></h2>
    <form method="POST" action="/settings/login">
        <div class="form-group">
            <label for="login"><?= \App\Core\I18n::t('settings_new_login') ?></label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($user->login) ?>" required minlength="3" maxlength="50">
        </div>
        <button type="submit" class="btn"><?= \App\Core\I18n::t('settings_change_login_btn') ?></button>
    </form>
</div>

<div class="card">
    <h2><?= \App\Core\I18n::t('settings_change_password') ?></h2>
    <form method="POST" action="/settings/password">
        <div class="form-group">
            <label for="old_password"><?= \App\Core\I18n::t('settings_old_password') ?></label>
            <input type="password" id="old_password" name="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password"><?= \App\Core\I18n::t('settings_new_password') ?></label>
            <input type="password" id="new_password" name="new_password" required minlength="6">
        </div>
        <div class="form-group">
            <label for="confirm_password"><?= \App\Core\I18n::t('settings_confirm_password') ?></label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn"><?= \App\Core\I18n::t('settings_change_password_btn') ?></button>
    </form>
</div>

<div class="card" style="border-color: var(--error);">
    <h2 style="color: var(--error);"><?= \App\Core\I18n::t('settings_delete_account') ?></h2>
    <p><?= \App\Core\I18n::t('settings_delete_warning') ?></p>
    <form method="POST" action="/settings/delete"
          onsubmit="return confirm('<?= \App\Core\I18n::t('settings_delete_confirm') ?>');">
        <div class="form-group">
            <label for="password"><?= \App\Core\I18n::t('settings_delete_confirm_text') ?></label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-danger"><?= \App\Core\I18n::t('settings_delete_btn') ?></button>
    </form>
</div>