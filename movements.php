<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Движения товара</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Складской учет деталей ПК</h1>
    <nav id="nav"></nav>
</header>
<main>
<section class="card">
    <h2>Движения товара</h2>
    <div class="toolbar"><button id="add-btn">Добавить операцию</button></div>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Деталь</th><th>Склад</th><th>Тип</th>
                <th>Количество</th><th>Дата</th><th>Комментарий</th><th></th>
            </tr>
        </thead>
        <tbody id="movements-body"></tbody>
    </table>
</section>
</main>
<dialog id="form-dialog">
    <form id="movement-form">
        <h3>Операция</h3>
        <input type="hidden" id="movement-id">
        <label>Деталь<input id="movement-part" placeholder="например: Intel Core i5" required></label>
        <label>Склад<input id="movement-warehouse" placeholder="например: Главный склад" required></label>
        <label>Тип
            <select id="movement-type">
                <option value="Приход">Приход</option>
                <option value="Расход">Расход</option>
            </select>
        </label>
        <label>Количество<input id="movement-quantity" type="number" min="1" required></label>
        <label>Комментарий<textarea id="movement-comment"></textarea></label>
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
