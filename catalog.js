function addToCart(name, price) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    cart.push({
        name: name,
        price: price
    });
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    alert('Товар добавлен в корзину');
}
function loadCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let body = document.getElementById('cart-body');
    let total = 0;
    if (!body) return;
    body.innerHTML = '';
    if (cart.length === 0) {
        body.innerHTML = `
            <tr>
                <td colspan="2">Корзина пуста</td>
            </tr>
        `;
        document.getElementById('total').textContent = 'Итого: 0 ₽';
        return;
    }
    cart.forEach(function(item) {
        total += item.price;
        body.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.price} ₽</td>
            </tr>
        `;
    });
    document.getElementById('total').textContent = 'Итого: ' + total + ' ₽';
}
function checkout() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Корзина пуста. Добавьте товары.');
        return;
    }
    alert('Заказ успешно оформлен');
    localStorage.removeItem('cart');
    loadCart();
}
loadCart();
