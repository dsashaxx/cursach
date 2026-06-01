<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поставщики</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Складской учет деталей ПК</h1>
    <nav id="nav"></nav>
</header>
<main>
<section class="card">
    <h2>Поставщики</h2>
    <div class="toolbar"><button id="add-btn">Добавить поставщика</button></div>
    <table>
        <thead><tr><th>ID</th><th>Название</th><th>Телефон</th><th>Email</th><th></th></tr></thead>
        <tbody id="suppliers-body"></tbody>
    </table>
</section>
</main>
<dialog id="form-dialog">
    <form id="supplier-form">
        <h3>Поставщик</h3>
        <input type="hidden" id="supplier-id">
        <label>Название<input id="supplier-name" required></label>
        <label>Телефон<input id="supplier-phone"></label>
        <label>Email<input id="supplier-email" type="email"></label>
        <label>Адрес<textarea id="supplier-address"></textarea></label>
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
