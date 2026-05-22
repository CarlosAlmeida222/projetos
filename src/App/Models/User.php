<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class User extends Model
{
    protected string $table = 'users';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }

    public function paginate(int $page, int $perPage, ?string $search = null): array
    {
        $total = $this->count($search);
        $offset = ($page - 1) * $perPage;
        $data = $this->search($search, $perPage, $offset);

        return [
            'data' => $data,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => (int)ceil($total / $perPage),
        ];
    }

    public function count(?string $search = null): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= " WHERE name LIKE :search OR email LIKE :search OR status LIKE :search";
            $params['search'] = "%{$search}%";
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return (int)$statement->fetchColumn();
    }

    public function search(?string $search, int $limit, int $offset): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= " WHERE name LIKE :search OR email LIKE :search OR status LIKE :search";
            $params['search'] = "%{$search}%";
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $statement = $this->db->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

        if (!empty($params)) {
            $statement->bindValue(':search', $params['search'], PDO::PARAM_STR);
        }

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $data = $this->sanitize($data);
        if (!isset($data['password_hash']) || $data['password_hash'] === null) {
            throw new \InvalidArgumentException('Senha é obrigatória');
        }
        $sql = "INSERT INTO {$this->table} (name, email, password_hash, status, phone, created_at, updated_at) VALUES (:name, :email, :password_hash, :status, :phone, NOW(), NOW())";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'status' => $data['status'],
            'phone' => $data['phone'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->sanitize($data);
        $fields = [
            'name' => 'name = :name',
            'email' => 'email = :email',
            'status' => 'status = :status',
            'phone' => 'phone = :phone',
            'password_hash' => 'password_hash = :password_hash',
        ];

        $update = [];
        $params = ['id' => $id];

        foreach ($fields as $key => $sql) {
            if ($key === 'password_hash' && ($data['password_hash'] ?? null) === null) {
                continue;
            }
            $update[] = $sql;
            $params[$key] = $data[$key] ?? null;
        }

        if (empty($update)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $update) . ", updated_at = NOW() WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute(['id' => $id]);
    }

    private function sanitize(array $data): array
    {
        $result = [
            'name' => trim((string)($data['name'] ?? '')),
            'email' => trim((string)($data['email'] ?? '')),
            'status' => in_array($data['status'] ?? '', ['active', 'inactive', 'blocked'], true) ? $data['status'] : 'active',
            'phone' => trim((string)($data['phone'] ?? '')),
            'password_hash' => null,
        ];

        if (!empty($data['password'])) {
            $result['password_hash'] = password_hash((string)$data['password'], PASSWORD_DEFAULT);
        }

        if ($result['password_hash'] === null) {
            unset($result['password_hash']);
        }

        return $result;
    }
}
