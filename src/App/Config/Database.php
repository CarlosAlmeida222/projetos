<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    public static function connect(): PDO
    {
        $host = getenv('DB_HOST') ?: 'db';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'projeto_db';
        $user = getenv('DB_USER') ?: 'projeto_user';
        $pass = getenv('DB_PASS') ?: 'projeto_pass';

        try {
            $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $exception) {
            throw new PDOException('Erro de conexão com o banco de dados: ' . $exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
