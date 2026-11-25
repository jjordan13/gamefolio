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

    public function save(string $login, string $passwordHash, string $nickname): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (login, password_hash, nickname) 
         VALUES (:login, :password_hash, :nickname) 
         RETURNING id'
        );
        $stmt->execute([
            'login' => $login,
            'password_hash' => $passwordHash,
            'nickname' => $nickname,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function existsByLogin(string $login): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE login = :login LIMIT 1');
        $stmt->execute(['login' => $login]);
        return (bool) $stmt->fetch();
    }

    public function updateProfile(int $userId, array $data): void
    {
        $fields = [];
        $params = ['id' => $userId];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) {
            return;
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }
}