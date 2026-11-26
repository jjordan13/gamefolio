<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Core\Database;
use App\Models\Entities\Game;
use PDO;

class GameRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?Game
    {
        $stmt = $this->db->prepare('SELECT * FROM games WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? Game::fromArray($data) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM games ORDER BY created_at DESC');
        $games = [];
        foreach ($stmt->fetchAll() as $row) {
            $games[] = Game::fromArray($row);
        }
        return $games;
    }

    public function findAllWithAuthor(): array
    {
        $sql = "SELECT g.*, u.nickname AS author_nickname 
                FROM games g 
                JOIN users u ON g.author_id = u.id 
                ORDER BY g.created_at DESC";
        $stmt = $this->db->query($sql);
        $games = [];
        foreach ($stmt->fetchAll() as $row) {
            $game = Game::fromArray($row);
            $game->author_nickname = $row['author_nickname'];
            $games[] = $game;
        }
        return $games;
    }

    public function findByAuthor(int $authorId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM games WHERE author_id = :author_id ORDER BY created_at DESC');
        $stmt->execute(['author_id' => $authorId]);
        $games = [];
        foreach ($stmt->fetchAll() as $row) {
            $games[] = Game::fromArray($row);
        }
        return $games;
    }

    public function findWithDetails(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT g.*, u.nickname AS author_nickname 
             FROM games g 
             JOIN users u ON g.author_id = u.id 
             WHERE g.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $game = $stmt->fetch();
        if (!$game) {
            return null;
        }

        $stmt = $this->db->prepare(
            "SELECT t.* FROM tags t 
             JOIN game_tags gt ON t.id = gt.tag_id 
             WHERE gt.game_id = :game_id"
        );
        $stmt->execute(['game_id' => $id]);
        $game['tags'] = $stmt->fetchAll();

        $stmt = $this->db->prepare(
            "SELECT * FROM game_media 
             WHERE game_id = :game_id 
             ORDER BY sort_order ASC"
        );
        $stmt->execute(['game_id' => $id]);
        $game['media'] = $stmt->fetchAll();

        return $game;
    }

    public function create(int $authorId, string $title, string $shortDescription,
                           string $fullDescription, string $engine, string $genre): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO games (author_id, title, short_description, full_description, engine, genre) 
             VALUES (:author_id, :title, :short_description, :full_description, :engine, :genre) 
             RETURNING id'
        );
        $stmt->execute([
            'author_id' => $authorId,
            'title' => $title,
            'short_description' => $shortDescription ?: null,
            'full_description' => $fullDescription ?: null,
            'engine' => $engine ?: null,
            'genre' => $genre ?: null,
        ]);
        return (int) $stmt->fetchColumn();
    }

    public function update(int $gameId, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE games 
             SET title = :title,
                 short_description = :short_description,
                 full_description = :full_description,
                 engine = :engine,
                 genre = :genre,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $gameId,
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'full_description' => $data['full_description'],
            'engine' => $data['engine'],
            'genre' => $data['genre'],
        ]);
    }

    public function delete(int $gameId): void
    {
        $stmt = $this->db->prepare('DELETE FROM games WHERE id = :id');
        $stmt->execute(['id' => $gameId]);
    }

    public function isOwner(int $gameId, int $userId): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM games WHERE id = :id AND author_id = :author_id');
        $stmt->execute(['id' => $gameId, 'author_id' => $userId]);
        return (bool) $stmt->fetch();
    }

    public function addMedia(int $gameId, string $url, string $type, int $sortOrder, bool $isCover): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO game_media (game_id, url, type, sort_order, is_cover) 
             VALUES (:game_id, :url, :type, :sort_order, :is_cover)'
        );
        $stmt->bindValue('game_id', $gameId, PDO::PARAM_INT);
        $stmt->bindValue('url', $url);
        $stmt->bindValue('type', $type);
        $stmt->bindValue('sort_order', $sortOrder, PDO::PARAM_INT);
        $stmt->bindValue('is_cover', $isCover, PDO::PARAM_BOOL);
        $stmt->execute();
    }

    public function getNextMediaOrder(int $gameId): int
    {
        $stmt = $this->db->prepare('SELECT COALESCE(MAX(sort_order), -1) + 1 FROM game_media WHERE game_id = :id');
        $stmt->execute(['id' => $gameId]);
        return (int) $stmt->fetchColumn();
    }

    public function deleteMedia(array $mediaIds, int $gameId): array
    {
        if (empty($mediaIds)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($mediaIds), '?'));
        $stmt = $this->db->prepare(
            "SELECT url FROM game_media WHERE game_id = ? AND id IN ($placeholders)"
        );
        $stmt->execute(array_merge([$gameId], $mediaIds));
        $urls = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt = $this->db->prepare(
            "DELETE FROM game_media WHERE game_id = ? AND id IN ($placeholders)"
        );
        $stmt->execute(array_merge([$gameId], $mediaIds));
        return $urls;
    }

    public function findAllMediaUrls(int $gameId): array
    {
        $stmt = $this->db->prepare('SELECT url FROM game_media WHERE game_id = :id');
        $stmt->execute(['id' => $gameId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function findAllTags(): array
    {
        $stmt = $this->db->query('SELECT * FROM tags ORDER BY name');
        return $stmt->fetchAll();
    }

    public function findTagsByGame(int $gameId): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.* FROM tags t 
             JOIN game_tags gt ON t.id = gt.tag_id 
             WHERE gt.game_id = :game_id 
             ORDER BY t.name'
        );
        $stmt->execute(['game_id' => $gameId]);
        return $stmt->fetchAll();
    }

    public function syncGameTags(int $gameId, array $tagIds): void
    {
        $stmt = $this->db->prepare('DELETE FROM game_tags WHERE game_id = :game_id');
        $stmt->execute(['game_id' => $gameId]);

        if (!empty($tagIds)) {
            $stmt = $this->db->prepare('INSERT INTO game_tags (game_id, tag_id) VALUES (:game_id, :tag_id)');
            foreach ($tagIds as $tagId) {
                $stmt->execute(['game_id' => $gameId, 'tag_id' => (int) $tagId]);
            }
        }
    }

    public function search(string $query = '', ?int $tagId = null): array
    {
        $sql = 'SELECT g.*, u.nickname AS author_nickname 
                FROM games g 
                JOIN users u ON g.author_id = u.id';
        $conditions = [];
        $params = [];

        if ($query !== '') {
            $conditions[] = 'g.title ILIKE :query';
            $params['query'] = '%' . $query . '%';
        }

        if ($tagId !== null) {
            $sql .= ' JOIN game_tags gt ON g.id = gt.game_id';
            $conditions[] = 'gt.tag_id = :tag_id';
            $params['tag_id'] = $tagId;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY g.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $games = [];
        foreach ($stmt->fetchAll() as $row) {
            $game = Game::fromArray($row);
            $game->author_nickname = $row['author_nickname'];
            $games[] = $game;
        }
        return $games;
    }
}