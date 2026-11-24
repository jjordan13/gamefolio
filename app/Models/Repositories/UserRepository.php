<?php
declare(strict_types=1);

namespace App\Models\Repositories;

use App\Core\Database;
use App\Models\Entities\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        return $data ? User::fromArray($data) : null;
    }

    public function findByLogin(string $login): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $data = $stmt->fetch();

        return $data ? User::fromArray($data) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY id DESC');
        $users = [];
        foreach ($stmt->fetchAll() as $row) {
            $users[] = User::fromArray($row);
        }
        return $users;
    }
}