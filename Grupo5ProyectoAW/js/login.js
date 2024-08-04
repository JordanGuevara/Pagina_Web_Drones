function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const users = {
        'alex': '123',
        'ax': '456'
    };

    if (users[username] && users[username] === password) {
        window.location.href = "index.html";

    } else {
        document.getElementById('error-message').innerText = 'Usuario o contraseña incorrectos.';
    }
}