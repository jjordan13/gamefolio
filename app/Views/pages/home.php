<section style="text-align: center; padding: 40px 20px; background: var(--bg-card); border-radius: 4px; margin-bottom: 40px;">
    <h1><?= \App\Core\I18n::t('welcome_title') ?></h1>
    <p style="font-size: 18px; color: var(--text-secondary); margin-top: 16px;">
        <?= \App\Core\I18n::t('welcome_desc') ?>
    </p>
</section>

<section style="margin-bottom: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><?= \App\Core\I18n::t('last_games') ?></h2>
        <a href="/games" class="btn btn-outline"><?= \App\Core\I18n::t('games_search_btn') ?></a>
    </div>

    <?php if (empty($recentGames)): ?>
        <p style="color: var(--text-secondary);"><?= \App\Core\I18n::t('no_games_yet') ?></p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($recentGames as $game): ?>
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
                    <p style="font-size: 14px;"><?= htmlspecialchars(mb_substr($game->short_description ?? \App\Core\I18n::t('games_no_description'), 0, 100)) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section style="margin-bottom: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><?= \App\Core\I18n::t('new_developers') ?></h2>
        <a href="/profiles" class="btn btn-outline"><?= \App\Core\I18n::t('games_search_btn') ?></a>
    </div>

    <?php if (empty($recentUsers)): ?>
        <p style="color: var(--text-secondary);"><?= \App\Core\I18n::t('no_users_yet') ?></p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($recentUsers as $user): ?>
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
</section>

<section style="background: var(--bg-card); padding: 30px; border-radius: 4px; text-align: center;">
    <h2><?= \App\Core\I18n::t('contacts') ?></h2>
    <p style="color: var(--text-secondary); margin-top: 16px;">
        Email: <a href="mailto:support@gamefolio.ru">support@gamefolio.ru</a>
    </p>
    <p style="color: var(--text-secondary);">
        Telegram: <a href="https://t.me/gamefolio" target="_blank">@gamefolio</a>
    </p>
    <p style="color: var(--text-secondary);">
        GitHub: <a href="https://github.com/gamefolio" target="_blank">github.com/gamefolio</a>
    </p>
</section>