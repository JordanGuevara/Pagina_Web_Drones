document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const username = document.getElementById('nombre').value;
    const correo = document.getElementById('correo').value;
    const pass = document.getElementById('pass').value;
    const repass = document.getElementById('repass').value;

    const messageElement = document.getElementById('message');

    if (!username || !correo || !pass || !repass) {
        messageElement.innerText = 'Por favor, complete todos los campos.';
        return;
    }

    if (pass !== repass) {
        messageElement.innerText = 'Las contraseñas no coinciden.';
        return;
    }

    const users = JSON.parse(localStorage.getItem('users')) || {};

    if (users[correo]) {
        messageElement.innerText = 'El correo ya está registrado.';
    } else {
        users[correo] = pass;
        localStorage.setItem('users', JSON.stringify(users));
        messageElement.innerText = 'Registro exitoso. Puedes iniciar sesión.';
        document.getElementById('register-form').reset();
    }
});
