<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Core\Application;
use App\Models\User;

$application = new Application($pdo);
$userModel = new User($application->getDatabase());

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

function jsonResponse($data, int $code = 200): void
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function parseJsonBody(): array
{
    $body = file_get_contents('php://input');
    if (empty($body)) {
        return [];
    }

    $data = json_decode($body, true);
    return is_array($data) ? $data : [];
}

if ($uri === '' || $uri === '/') {
    require_once __DIR__ . '/../views/public/home.php';
    exit;
}

if ($uri === '/admin' || $uri === '/admin/users') {
    require_once __DIR__ . '/../views/admin/users.php';
    exit;
}

if (preg_match('#^/api/users(?:/([0-9]+))?$#', $uri, $matches)) {
    $id = isset($matches[1]) ? (int) $matches[1] : null;

    if ($method === 'GET') {
        if ($id !== null) {
            $user = $userModel->findById($id);
            if (!$user) {
                jsonResponse(['error' => 'Usuário não encontrado'], 404);
            }
            jsonResponse(['data' => $user]);
        }

        $search = trim((string)($_GET['search'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = max(1, min(50, (int)($_GET['perPage'] ?? 10)));

        $result = $userModel->paginate($page, $perPage, $search);
        jsonResponse($result);
    }

    if ($method === 'POST' && $id === null) {
        try {
            $payload = parseJsonBody();
            $userId = $userModel->create($payload);
            $user = $userModel->findById($userId);
            jsonResponse(['message' => 'Usuário criado com sucesso', 'data' => $user], 201);
        } catch (PDOException $e) {
            $msg = 'Erro ao criar usuário';
            $code = 400;
            $errorInfo = $e->errorInfo;
            if (isset($errorInfo[1]) && $errorInfo[1] === 1062) {
                $msg = 'E-mail já cadastrado';
                $code = 422;
            } else {
                $msg = $e->getMessage();
            }
            jsonResponse(['error' => $msg, 'details' => $e->getMessage()], $code);
        } catch (\Throwable $e) {
            jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    if ($id !== null && ($method === 'PUT' || $method === 'PATCH')) {
        try {
            $payload = parseJsonBody();
            $updated = $userModel->update($id, $payload);
            if (!$updated) {
                jsonResponse(['error' => 'Não foi possível atualizar o usuário'], 422);
            }

            $user = $userModel->findById($id);
            jsonResponse(['message' => 'Usuário atualizado com sucesso', 'data' => $user]);
        } catch (PDOException $e) {
            $msg = 'Erro ao atualizar usuário';
            $code = 400;
            $errorInfo = $e->errorInfo;
            if (isset($errorInfo[1]) && $errorInfo[1] === 1062) {
                $msg = 'E-mail já cadastrado';
                $code = 422;
            } else {
                $msg = $e->getMessage();
            }
            jsonResponse(['error' => $msg, 'details' => $e->getMessage()], $code);
        } catch (\Throwable $e) {
            jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    if ($id !== null && $method === 'DELETE') {
        try {
            $deleted = $userModel->delete($id);
            if (!$deleted) {
                jsonResponse(['error' => 'Não foi possível excluir o usuário'], 422);
            }
            jsonResponse(['message' => 'Usuário excluído com sucesso']);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Erro ao excluir usuário', 'details' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    jsonResponse(['error' => 'Método não permitido'], 405);
}

http_response_code(404);
echo '<h1>404 - Página não encontrada</h1>';
