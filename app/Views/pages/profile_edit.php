<h1><?= \App\Core\I18n::t('profile_edit_title') ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/profile/edit" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nickname"><?= \App\Core\I18n::t('profile_edit_nickname') ?></label>
        <input type="text" id="nickname" name="nickname" value="<?= htmlspecialchars($user->nickname) ?>" required maxlength="100">
    </div>

    <div class="form-group">
        <label for="bio"><?= \App\Core\I18n::t('profile_edit_bio') ?></label>
        <textarea id="bio" name="bio" rows="5"><?= htmlspecialchars($user->bio ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label><?= \App\Core\I18n::t('profile_edit_avatar') ?></label>
        <?php if ($user->avatar_url): ?>
            <p><?= \App\Core\I18n::t('profile_edit_current_avatar') ?>:</p>
            <img src="<?= htmlspecialchars($user->avatar_url) ?>" alt="Avatar" style="max-width: 150px; border-radius: 50%;">
        <?php endif; ?>
        <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp">
    </div>

    <div class="form-group">
        <label><?= \App\Core\I18n::t('profile_edit_banner') ?></label>
        <?php if ($user->banner_url): ?>
            <p><?= \App\Core\I18n::t('profile_edit_current_banner') ?>:</p>
            <img src="<?= htmlspecialchars($user->banner_url) ?>" alt="Banner" style="max-width: 100%;">
        <?php endif; ?>
        <input type="file" name="banner" accept="image/jpeg,image/png,image/gif,image/webp">
    </div>

    <button type="submit" class="btn"><?= \App\Core\I18n::t('profile_edit_save') ?></button>
</form>