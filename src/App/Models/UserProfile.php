<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class UserProfile extends Model
{
    protected string $table = 'user_profiles';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }
}
