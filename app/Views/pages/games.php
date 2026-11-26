<h1>Каталог игр</h1>

<form method="GET" action="/games" class="search-filters">
    <input type="text" name="q" value="<?= htmlspecialchars($currentQuery ?? '') ?>"
           placeholder="Поиск по названию...">

    <select name="tag">
        <option value="">Все теги</option>
        <?php foreach ($tags as $tag): ?>
            <option value="<?= $tag['id'] ?>"
                    <?= ($currentTagId ?? null) === $tag['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn">Найти</button>

    <?php if (!empty($currentQuery) || $currentTagId !== null): ?>
        <a href="/games" class="btn btn-outline">Сбросить</a>
    <?php endif; ?>
</form>

<?php if (empty($games)): ?>
    <p>Игры не найдены. Попробуйте изменить параметры поиска.</p>
<?php else: ?>
    <div class="card-grid">
        <?php foreach ($games as $game): ?>
            <div class="card">
                <h3><a href="/games/<?= $game->id ?>"><?= htmlspecialchars($game->title) ?></a></h3>
                <p style="color: var(--text-secondary); font-size: 13px;">
                    <?= htmlspecialchars($game->engine ?? 'Движок не указан') ?> •
                    <?= htmlspecialchars($game->genre ?? 'Жанр не указан') ?>
                </p>
                <p>Автор: <a href="/profiles/<?= $game->author_id ?>"><?= htmlspecialchars($game->author_nickname) ?></a></p>
                <p><?= htmlspecialchars($game->short_description ?? 'Без описания') ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>