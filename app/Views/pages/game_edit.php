<h1><?= \App\Core\I18n::t('game_edit_title') ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="/games/<?= $game['id'] ?>/edit" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title"><?= \App\Core\I18n::t('game_create_name') ?> *</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($game['title']) ?>" required maxlength="255">
    </div>

    <div class="form-group">
        <label for="short_description"><?= \App\Core\I18n::t('game_create_short_desc') ?></label>
        <textarea id="short_description" name="short_description" rows="3" maxlength="500"><?= htmlspecialchars($game['short_description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="full_description"><?= \App\Core\I18n::t('game_create_full_desc') ?></label>
        <textarea id="full_description" name="full_description" rows="10"><?= htmlspecialchars($game['full_description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="engine"><?= \App\Core\I18n::t('game_create_engine') ?></label>
        <input type="text" id="engine" name="engine" value="<?= htmlspecialchars($game['engine'] ?? '') ?>" maxlength="100">
    </div>

    <div class="form-group">
        <label for="genre"><?= \App\Core\I18n::t('game_create_genre') ?></label>
        <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($game['genre'] ?? '') ?>" maxlength="100">
    </div>

    <div class="form-group">
        <label><?= \App\Core\I18n::t('game_create_tags') ?></label>
        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px;">
            <?php
            $selectedTagIds = array_column($game['tags'] ?? [], 'id');
            foreach ($tags as $tag):
                ?>
                <label style="display: inline-flex; align-items: center; gap: 4px; background: var(--bg-card); padding: 6px 12px; border-radius: 2px; cursor: pointer; border: 1px solid var(--border);">
                    <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
                            <?= in_array($tag['id'], $selectedTagIds) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($tag['name']) ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <label><?= \App\Core\I18n::t('game_edit_media_gallery') ?></label>
        <?php if (empty($game['media'])): ?>
            <p><?= \App\Core\I18n::t('game_edit_no_media') ?></p>
        <?php else: ?>
            <?php foreach ($game['media'] as $media): ?>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px; padding: 8px; background: var(--bg-secondary); border-radius: 2px;">
                    <img src="<?= htmlspecialchars($media['url']) ?>" alt="" style="width: 160px; height: 90px; object-fit: cover;">
                    <label>
                        <input type="checkbox" name="delete_media[]" value="<?= $media['id'] ?>">
                        <?= \App\Core\I18n::t('game_edit_delete_media') ?>
                    </label>
                    <?php if ($media['is_cover']): ?>
                        <span style="color: var(--accent); font-size: 12px;"><?= \App\Core\I18n::t('game_edit_cover') ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label><?= \App\Core\I18n::t('game_edit_add_media') ?></label>
        <input type="file" name="media[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
    </div>

    <button type="submit" class="btn"><?= \App\Core\I18n::t('game_edit_save') ?></button>
    <a href="/games/<?= $game['id'] ?>" class="btn btn-outline"><?= \App\Core\I18n::t('game_create_cancel') ?></a>
</form>