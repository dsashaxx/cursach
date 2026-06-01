<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали ПК</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Складской учет деталей ПК</h1>
    <nav id="nav"></nav>
</header>
<main>
<section class="card">
    <h2>Детали ПК</h2>
    <div class="toolbar"><button id="add-btn">Добавить деталь</button></div>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Название</th><th>Артикул</th><th>Категория</th>
                <th>Поставщик</th><th>Цена</th><th>Остаток</th><th></th>
            </tr>
        </thead>
        <tbody id="parts-body"></tbody>
    </table>
</section>
</main>
<dialog id="form-dialog">
    <form id="part-form">
        <h3>Деталь</h3>
        <input type="hidden" id="part-id">
        <label>Название<input id="part-name" required></label>
        <label>Артикул<input id="part-number" required></label>
        <label>Категория<input id="part-category" placeholder="например: Процессоры"></label>
        <label>Поставщик<input id="part-supplier" placeholder="например: DNS"></label>
        <label>Цена<input id="part-price" type="number" min="0" step="0.01" required></label>
        <label>Остаток<input id="part-stock" type="number" min="0" required></label>
        <label>Описание<textarea id="part-description"></textarea></label>
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
