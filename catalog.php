<?php require_once 'check_auth.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Магазин деталей</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Магазин деталей</h1>
    <nav id="nav"></nav>
</header>

<main>
<section class="card">
    <h2>Каталог товаров</h2>

    <div class="catalog">
        <div class="product">
            <img src="images/cpu1.jpg" alt="Intel Core i5">
            <h3>Intel Core i5</h3><p>Процессор</p>
            <p>25 000 ₽</p><button onclick="addToCart('Intel Core i5', 25000)">В корзину</button>
        </div>
        <div class="product">
            <img src="images/cpu2.jpg" alt="AMD Ryzen 5">
            <h3>AMD Ryzen 5 5600</h3><p>Процессор</p><p>18 000 ₽</p>
            <button onclick="addToCart('AMD Ryzen 5 5600', 18000)">В корзину</button></div>
        <div class="product">
            <img src="images/gpu1.jpg" alt="RTX 4060">
            <h3>RTX 4060</h3><p>Видеокарта</p><p>45 000 ₽</p>
            <button onclick="addToCart('RTX 4060', 45000)">В корзину</button></div>
        <div class="product">
            <img src="images/gpu2.jpg" alt="RTX 4070">
            <h3>RTX 4070</h3><p>Видеокарта</p><p>68 000 ₽</p>
            <button onclick="addToCart('RTX 4070', 68000)">В корзину</button></div>
        <div class="product">
            <img src="images/ram1.jpg" alt="Kingston Fury 16GB">
            <h3>Kingston Fury 16GB</h3><p>Оперативная память</p><p>4 500 ₽</p>
            <button onclick="addToCart('Kingston Fury 16GB', 4500)">В корзину</button></div>
        <div class="product">
            <img src="images/ram2.jpg" alt="Corsair Vengeance 32GB">
            <h3>Corsair Vengeance 32GB</h3><p>Оперативная память</p><p>9 800 ₽</p>
            <button onclick="addToCart('Corsair Vengeance 32GB', 9800)">В корзину</button></div>
        <div class="product">
            <img src="images/motherboard1.jpg" alt="ASUS PRIME B550M">
            <h3>ASUS PRIME B550M</h3><p>Материнская плата</p><p>11 000 ₽</p>
            <button onclick="addToCart('ASUS PRIME B550M', 11000)">В корзину</button></div>
        <div class="product">
            <img src="images/motherboard2.jpg" alt="MSI B760 Gaming">
            <h3>MSI B760 Gaming</h3><p>Материнская плата</p><p>14 500 ₽</p>
            <button onclick="addToCart('MSI B760 Gaming', 14500)">В корзину</button></div>
        <div class="product">
            <img src="images/ssd1.jpg" alt="Kingston NV2 1TB">
            <h3>Kingston NV2 1TB</h3><p>SSD накопитель</p><p>5 200 ₽</p>
            <button onclick="addToCart('Kingston NV2 1TB', 5200)">В корзину</button></div>
        <div class="product">
            <img src="images/ssd2.jpg" alt="Samsung 980 PRO 1TB">
            <h3>Samsung 980 PRO 1TB</h3><p>SSD накопитель</p><p>8 900 ₽</p>
            <button onclick="addToCart('Samsung 980 PRO 1TB', 8900)">В корзину</button></div>
        <div class="product">
            <img src="images/psu1.jpg" alt="DeepCool 650W">
            <h3>DeepCool 650W</h3><p>Блок питания</p><p>5 500 ₽</p>
            <button onclick="addToCart('DeepCool 650W', 5500)">В корзину</button></div>
        <div class="product">
            <img src="images/psu2.jpg" alt="Chieftec 750W">
            <h3>Chieftec 750W</h3><p>Блок питания</p><p>7 800 ₽</p>
            <button onclick="addToCart('Chieftec 750W', 7800)">В корзину</button></div>
        <div class="product">
            <img src="images/case.jpg" alt="Zalman S2">
            <h3>Zalman S2</h3><p>Корпус</p><p>4 200 ₽</p>
            <button onclick="addToCart('Zalman S2', 4200)">В корзину</button></div>
        <div class="product">
            <img src="images/cooler.jpg" alt="DeepCool GAMMAXX 400">
            <h3>DeepCool GAMMAXX 400</h3><p>Кулер</p><p>2 300 ₽</p>
            <button onclick="addToCart('DeepCool GAMMAXX 400', 2300)">В корзину</button></div>
        <div class="product">
            <img src="images/hdd.jpg" alt="Seagate Barracuda 2TB">
            <h3>Seagate Barracuda 2TB</h3><p>Жесткий диск</p><p>6 100 ₽</p>
            <button onclick="addToCart('Seagate Barracuda 2TB', 6100)">В корзину</button></div>
    </div>

    <hr>

    <h2>Корзина</h2>
    <table>
        <thead><tr><th>Товар</th><th>Цена</th></tr></thead>
        <tbody id="cart-body"></tbody>
    </table>

    <h3 id="total">Итого: 0 ₽</h3>
    <button onclick="checkout()">Оформить заказ</button>
</section>
</main>
<script>window.USER_ROLE = "<?= $_SESSION['role'] ?>";</script>
<script src="script.js"></script>
<script src="catalog.js"></script>
</body>
</html>
