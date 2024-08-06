document.addEventListener('DOMContentLoaded', function() {
    const sendMessageButton = document.getElementById('sendMessage');
    const messageInput = document.getElementById('messageInput');
    const chatBody = document.getElementById('chatBody');
    function addMessage(text, isUser) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message');
        if (isUser) {
            messageDiv.classList.add('user');
        } else {
            messageDiv.classList.add('system');
        }
        messageDiv.innerHTML = `
            ${!isUser ? '<img src="img/icono.png" alt="Avatar" class="avatar">' : ''}
            <div class="message">${text}</div>
        `;
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    sendMessageButton.addEventListener('click', function() {
        const messageText = messageInput.value.trim();
        if (messageText) {
            addMessage(messageText, true); 
            messageInput.value = '';
            setTimeout(() => {
                addMessage('Respondiendo...', false);
            }, 1000); 
        }
    });

    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessageButton.click();
        }
    });
});