document.getElementById('login-button').addEventListener('click', function() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const users = JSON.parse(localStorage.getItem('users')) || {};
    const messageElement = document.getElementById('error-message');

    if (users[username] && users[username] === password) {
        window.location.href = "index.html";
    } else {
        messageElement.innerText = 'Usuario o contraseñas incorrectas. ¡Ingrese bien sus credenciales!';
    }
});
