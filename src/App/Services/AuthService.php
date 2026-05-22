<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use PDO;

class AuthService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function login(string $email, string $password): ?array
    {
        $user = (new User($this->db))->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }
}
