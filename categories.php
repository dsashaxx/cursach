<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Категории</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Складской учет деталей ПК</h1>
    <nav id="nav"></nav>
</header>
<main>
<section class="card">
    <h2>Категории</h2>
    <div class="toolbar"><button id="add-btn">Добавить категорию</button></div>
    <table>
        <thead><tr><th>ID</th><th>Название</th><th>Описание</th><th></th></tr></thead>
        <tbody id="categories-body"></tbody>
    </table>
</section>
</main>
<dialog id="form-dialog">
    <form id="category-form">
        <h3>Категория</h3>
        <input type="hidden" id="category-id">
        <label>Название<input id="category-name" required></label>
        <label>Описание<textarea id="category-description"></textarea></label>
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
