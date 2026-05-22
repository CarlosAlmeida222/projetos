<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class ProfilePermission extends Model
{
    protected string $table = 'profile_permissions';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }
}
