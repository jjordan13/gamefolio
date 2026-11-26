<h1><?= \App\Core\I18n::t('profiles_title') ?></h1>

<form method="GET" action="/profiles" class="search-filters">
    <input type="text" name="q" value="<?= htmlspecialchars($currentQuery ?? '') ?>"
           placeholder="<?= \App\Core\I18n::t('profiles_search_placeholder') ?>">
    <button type="submit" class="btn"><?= \App\Core\I18n::t('games_search_btn') ?></button>
    <?php if (!empty($currentQuery)): ?>
        <a href="/profiles" class="btn btn-outline"><?= \App\Core\I18n::t('games_reset_btn') ?></a>
    <?php endif; ?>
</form>

<?php if (empty($users)): ?>
    <p><?= \App\Core\I18n::t('profiles_not_found') ?></p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($users as $user): ?>
            <div class="card">
                <h3><a href="/profiles/<?= $user->id ?>"><?= htmlspecialchars($user->nickname) ?></a></h3>
                <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 8px;">
                    @<?= htmlspecialchars($user->login) ?>
                </p>
                <p style="font-size: 14px;"><?= htmlspecialchars(mb_substr($user->bio ?? \App\Core\I18n::t('profiles_no_description'), 0, 100)) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>