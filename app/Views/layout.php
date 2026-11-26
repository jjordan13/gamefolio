<?php
use App\Core\I18n;
use App\Core\Session;
use App\Models\Repositories\UserRepository;
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>" data-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <a href="/" class="logo">🎮 GameFolio</a>
    <nav>
        <a href="/games"><?= I18n::t('nav_games') ?></a>
        <a href="/profiles"><?= I18n::t('nav_profiles') ?></a>

        <?php if (Session::isLoggedIn()): ?>
            <div class="user-menu">
                <button class="user-menu-btn">
                    <?php
                    $userId = Session::userId();
                    $userRepo = new UserRepository();
                    $currentUser = $userRepo->findById($userId);
                    $avatar = $currentUser->avatar_url ?? 'https://via.placeholder.com/32';
                    ?>
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar">
                    <span>▼</span>
                </button>
                <div class="user-menu-dropdown">
                    <a href="/profiles/<?= $userId ?>"><?= I18n::t('nav_my_profile') ?></a>
                    <a href="/settings"><?= I18n::t('nav_settings') ?></a>
                    <a href="/logout"><?= I18n::t('nav_logout') ?></a>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-buttons">
                <a href="/login" class="btn btn-outline"><?= I18n::t('nav_login') ?></a>
                <a href="/register" class="btn"><?= I18n::t('nav_register') ?></a>
            </div>
        <?php endif; ?>
    </nav>
</header>

<main>
    <?= $pageContent ?>
</main>

<footer>
    <div class="footer-controls">
        <form method="POST" action="/preferences/theme" style="display: inline;">
            <input type="hidden" name="theme" value="<?= $theme === 'dark' ? 'light' : 'dark' ?>">
            <button type="submit">
                <?= $theme === 'dark' ? '☀️ ' . I18n::t('footer_theme_light') : '🌙 ' . I18n::t('footer_theme_dark') ?>
            </button>
        </form>
        <form method="POST" action="/preferences/lang" style="display: inline;">
            <input type="hidden" name="lang" value="<?= $lang === 'ru' ? 'en' : 'ru' ?>">
            <button type="submit">
                <?= $lang === 'ru' ? '🇬🇧 EN' : '🇷🇺 RU' ?>
            </button>
        </form>
    </div>
    <p>&copy; 2026 GameFolio. <?= I18n::t('footer_rights') ?>.</p>
</footer>
</body>
</html>