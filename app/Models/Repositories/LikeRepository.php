<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Core\Database;
use PDO;

class LikeRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function countByGame(int $gameId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM likes WHERE game_id = :game_id');
        $stmt->execute(['game_id' => $gameId]);
        return (int) $stmt->fetchColumn();
    }

    public function isLikedByUser(int $gameId, int $userId): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM likes WHERE game_id = :game_id AND user_id = :user_id');
        $stmt->execute(['game_id' => $gameId, 'user_id' => $userId]);
        return (bool) $stmt->fetch();
    }

    public function toggle(int $gameId, int $userId): bool
    {
        if ($this->isLikedByUser($gameId, $userId)) {
            $stmt = $this->db->prepare('DELETE FROM likes WHERE game_id = :game_id AND user_id = :user_id');
            $stmt->execute(['game_id' => $gameId, 'user_id' => $userId]);
            return false; // лайк снят
        }

        $stmt = $this->db->prepare('INSERT INTO likes (game_id, user_id) VALUES (:game_id, :user_id)');
        $stmt->execute(['game_id' => $gameId, 'user_id' => $userId]);
        return true; // лайк поставлен
    }
}