<?php

require_once __DIR__ . '/../controllers/UserController.php';
$controller = new UserController();
$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? 'users';
$id = $_GET['id'] ?? null;
if ($resource === 'users' && $method === 'GET' && $id === null) {
    $controller->getAll();
}
if ($resource === 'users' && $method === 'GET' && $id !== null) {
    $controller->getById($id);
}

if ($resource === 'users' && $method === 'POST') {
    $controller->create();
}
http_response_code(405);
echo json_encode(['error' => 'Метод не разрешен'], JSON_UNESCAPED_UNICODE);
