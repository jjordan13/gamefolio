<h1><?= \App\Core\I18n::t('games_catalog') ?></h1>

<form method="GET" action="/games" class="search-filters">
    <input type="text" name="q" value="<?= htmlspecialchars($currentQuery ?? '') ?>"
           placeholder="<?= \App\Core\I18n::t('games_search_placeholder') ?>">

    <select name="tag">
        <option value=""><?= \App\Core\I18n::t('games_all_tags') ?></option>
        <?php foreach ($tags as $tag): ?>
            <option value="<?= $tag['id'] ?>"
                    <?= ($currentTagId ?? null) === $tag['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn"><?= \App\Core\I18n::t('games_search_btn') ?></button>

    <?php if (!empty($currentQuery) || $currentTagId !== null): ?>
        <a href="/games" class="btn btn-outline"><?= \App\Core\I18n::t('games_reset_btn') ?></a>
    <?php endif; ?>
</form>

<?php if (empty($games)): ?>
    <p><?= \App\Core\I18n::t('games_not_found') ?></p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($games as $game): ?>
            <div class="card">
                <h3><a href="/games/<?= $game->id ?>"><?= htmlspecialchars($game->title) ?></a></h3>
                <p style="color: var(--text-secondary); font-size: 13px;">
                    <?= htmlspecialchars($game->engine ?? \App\Core\I18n::t('games_no_engine')) ?> •
                    <?= htmlspecialchars($game->genre ?? \App\Core\I18n::t('games_no_genre')) ?>
                </p>
                <p style="font-size: 13px;">
                    <?= \App\Core\I18n::t('games_author') ?>:
                    <a href="/profiles/<?= $game->author_id ?>"><?= htmlspecialchars($game->author_nickname) ?></a>
                </p>
                <p style="font-size: 14px;"><?= htmlspecialchars($game->short_description ?? \App\Core\I18n::t('games_no_description')) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>