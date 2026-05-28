const API = '/api';

const demoUsers = [
    { username: 'admin', password: 'admin', role: 'admin' },
    { username: 'user', password: 'user', role: 'user' }
];

function getLocalUsers() {
    return JSON.parse(localStorage.getItem('authUsers') || 'null') || demoUsers;
}

function saveLocalUsers(users) {
    localStorage.setItem('authUsers', JSON.stringify(users));
}

function currentUser() {
    return JSON.parse(localStorage.getItem('currentUser') || 'null');
}

function requireAuth() {
    if (!currentUser() && !location.pathname.endsWith('login.html')) {
        location.href = 'login.html';
    }
}

function isAdmin() {
    const user = currentUser();
    return user && user.role === 'admin';
}

function setupNav() {
    const nav = document.getElementById('nav');
    if (!nav) return;
    const user = currentUser();
    nav.innerHTML = `
        <a href="index.html">Главная</a>
        <a href="parts.html">Детали</a>
        <a href="categories.html">Категории</a>
        <a href="suppliers.html">Поставщики</a>
        <a href="warehouses.html">Склады</a>
        <a href="movements.html">Движения</a>
        <a href="users.html">Users API</a>
        <span>${user ? user.username + ' (' + user.role + ')' : ''}</span>
        <button id="logout-btn">Выход</button>
    `;
    document.getElementById('logout-btn').onclick = () => {
        localStorage.removeItem('currentUser');
        location.href = 'login.html';
    };
}

function showAdminElements() {
    document.querySelectorAll('.admin-only').forEach(el => el.style.display = isAdmin() ? 'flex' : 'none');
}

async function request(url, options = {}) {
    const res = await fetch(url, {
        headers: { 'Content-Type': 'application/json' },
        ...options
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        const msg = data.message || (data.errors && data.errors[0].msg) || 'Ошибка запроса';
        throw new Error(msg);
    }
    return data;
}

function initLogin() {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;

    loginForm.onsubmit = (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const user = getLocalUsers().find(u => u.username === username && u.password === password);
        if (!user) {
            document.getElementById('error-msg').textContent = 'Неверный логин или пароль';
            return;
        }
        localStorage.setItem('currentUser', JSON.stringify({ username: user.username, role: user.role }));
        location.href = 'index.html';
    };

    document.getElementById('register-form').onsubmit = (e) => {
        e.preventDefault();
        const username = document.getElementById('reg-username').value.trim();
        const password = document.getElementById('reg-password').value;
        const users = getLocalUsers();
        if (users.some(u => u.username === username)) {
            document.getElementById('reg-msg').textContent = 'Такой пользователь уже существует';
            return;
        }
        users.push({ username, password, role: 'user' });
        saveLocalUsers(users);
        document.getElementById('reg-msg').textContent = 'Пользователь зарегистрирован. Теперь можно войти.';
    };
}

function initIndex() {
    const welcome = document.getElementById('welcome');
    if (welcome) {
        const user = currentUser();
        welcome.textContent = user ? `Добро пожаловать, ${user.username}! Ваша роль: ${user.role}.` : '';
    }
}

function actionsHtml(id) {
    if (!isAdmin()) return '';
    return `<div class="row-actions"><button data-edit="${id}">Изменить</button><button class="danger" data-delete="${id}">Удалить</button></div>`;
}

function openDialog() { document.getElementById('form-dialog').showModal(); }
function closeDialog() { document.getElementById('form-dialog').close(); }

async function initCategories() {
    const tbody = document.getElementById('categories-tbody');
    if (!tbody) return;
    const form = document.getElementById('category-form');
    const msg = document.getElementById('form-msg');

    async function load() {
        const rows = await request(`${API}/categories`);
        tbody.innerHTML = rows.map(r => `<tr><td>${r.id}</td><td>${r.name}</td><td>${r.description || ''}</td><td>${actionsHtml(r.id)}</td></tr>`).join('');
        tbody.querySelectorAll('[data-edit]').forEach(b => b.onclick = () => edit(rows.find(r => r.id == b.dataset.edit)));
        tbody.querySelectorAll('[data-delete]').forEach(b => b.onclick = () => remove(b.dataset.delete));
    }
    function edit(r) { document.getElementById('category-id').value = r.id; document.getElementById('category-name').value = r.name; document.getElementById('category-description').value = r.description || ''; openDialog(); }
    async function remove(id) { if (confirm('Удалить запись?')) { await request(`${API}/categories/${id}`, { method: 'DELETE' }); load(); } }
    document.getElementById('add-btn')?.addEventListener('click', () => { form.reset(); document.getElementById('category-id').value = ''; openDialog(); });
    document.getElementById('cancel-btn').onclick = closeDialog;
    form.onsubmit = async e => { e.preventDefault(); try { const id = document.getElementById('category-id').value; const body = JSON.stringify({ name: document.getElementById('category-name').value, description: document.getElementById('category-description').value }); await request(`${API}/categories${id ? '/' + id : ''}`, { method: id ? 'PUT' : 'POST', body }); closeDialog(); load(); } catch (err) { msg.textContent = err.message; } };
    load();
}

async function initSuppliers() {
    const tbody = document.getElementById('suppliers-tbody');
    if (!tbody) return;
    const form = document.getElementById('supplier-form');
    async function load() { const rows = await request(`${API}/suppliers`); tbody.innerHTML = rows.map(r => `<tr><td>${r.id}</td><td>${r.name}</td><td>${r.phone || ''}</td><td>${r.email || ''}</td><td>${actionsHtml(r.id)}</td></tr>`).join(''); tbody.querySelectorAll('[data-edit]').forEach(b => b.onclick = () => edit(rows.find(r => r.id == b.dataset.edit))); tbody.querySelectorAll('[data-delete]').forEach(b => b.onclick = async () => { if (confirm('Удалить запись?')) { await request(`${API}/suppliers/${b.dataset.delete}`, { method: 'DELETE' }); load(); } }); }
    function edit(r) { ['id','name','phone','email','address'].forEach(f => document.getElementById('supplier-' + f).value = r[f] || ''); openDialog(); }
    document.getElementById('add-btn')?.addEventListener('click', () => { form.reset(); document.getElementById('supplier-id').value = ''; openDialog(); });
    document.getElementById('cancel-btn').onclick = closeDialog;
    form.onsubmit = async e => { e.preventDefault(); const id = document.getElementById('supplier-id').value; const data = { name: supplierName.value, phone: supplierPhone.value, email: supplierEmail.value, address: supplierAddress.value }; await request(`${API}/suppliers${id ? '/' + id : ''}`, { method: id ? 'PUT' : 'POST', body: JSON.stringify(data) }); closeDialog(); load(); };
    const supplierName = document.getElementById('supplier-name'), supplierPhone = document.getElementById('supplier-phone'), supplierEmail = document.getElementById('supplier-email'), supplierAddress = document.getElementById('supplier-address');
    load();
}

async function initWarehouses() {
    const tbody = document.getElementById('warehouses-tbody');
    if (!tbody) return;
    const form = document.getElementById('warehouse-form');
    async function load() { const rows = await request(`${API}/warehouses`); tbody.innerHTML = rows.map(r => `<tr><td>${r.id}</td><td>${r.name}</td><td>${r.address || ''}</td><td>${actionsHtml(r.id)}</td></tr>`).join(''); tbody.querySelectorAll('[data-edit]').forEach(b => b.onclick = () => { const r = rows.find(x => x.id == b.dataset.edit); warehouseId.value = r.id; warehouseName.value = r.name; warehouseAddress.value = r.address || ''; openDialog(); }); tbody.querySelectorAll('[data-delete]').forEach(b => b.onclick = async () => { if (confirm('Удалить запись?')) { await request(`${API}/warehouses/${b.dataset.delete}`, { method: 'DELETE' }); load(); } }); }
    const warehouseId = document.getElementById('warehouse-id'), warehouseName = document.getElementById('warehouse-name'), warehouseAddress = document.getElementById('warehouse-address');
    document.getElementById('add-btn')?.addEventListener('click', () => { form.reset(); warehouseId.value = ''; openDialog(); });
    document.getElementById('cancel-btn').onclick = closeDialog;
    form.onsubmit = async e => { e.preventDefault(); const id = warehouseId.value; await request(`${API}/warehouses${id ? '/' + id : ''}`, { method: id ? 'PUT' : 'POST', body: JSON.stringify({ name: warehouseName.value, address: warehouseAddress.value }) }); closeDialog(); load(); };
    load();
}

async function fillSelect(id, url, textField = 'name') {
    const select = document.getElementById(id);
    if (!select) return;
    const rows = await request(url);
    select.innerHTML = '<option value="">—</option>' + rows.map(r => `<option value="${r.id}">${r[textField]}</option>`).join('');
}

async function initParts() {
    const tbody = document.getElementById('parts-tbody');
    if (!tbody) return;
    const form = document.getElementById('part-form');
    await fillSelect('category-id', `${API}/categories`); await fillSelect('supplier-id', `${API}/suppliers`);
    async function load() { const rows = await request(`${API}/parts`); tbody.innerHTML = rows.map(r => `<tr><td>${r.id}</td><td>${r.name}</td><td>${r.partNumber}</td><td>${r.categoryName || ''}</td><td>${r.supplierName || ''}</td><td>${r.purchasePrice}</td><td>${r.stock}</td><td>${actionsHtml(r.id)}</td></tr>`).join(''); tbody.querySelectorAll('[data-edit]').forEach(b => b.onclick = () => edit(rows.find(r => r.id == b.dataset.edit))); tbody.querySelectorAll('[data-delete]').forEach(b => b.onclick = async () => { if (confirm('Удалить запись?')) { await request(`${API}/parts/${b.dataset.delete}`, { method: 'DELETE' }); load(); } }); }
    function edit(r) { partId.value = r.id; partName.value = r.name; partNumber.value = r.partNumber; categoryId.value = r.categoryId || ''; supplierId.value = r.supplierId || ''; purchasePrice.value = r.purchasePrice || 0; description.value = r.description || ''; openDialog(); }
    const partId = document.getElementById('part-id'), partName = document.getElementById('part-name'), partNumber = document.getElementById('part-number'), categoryId = document.getElementById('category-id'), supplierId = document.getElementById('supplier-id'), purchasePrice = document.getElementById('purchase-price'), description = document.getElementById('description');
    document.getElementById('add-btn')?.addEventListener('click', () => { form.reset(); partId.value = ''; openDialog(); });
    document.getElementById('cancel-btn').onclick = closeDialog;
    form.onsubmit = async e => { e.preventDefault(); const id = partId.value; const data = { name: partName.value, partNumber: partNumber.value, categoryId: categoryId.value, supplierId: supplierId.value, purchasePrice: purchasePrice.value, description: description.value }; await request(`${API}/parts${id ? '/' + id : ''}`, { method: id ? 'PUT' : 'POST', body: JSON.stringify(data) }); closeDialog(); load(); };
    load();
}

async function initMovements() {
    const tbody = document.getElementById('movements-tbody');
    if (!tbody) return;
    await fillSelect('part-id', `${API}/parts`); await fillSelect('warehouse-id', `${API}/warehouses`);
    async function load() { const rows = await request(`${API}/movements`); tbody.innerHTML = rows.map(r => `<tr><td>${r.id}</td><td>${r.partName || ''}</td><td>${r.warehouseName || ''}</td><td>${r.type}</td><td>${r.quantity}</td><td>${r.date}</td><td>${r.comment || ''}</td></tr>`).join(''); }
    document.getElementById('add-btn')?.addEventListener('click', () => { document.getElementById('movement-form').reset(); openDialog(); });
    document.getElementById('cancel-btn').onclick = closeDialog;
    document.getElementById('movement-form').onsubmit = async e => { e.preventDefault(); const data = { partId: document.getElementById('part-id').value, warehouseId: document.getElementById('warehouse-id').value, type: document.getElementById('movement-type').value, quantity: document.getElementById('quantity').value, comment: document.getElementById('comment').value }; await request(`${API}/movements`, { method: 'POST', body: JSON.stringify(data) }); closeDialog(); load(); };
    load();
}

async function initUsersApi() {
    const tbody = document.getElementById('users-tbody');
    if (!tbody) return;
    async function load() { const rows = await request('/users'); tbody.innerHTML = rows.map(u => `<tr><td>${u.id}</td><td>${u.name}</td><td>${u.email}</td><td>${u.age || ''}</td></tr>`).join(''); }
    document.getElementById('user-form').onsubmit = async e => { e.preventDefault(); const data = { name: document.getElementById('user-name').value, email: document.getElementById('user-email').value, age: document.getElementById('user-age').value }; try { await request('/users', { method: 'POST', body: JSON.stringify(data) }); document.getElementById('user-form').reset(); document.getElementById('form-msg').textContent = ''; load(); } catch (err) { document.getElementById('form-msg').textContent = err.message; } };
    load();
}

requireAuth();
initLogin();
setupNav();
showAdminElements();
initIndex();
initCategories();
initSuppliers();
initWarehouses();
initParts();
initMovements();
initUsersApi();
