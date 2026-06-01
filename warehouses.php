<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Склады</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Складской учет деталей ПК</h1>
    <nav id="nav"></nav>
</header>
<main>
<section class="card">
    <h2>Склады</h2>
    <div class="toolbar"><button id="add-btn">Добавить склад</button></div>
    <table>
        <thead><tr><th>ID</th><th>Название</th><th>Адрес</th><th></th></tr></thead>
        <tbody id="warehouses-body"></tbody>
    </table>
</section>
</main>
<dialog id="form-dialog">
    <form id="warehouse-form">
        <h3>Склад</h3>
        <input type="hidden" id="warehouse-id">
        <label>Название<input id="warehouse-name" required></label>
        <label>Адрес<textarea id="warehouse-address"></textarea></label>
        <p id="form-msg" class="error"></p>
        <button type="submit">Сохранить</button>
        <button type="button" id="cancel-btn">Отмена</button>
    </form>
</dialog>
<script>window.USER_ROLE = "<?= $_SESSION['role'] ?>";</script>
<script src="script.js"></script>
<script src="admin.js"></script>
</body>
</html>
