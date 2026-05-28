const loginTab = document.getElementById('login-tab');
const registerTab = document.getElementById('register-tab');

const loginSection = document.getElementById('login-section');
const registerSection = document.getElementById('register-section');

loginTab.addEventListener('click', function () {
    loginSection.classList.remove('hidden');
    registerSection.classList.add('hidden');
});

registerTab.addEventListener('click', function () {
    registerSection.classList.remove('hidden');
    loginSection.classList.add('hidden');
});