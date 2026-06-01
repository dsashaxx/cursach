<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><?= $_SESSION['role'] === 'user' ? 'Магазин деталей' : 'Складской учет деталей ПК' ?></h1>
    <nav id="nav"></nav>
</header>
<main>
    <section class="card">
        <h2>Главная страница</h2>
        <p id="welcome"></p>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <p>Используйте меню для перехода к деталям, складам, справочникам и движениям товара.</p>
        <?php else: ?>
            <p>Перейдите в каталог, чтобы выбрать товары и оформить заказ.</p>
        <?php endif; ?>
    </section>
</main>

<script>window.USER_ROLE = "<?= $_SESSION['role'] ?>";</script>
<script src="script.js"></script>
</body>
</html>
