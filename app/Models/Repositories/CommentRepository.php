<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Core\Database;
use PDO;

class CommentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByGame(int $gameId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, u.nickname AS author_nickname 
             FROM comments c 
             JOIN users u ON c.user_id = u.id 
             WHERE c.game_id = :game_id 
             ORDER BY c.created_at DESC'
        );
        $stmt->execute(['game_id' => $gameId]);
        return $stmt->fetchAll();
    }

    public function create(int $gameId, int $userId, string $content): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO comments (game_id, user_id, content) 
             VALUES (:game_id, :user_id, :content)'
        );
        $stmt->execute([
            'game_id' => $gameId,
            'user_id' => $userId,
            'content' => $content,
        ]);
    }

    public function delete(int $commentId, int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM comments WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $commentId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}