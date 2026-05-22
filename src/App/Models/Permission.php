<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Permission extends Model
{
    protected string $table = 'permissions';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }
}
