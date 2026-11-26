<h2><?= \App\Core\I18n::t('welcome_title') ?></h2>
<p><?= \App\Core\I18n::t('welcome_desc') ?></p>

<div class="features">
    <h3><?= \App\Core\I18n::t('features_title') ?>:</h3>
    <ul>
        <?php foreach ($features as $feature): ?>
            <li><?= htmlspecialchars($feature) ?></li>
        <?php endforeach; ?>
    </ul>
</div>