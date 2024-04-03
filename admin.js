function sendMessage() {
    const gujaratiInput = document.getElementById('gujaratiInput');
    const message = gujaratiInput.value.trim();

    if (message !== '') {
        // Simulate sending a message to the clients
        notifyClients(message);

        // Clear the input after sending
        gujaratiInput.value = '';
    }
}

function notifyClients(message) {
    // You can use any method to notify clients, such as WebSocket or AJAX
    // For simplicity, I'll use localStorage as a communication method
    const existingMessages = localStorage.getItem('messages') || '[]';
    const messages = JSON.parse(existingMessages);

    // Add the new message to the top of the messages array
    messages.unshift({ text: message, timestamp: new Date().toLocaleString() });

    // Save the updated messages back to localStorage
    localStorage.setItem('messages', JSON.stringify(messages));
}
