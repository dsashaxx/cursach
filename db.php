<?php

$host = 'localhost';
$port = '5434';
$dbname = 'warehouse_db';
$user = 'postgres';
$password = '123456';
try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}