<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Profile extends Model
{
    protected string $table = 'profiles';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }
}
