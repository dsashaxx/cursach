const API = 'api/data.php';

function getValue(id) {
    const el = document.getElementById(id);
    return el ? el.value : '';
}
function setValue(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value ?? '';
}
function showMessage(text) {
    const msg = document.getElementById('form-msg');
    if (msg) msg.textContent = text || '';
}
function openDialog() {
    showMessage('');
    document.getElementById('form-dialog').showModal();
}
function closeDialog() {
    document.getElementById('form-dialog').close();
}
async function request(url, options = {}) {
    const response = await fetch(url, {
        headers: { 'Content-Type': 'application/json' },
        ...options
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
        throw new Error(data.error || 'Ошибка запроса');
    }
    return data;
}
function actionsHtml(id) {
    if (window.USER_ROLE !== 'admin') return '';
    return `
        <div class="row-actions">
            <button data-edit="${id}">Изменить</button>
            <button class="danger" data-delete="${id}">Удалить</button>
        </div>
    `;
}

function initCategories() {
    const tbody = document.getElementById('categories-body');
    if (!tbody) return;
    const form = document.getElementById('category-form');
    async function load() {
        const rows = await request(API + '?type=categories');
        tbody.innerHTML = rows.map(r => `
            <tr>
                <td>${r.id}</td>
                <td>${r.name}</td>
                <td>${r.description || ''}</td>
                <td>${actionsHtml(r.id)}</td>
            </tr>
        `).join('');
        tbody.querySelectorAll('[data-edit]').forEach(btn => {
            btn.onclick = () => {
                const row = rows.find(r => r.id == btn.dataset.edit);
                setValue('category-id', row.id);
                setValue('category-name', row.name);
                setValue('category-description', row.description);
                openDialog();
            };
        });
        tbody.querySelectorAll('[data-delete]').forEach(btn => {
            btn.onclick = async () => {
                if (confirm('Удалить запись?')) {
                    await request(API + '?type=categories&id=' + btn.dataset.delete, { method: 'DELETE' });
                    load();
                }
            };
        });
    }
    document.getElementById('add-btn').onclick = () => {
        form.reset();
        setValue('category-id', '');
        openDialog();
    };
    document.getElementById('cancel-btn').onclick = closeDialog;
    form.onsubmit = async (event) => {
        event.preventDefault();
        const id = getValue('category-id');
        const body = {
            id: id,
            name: getValue('category-name'),
            description: getValue('category-description')
        };
        try {
            await request(API + '?type=categories', {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(body)
            });
            closeDialog();
            load();
        } catch (e) {
            showMessage(e.message);
        }
    };
    load();
}
function initSuppliers() {
    const tbody = document.getElementById('suppliers-body');
    if (!tbody) return;

    const form = document.getElementById('supplier-form');

    async function load() {
        const rows = await request(API + '?type=suppliers');

        tbody.innerHTML = rows.map(r => `
            <tr>
                <td>${r.id}</td>
                <td>${r.name}</td>
                <td>${r.phone || ''}</td>
                <td>${r.email || ''}</td>
                <td>${actionsHtml(r.id)}</td>
            </tr>
        `).join('');

        tbody.querySelectorAll('[data-edit]').forEach(btn => {
            btn.onclick = () => {
                const row = rows.find(r => r.id == btn.dataset.edit);
                setValue('supplier-id', row.id);
                setValue('supplier-name', row.name);
                setValue('supplier-phone', row.phone);
                setValue('supplier-email', row.email);
                setValue('supplier-address', row.address);
                openDialog();
            };
        });

        tbody.querySelectorAll('[data-delete]').forEach(btn => {
            btn.onclick = async () => {
                if (confirm('Удалить запись?')) {
                    await request(API + '?type=suppliers&id=' + btn.dataset.delete, { method: 'DELETE' });
                    load();
                }
            };
        });
    }

    document.getElementById('add-btn').onclick = () => {
        form.reset();
        setValue('supplier-id', '');
        openDialog();
    };

    document.getElementById('cancel-btn').onclick = closeDialog;

    form.onsubmit = async (event) => {
        event.preventDefault();
        const id = getValue('supplier-id');
        const body = {
            id: id,
            name: getValue('supplier-name'),
            phone: getValue('supplier-phone'),
            email: getValue('supplier-email'),
            address: getValue('supplier-address')
        };

        try {
            await request(API + '?type=suppliers', {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(body)
            });
            closeDialog();
            load();
        } catch (e) {
            showMessage(e.message);
        }
    };

    load();
}

function initWarehouses() {
    const tbody = document.getElementById('warehouses-body');
    if (!tbody) return;

    const form = document.getElementById('warehouse-form');

    async function load() {
        const rows = await request(API + '?type=warehouses');

        tbody.innerHTML = rows.map(r => `
            <tr>
                <td>${r.id}</td>
                <td>${r.name}</td>
                <td>${r.address || ''}</td>
                <td>${actionsHtml(r.id)}</td>
            </tr>
        `).join('');

        tbody.querySelectorAll('[data-edit]').forEach(btn => {
            btn.onclick = () => {
                const row = rows.find(r => r.id == btn.dataset.edit);
                setValue('warehouse-id', row.id);
                setValue('warehouse-name', row.name);
                setValue('warehouse-address', row.address);
                openDialog();
            };
        });

        tbody.querySelectorAll('[data-delete]').forEach(btn => {
            btn.onclick = async () => {
                if (confirm('Удалить запись?')) {
                    await request(API + '?type=warehouses&id=' + btn.dataset.delete, { method: 'DELETE' });
                    load();
                }
            };
        });
    }

    document.getElementById('add-btn').onclick = () => {
        form.reset();
        setValue('warehouse-id', '');
        openDialog();
    };

    document.getElementById('cancel-btn').onclick = closeDialog;

    form.onsubmit = async (event) => {
        event.preventDefault();
        const id = getValue('warehouse-id');
        const body = {
            id: id,
            name: getValue('warehouse-name'),
            address: getValue('warehouse-address')
        };

        try {
            await request(API + '?type=warehouses', {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(body)
            });
            closeDialog();
            load();
        } catch (e) {
            showMessage(e.message);
        }
    };

    load();
}

function initParts() {
    const tbody = document.getElementById('parts-body');
    if (!tbody) return;

    const form = document.getElementById('part-form');

    async function load() {
        const rows = await request(API + '?type=parts');

        tbody.innerHTML = rows.map(r => `
            <tr>
                <td>${r.id}</td>
                <td>${r.name}</td>
                <td>${r.number}</td>
                <td>${r.category || ''}</td>
                <td>${r.supplier || ''}</td>
                <td>${r.price}</td>
                <td>${r.stock}</td>
                <td>${actionsHtml(r.id)}</td>
            </tr>
        `).join('');

        tbody.querySelectorAll('[data-edit]').forEach(btn => {
            btn.onclick = () => {
                const row = rows.find(r => r.id == btn.dataset.edit);
                setValue('part-id', row.id);
                setValue('part-name', row.name);
                setValue('part-number', row.number);
                setValue('part-category', row.category);
                setValue('part-supplier', row.supplier);
                setValue('part-price', row.price);
                setValue('part-stock', row.stock);
                setValue('part-description', row.description);
                openDialog();
            };
        });

        tbody.querySelectorAll('[data-delete]').forEach(btn => {
            btn.onclick = async () => {
                if (confirm('Удалить запись?')) {
                    await request(API + '?type=parts&id=' + btn.dataset.delete, { method: 'DELETE' });
                    load();
                }
            };
        });
    }

    document.getElementById('add-btn').onclick = () => {
        form.reset();
        setValue('part-id', '');
        openDialog();
    };

    document.getElementById('cancel-btn').onclick = closeDialog;

    form.onsubmit = async (event) => {
        event.preventDefault();
        const id = getValue('part-id');
        const body = {
            id: id,
            name: getValue('part-name'),
            number: getValue('part-number'),
            category: getValue('part-category'),
            supplier: getValue('part-supplier'),
            price: getValue('part-price'),
            stock: getValue('part-stock'),
            description: getValue('part-description')
        };

        try {
            await request(API + '?type=parts', {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(body)
            });
            closeDialog();
            load();
        } catch (e) {
            showMessage(e.message);
        }
    };

    load();
}

function initMovements() {
    const tbody = document.getElementById('movements-body');
    if (!tbody) return;

    const form = document.getElementById('movement-form');

    async function load() {
        const rows = await request(API + '?type=movements');

        tbody.innerHTML = rows.map(r => `
            <tr>
                <td>${r.id}</td>
                <td>${r.part || ''}</td>
                <td>${r.warehouse || ''}</td>
                <td>${r.type}</td>
                <td>${r.quantity}</td>
                <td>${r.date}</td>
                <td>${r.comment || ''}</td>
                <td>${actionsHtml(r.id)}</td>
            </tr>
        `).join('');

        tbody.querySelectorAll('[data-edit]').forEach(btn => {
            btn.onclick = () => {
                const row = rows.find(r => r.id == btn.dataset.edit);
                setValue('movement-id', row.id);
                setValue('movement-part', row.part);
                setValue('movement-warehouse', row.warehouse);
                setValue('movement-type', row.type);
                setValue('movement-quantity', row.quantity);
                setValue('movement-comment', row.comment);
                openDialog();
            };
        });

        tbody.querySelectorAll('[data-delete]').forEach(btn => {
            btn.onclick = async () => {
                if (confirm('Удалить запись?')) {
                    await request(API + '?type=movements&id=' + btn.dataset.delete, { method: 'DELETE' });
                    load();
                }
            };
        });
    }

    document.getElementById('add-btn').onclick = () => {
        form.reset();
        setValue('movement-id', '');
        openDialog();
    };

    document.getElementById('cancel-btn').onclick = closeDialog;

    form.onsubmit = async (event) => {
        event.preventDefault();
        const id = getValue('movement-id');
        const body = {
            id: id,
            part: getValue('movement-part'),
            warehouse: getValue('movement-warehouse'),
            type: getValue('movement-type'),
            quantity: getValue('movement-quantity'),
            comment: getValue('movement-comment')
        };

        try {
            await request(API + '?type=movements', {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(body)
            });
            closeDialog();
            load();
        } catch (e) {
            showMessage(e.message);
        }
    };

    load();
}

initCategories();
initSuppliers();
initWarehouses();
initParts();
initMovements();
