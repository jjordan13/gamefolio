<?php
$banner = !empty($user->banner_url) ? $user->banner_url : 'https://via.placeholder.com/1024x576/2a475e/ffffff?text=No+Banner';
$avatar = !empty($user->avatar_url) ? $user->avatar_url : 'https://via.placeholder.com/200/171a21/ffffff?text=Avatar';
$socials = json_decode($user->social_links ?? '{}', true);
?>

<div class="profile-banner" style="background-image: url('<?= htmlspecialchars($banner) ?>');"></div>

<div class="profile-info">
    <div class="profile-avatar" style="background-image: url('<?= htmlspecialchars($avatar) ?>');"></div>

    <div class="profile-details">
        <h1><?= htmlspecialchars($user->nickname) ?></h1>
        <div class="login">@<?= htmlspecialchars($user->login) ?></div>

        <?php if (!empty($user->bio)): ?>
            <div class="bio"><?= nl2br(htmlspecialchars($user->bio)) ?></div>
        <?php endif; ?>

        <?php if (!empty($socials)): ?>
            <div style="margin-top: 15px;">
                <strong><?= \App\Core\I18n::t('profile_socials') ?>:</strong>
                <ul>
                    <?php foreach ($socials as $name => $link): ?>
                        <li><a href="<?= htmlspecialchars($link) ?>" target="_blank"><?= htmlspecialchars($name) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::userId() === $user->id): ?>
            <p style="margin-top: 16px;">
                <a href="/profile/edit" class="btn"><?= \App\Core\I18n::t('profile_edit_btn') ?></a>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="games-section">
    <h2><?= \App\Core\I18n::t('profile_projects') ?> (<?= count($games) ?>)</h2>

    <?php if (empty($games)): ?>
        <p><?= \App\Core\I18n::t('profile_no_games') ?></p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($games as $game): ?>
                <div class="card">
                    <h3><a href="/games/<?= $game->id ?>"><?= htmlspecialchars($game->title) ?></a></h3>
                    <p style="color: var(--text-secondary); font-size: 13px;">
                        <?= htmlspecialchars($game->engine ?? \App\Core\I18n::t('games_no_engine')) ?> •
                        <?= htmlspecialchars($game->genre ?? \App\Core\I18n::t('games_no_genre')) ?>
                    </p>
                    <p style="font-size: 14px;"><?= htmlspecialchars(mb_substr($game->short_description ?? '', 0, 100)) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (\App\Core\Session::userId() === $user->id): ?>
        <div class="card" style="text-align: center; background: var(--bg-hover);">
            <a href="/games/create" style="font-size: 24px; color: var(--accent);"><?= \App\Core\I18n::t('profile_add_game') ?></a>
        </div>
    <?php endif; ?>
</div>