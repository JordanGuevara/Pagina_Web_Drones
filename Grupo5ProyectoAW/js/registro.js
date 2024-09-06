document.getElementById('register-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('php/registro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageElement = document.getElementById('message');
        if (data.status === 'error') {
            messageElement.textContent = data.message;
            messageElement.style.color = 'red';
        } else if (data.status === 'success') {
            messageElement.textContent = data.message;
            messageElement.style.color = 'green';
        }
    })
    .catch(error => console.error('Error:', error));
});
