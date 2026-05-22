<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $statement = $this->db->query("SELECT * FROM {$this->table}");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findBy(string $field, string $value): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
        $statement = $this->db->prepare($sql);
        $statement->execute(['value' => $value]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }
}
