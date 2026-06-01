function setupNav() {
    const nav = document.getElementById('nav');
    if (!nav) return;

    if (window.USER_ROLE === 'admin') {
        nav.innerHTML = `
            <a href="index.php">Главная</a>
            <a href="parts.php">Детали</a>
            <a href="categories.php">Категории</a>
            <a href="suppliers.php">Поставщики</a>
            <a href="warehouses.php">Склады</a>
            <a href="movements.php">Движения</a>
            <a href="users_page.php">Users API</a>
            <button id="logout-btn">Выход</button>
        `;
    } else {
        nav.innerHTML = `
            <a href="index.php">Главная</a>
            <a href="catalog.php">Каталог</a>
            <button id="logout-btn">Выход</button>
        `;
    }
    document.getElementById('logout-btn').onclick = function () {
        location.href = 'logout.php';
    };
}
function setupWelcome() {
    const welcome = document.getElementById('welcome');
    if (!welcome) return;

    if (window.USER_ROLE === 'user') {
        welcome.textContent = 'Добро пожаловать в магазин деталей для ПК.';
    } else {
        welcome.textContent = 'Добро пожаловать в систему складского учета деталей ПК.';
    }
}
setupNav();
setupWelcome();
