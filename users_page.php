<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Users API</title>
    <link rel="stylesheet" href="style.css">
    <script src="users_api.js" defer></script>
</head>
<body>
<header>
    <h1>Складской учет деталей ПК</h1>
    <nav id="nav"></nav>
</header>
<main>
<section class="card">
    <h2>Пользователи API</h2>
    <form id="user-form">
        <label>Имя<input id="name" required></label>
        <label>Email<input id="email" type="email" required></label>
        <label>Возраст<input id="age" type="number"></label>
        <p id="form-msg" class="error"></p>
        <button type="submit">Добавить</button>
    </form>

    <table>
        <thead><tr><th>ID</th><th>Имя</th><th>Email</th><th>Возраст</th></tr></thead>
        <tbody id="users-body"></tbody>
    </table>
</section>
</main>
<script>window.USER_ROLE = "<?= $_SESSION['role'] ?>";</script>
<script src="script.js"></script>
</body>
</html>
