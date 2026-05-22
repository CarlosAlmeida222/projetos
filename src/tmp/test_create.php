<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Core\Application;
use App\Models\User;

$app = new Application($pdo);
$userModel = new User($app->getDatabase());

try {
    $id = $userModel->create(['name' => 'Dup CLI', 'email' => 'user013@example.com', 'password' => 'secret123', 'status' => 'active']);
    echo "OK:$id\n";
} catch (Throwable $e) {
    echo 'ERR:'.$e->getMessage()."\n";
}
