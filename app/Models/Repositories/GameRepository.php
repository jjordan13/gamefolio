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
}