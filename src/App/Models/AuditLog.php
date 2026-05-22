<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class AuditLog extends Model
{
    protected string $table = 'audit_logs';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }
}
