<h1>Разработчики на GameFolio</h1>

<form method="GET" action="/profiles" class="search-filters">
    <input type="text" name="q" value="<?= htmlspecialchars($currentQuery ?? '') ?>"
           placeholder="Поиск по никнейму или логину...">
    <button type="submit" class="btn">Найти</button>
    <?php if (!empty($currentQuery)): ?>
        <a href="/profiles" class="btn btn-outline">Сбросить</a>
    <?php endif; ?>
</form>

<?php if (empty($users)): ?>
    <p>Пользователи не найдены.</p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($users as $user): ?>
            <div class="card">
                <h3><a href="/profiles/<?= $user->id ?>"><?= htmlspecialchars($user->nickname) ?></a></h3>
                <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 8px;">
                    @<?= htmlspecialchars($user->login) ?>
                </p>
                <p><?= htmlspecialchars(mb_substr($user->bio ?? 'Нет описания', 0, 100)) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>