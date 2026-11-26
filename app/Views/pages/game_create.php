<h1>Добавить новую игру</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/games/create" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Название игры *</label>
        <input type="text" id="title" name="title" required maxlength="255">
    </div>

    <div class="form-group">
        <label for="short_description">Краткое описание</label>
        <textarea id="short_description" name="short_description" rows="3" maxlength="500"></textarea>
    </div>

    <div class="form-group">
        <label for="full_description">Полное описание</label>
        <textarea id="full_description" name="full_description" rows="10"></textarea>
    </div>

    <div class="form-group">
        <label for="engine">Движок</label>
        <input type="text" id="engine" name="engine" maxlength="100" placeholder="Unity, Unreal Engine, Godot...">
    </div>

    <div class="form-group">
        <label for="genre">Жанр</label>
        <input type="text" id="genre" name="genre" maxlength="100" placeholder="Action, RPG, Strategy...">
    </div>

    <div class="form-group">
        <label>Теги</label>
        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px;">
            <?php foreach ($tags as $tag): ?>
                <label style="display: inline-flex; align-items: center; gap: 4px; background: var(--bg-card); padding: 6px 12px; border-radius: 2px; cursor: pointer; border: 1px solid var(--border);">
                    <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>">
                    <?= htmlspecialchars($tag['name']) ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <label>Медиафайлы (скриншоты, GIF)</label>
        <input type="file" name="media[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
        <p style="font-size: 12px; color: var(--text-secondary);">Можно выбрать несколько файлов. Первый файл станет обложкой.</p>
    </div>

    <button type="submit" class="btn">Создать игру</button>
    <a href="/games" class="btn btn-outline">Отмена</a>
</form>