function updateMessages() {
    // Retrieve messages from localStorage
    const existingMessages = localStorage.getItem('messages') || '[]';
    const messages = JSON.parse(existingMessages);

    // Update the message list in the client panel
    const messageList = document.getElementById('messageList');
    messageList.innerHTML = '';
    messages.forEach(message => {
        const listItem = document.createElement('li');
        listItem.classList.add('message');
        listItem.textContent = `${message.text} - ${message.timestamp}`;
        messageList.appendChild(listItem);
    });
}

// Initial update when the page loads
updateMessages();

// You can use WebSocket or other methods to update in real-time
// For simplicity, let's update every 5 seconds
setInterval(updateMessages, 5000);

function resetMessages() {
    const messageContainer = document.getElementById('chatMessages');
    
    // Clear all messages in the chat
    messageContainer.innerHTML = '';

    // Clear local storage
    localStorage.removeItem('messages');
}
