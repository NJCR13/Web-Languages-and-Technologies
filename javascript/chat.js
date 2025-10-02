document.addEventListener('DOMContentLoaded', () => {
  const chatBox = document.querySelector('.chat-box');
  const form = document.querySelector('.chat-form');
  const textarea = form.querySelector('textarea');

  if (chatBox) {
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const message = textarea.value.trim();
    if (message === '') {
      alert('Please write a message before sending.');
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) throw new Error('Failed to send message');

      const result = await response.json();
      if (result.success) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('chat-message', 'sent');

        const messageText = document.createElement('p');
        messageText.classList.add('message-text');
        messageText.textContent = message;

        const messageDate = document.createElement('span');
        messageDate.classList.add('message-date');
        messageDate.textContent = result.date || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        messageDiv.appendChild(messageText);
        messageDiv.appendChild(messageDate);
        chatBox.appendChild(messageDiv);

        textarea.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;
      } else {
        alert('Could not send message.');
      }
    } catch (error) {
      console.error('Error:', error);
      alert('An error occurred while sending the message.');
    }
  });
});