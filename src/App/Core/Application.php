<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Application
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getDatabase(): PDO
    {
        return $this->db;
    }
}
