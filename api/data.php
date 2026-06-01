<?php

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Нет доступа'], JSON_UNESCAPED_UNICODE);
    exit;
}
require_once __DIR__ . '/../db.php';
$type = $_GET['type'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

function answer($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function adminOnly() {
    if (($_SESSION['role'] ?? '') !== 'admin') {
        answer(['error' => 'Только для администратора'], 403);
    }
}
function required($input, $field) {
    if (!isset($input[$field]) || trim((string)$input[$field]) === '') {
        answer(['error' => 'Поле ' . $field . ' обязательно'], 400);
    }
}

function getIdByName($pdo, $table, $name) {
    if ($name === null || trim($name) === '') return null;
    $stmt = $pdo->prepare("SELECT id FROM $table WHERE name = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id'] : null;
}

try {
    if ($method === 'GET') {
        if ($type === 'categories') {
            answer($pdo->query('SELECT id, name, description FROM categories ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
        }
        if ($type === 'suppliers') {
            answer($pdo->query('SELECT id, name, phone, email, address FROM suppliers ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
        }
        if ($type === 'warehouses') {
            answer($pdo->query('SELECT id, name, address FROM warehouses ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
        }
        if ($type === 'parts') {
            answer($pdo->query('SELECT * FROM v_parts_full ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
        }
        if ($type === 'movements') {
            answer($pdo->query('SELECT * FROM v_movements_full ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
        }
    }

    adminOnly();
    if ($type === 'categories') {
        if ($method === 'POST') {
            required($input, 'name');
            $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
            $stmt->execute([$input['name'], $input['description'] ?? null]);
        }
        if ($method === 'PUT') {
            required($input, 'id');
            required($input, 'name');
            $stmt = $pdo->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?');
            $stmt->execute([$input['name'], $input['description'] ?? null, $input['id']]);
        }
        if ($method === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
            $stmt->execute([$_GET['id'] ?? 0]);
        }
        answer(['success' => true]);
    }
    if ($type === 'suppliers') {
        if ($method === 'POST') {
            required($input, 'name');
            if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                answer(['error' => 'Некорректный email'], 400);
            }
            $stmt = $pdo->prepare('INSERT INTO suppliers (name, phone, email, address) VALUES (?, ?, ?, ?)');
            $stmt->execute([$input['name'], $input['phone'] ?? null, $input['email'] ?? null, $input['address'] ?? null]);
        }
        if ($method === 'PUT') {
            required($input, 'id');
            required($input, 'name');
            $stmt = $pdo->prepare('UPDATE suppliers SET name = ?, phone = ?, email = ?, address = ? WHERE id = ?');
            $stmt->execute([$input['name'], $input['phone'] ?? null, $input['email'] ?? null, $input['address'] ?? null, $input['id']]);
        }
        if ($method === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM suppliers WHERE id = ?');
            $stmt->execute([$_GET['id'] ?? 0]);
        }
        answer(['success' => true]);
    }

    if ($type === 'warehouses') {
        if ($method === 'POST') {
            required($input, 'name');
            $stmt = $pdo->prepare('INSERT INTO warehouses (name, address) VALUES (?, ?)');
            $stmt->execute([$input['name'], $input['address'] ?? null]);
        }
        if ($method === 'PUT') {
            required($input, 'id');
            required($input, 'name');
            $stmt = $pdo->prepare('UPDATE warehouses SET name = ?, address = ? WHERE id = ?');
            $stmt->execute([$input['name'], $input['address'] ?? null, $input['id']]);
        }
        if ($method === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM warehouses WHERE id = ?');
            $stmt->execute([$_GET['id'] ?? 0]);
        }
        answer(['success' => true]);
    }

    if ($type === 'parts') {
        if ($method === 'POST' || $method === 'PUT') {
            required($input, 'name');
            required($input, 'number');
            $categoryId = getIdByName($pdo, 'categories', $input['category'] ?? '');
            $supplierId = getIdByName($pdo, 'suppliers', $input['supplier'] ?? '');
        }
        if ($method === 'POST') {
            $stmt = $pdo->prepare('INSERT INTO parts (name, number, category_id, supplier_id, price, stock, description) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$input['name'], $input['number'], $categoryId, $supplierId, $input['price'] ?? 0, $input['stock'] ?? 0, $input['description'] ?? null]);
        }
        if ($method === 'PUT') {
            required($input, 'id');
            $stmt = $pdo->prepare('UPDATE parts SET name = ?, number = ?, category_id = ?, supplier_id = ?, price = ?, stock = ?, description = ? WHERE id = ?');
            $stmt->execute([$input['name'], $input['number'], $categoryId, $supplierId, $input['price'] ?? 0, $input['stock'] ?? 0, $input['description'] ?? null, $input['id']]);
        }
        if ($method === 'DELETE') {
            $stmt = $pdo->prepare('CALL sp_delete_part(?)');
            $stmt->execute([$_GET['id'] ?? 0]);
        }
        answer(['success' => true]);
    }

    if ($type === 'movements') {
        if ($method === 'POST' || $method === 'PUT') {
            required($input, 'part');
            required($input, 'warehouse');
            $partId = getIdByName($pdo, 'parts', $input['part']);
            $warehouseId = getIdByName($pdo, 'warehouses', $input['warehouse']);
        }
        if ($method === 'POST') {
            $stmt = $pdo->prepare('CALL sp_add_movement(?, ?, ?, ?, ?)');
            $stmt->execute([$partId, $warehouseId, $input['type'] ?? 'Приход', $input['quantity'] ?? 1, $input['comment'] ?? null]);
        }
        if ($method === 'PUT') {
            required($input, 'id');
            $stmt = $pdo->prepare('UPDATE movements SET part_id = ?, warehouse_id = ?, type = ?, quantity = ?, date = ?, comment = ? WHERE id = ?');
            $stmt->execute([$partId, $warehouseId, $input['type'] ?? 'Приход', $input['quantity'] ?? 1, $input['date'] ?? date('Y-m-d'), $input['comment'] ?? null, $input['id']]);
        }
        if ($method === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM movements WHERE id = ?');
            $stmt->execute([$_GET['id'] ?? 0]);
        }
        answer(['success' => true]);
    }
    answer(['error' => 'Неверный тип данных'], 400);
} catch (Exception $e) {
    answer(['error' => $e->getMessage()], 400);
}
