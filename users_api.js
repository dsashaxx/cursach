const form = document.getElementById('user-form');
const body = document.getElementById('users-body');
const msg = document.getElementById('form-msg');

async function loadUsers() {
    const response = await fetch('api/users.php');
    const users = await response.json();
    body.innerHTML = '';
    users.forEach(function(user) {
        body.innerHTML += `
            <tr>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.age ?? ''}</td>
            </tr>
        `;
    });
}
form.addEventListener('submit', async function(event) {
    event.preventDefault();
    const data = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        age: document.getElementById('age').value
    };
    const response = await fetch('api/users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    const result = await response.json();
    if (!response.ok) {
        msg.textContent = result.error || 'Ошибка';
        return;
    }
    msg.textContent = '';
    form.reset();
    loadUsers();
});
loadUsers();
