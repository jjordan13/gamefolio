<div class="game-header">
    <h1><?= htmlspecialchars($game['title']) ?></h1>
    <p class="game-author">Разработчик: <a href="/profiles/<?= $game['author_id'] ?>"><?= htmlspecialchars($game['author_nickname']) ?></a></p>
</div>

<?php if (!empty($success)): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="game-main">
    <!-- Карусель медиа -->
    <div class="media-carousel">
        <div class="media-carousel-inner" id="mediaCarousel">
            <?php if (empty($game['media'])): ?>
                <div class="media-carousel-item">
                    <img src="https://via.placeholder.com/800x450?text=No+Media" alt="No media">
                </div>
            <?php else: ?>
                <?php foreach ($game['media'] as $media): ?>
                    <div class="media-carousel-item">
                        <img src="<?= htmlspecialchars($media['url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (count($game['media'] ?? []) > 1): ?>
            <div class="carousel-controls">
                <button type="button" class="carousel-btn" onclick="scrollCarousel(-1)">‹</button>
                <button type="button" class="carousel-btn" onclick="scrollCarousel(1)">›</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Инфо-блок -->
    <div class="game-info">
        <h3>Об игре</h3>
        <p><?= htmlspecialchars($game['short_description'] ?? 'Описание отсутствует') ?></p>

        <p><strong>Движок:</strong> <?= htmlspecialchars($game['engine'] ?? 'Не указан') ?></p>
        <p><strong>Жанр:</strong> <?= htmlspecialchars($game['genre'] ?? 'Не указан') ?></p>

        <h4>Теги:</h4>
        <div>
            <?php if (empty($gameTags)): ?>
                <span style="color: var(--text-secondary);">Теги не указаны</span>
            <?php else: ?>
                <?php foreach ($gameTags as $tag): ?>
                    <a href="/games?tag=<?= $tag['id'] ?>" class="tag"><?= htmlspecialchars($tag['name']) ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (\App\Core\Session::userId() === (int) $game['author_id']): ?>
            <div style="margin-top: 20px;">
                <a href="/games/<?= $game['id'] ?>/edit" class="btn">Редактировать</a>
                <form method="POST" action="/games/<?= $game['id'] ?>/delete"
                      style="display: inline; margin-left: 10px;"
                      onsubmit="return confirm('Удалить игру безвозвратно?');">
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="game-description">
    <h2>Подробное описание</h2>
    <p><?= nl2br(htmlspecialchars($game['full_description'] ?? 'Подробное описание не добавлено.')) ?></p>
</div>

<!-- Лайки -->
<div class="card" style="margin-bottom: 24px;">
    <h3>Оценка сообщества</h3>
    <p>Лайков: <strong><?= (int) $likeCount ?></strong></p>

    <?php if (\App\Core\Session::isLoggedIn()): ?>
        <form method="POST" action="/games/<?= $game['id'] ?>/like" style="display: inline;">
            <button type="submit" class="btn">
                <?= $isLiked ? '❤️ Снять лайк' : '🤍 Поставить лайк' ?>
            </button>
        </form>
    <?php else: ?>
        <p><a href="/login">Войдите</a>, чтобы оценить игру</p>
    <?php endif; ?>
</div>

<!-- Комментарии -->
<div class="comments-section">
    <h3>Комментарии (<?= count($comments) ?>)</h3>

    <?php if (\App\Core\Session::isLoggedIn()): ?>
        <form method="POST" action="/games/<?= $game['id'] ?>/comment" style="margin-bottom: 20px;">
            <textarea name="content" rows="3" required placeholder="Напишите комментарий..."></textarea>
            <br><br>
            <button type="submit" class="btn">Отправить</button>
        </form>
    <?php else: ?>
        <p><a href="/login">Войдите</a>, чтобы оставить комментарий</p>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
        <p>Комментариев пока нет. Будьте первым!</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author"><?= htmlspecialchars($comment['author_nickname']) ?></span>
                    <span class="comment-date"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></span>
                </div>
                <p class="comment-content"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>

                <?php if (\App\Core\Session::userId() === (int) $comment['user_id']): ?>
                    <form method="POST" action="/comments/<?= $comment['id'] ?>/delete"
                          style="display: inline; margin-top: 8px;"
                          onsubmit="return confirm('Удалить комментарий?');">
                        <button type="submit" style="background: none; border: none; color: var(--error); cursor: pointer;">
                            Удалить
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function scrollCarousel(direction) {
        const carousel = document.getElementById('mediaCarousel');
        const scrollAmount = carousel.offsetWidth;
        carousel.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }
</script>