const chatIcon = document.getElementById('ai-chat-icon');
const chatModal = document.getElementById('ai-chat-modal');
const chatCloseBtn = document.getElementById('chat-close-btn');
const chatBody = document.getElementById('chat-body');
const chatInput = document.getElementById('chat-input');
const chatSendBtn = document.getElementById('chat-send-btn');
const faqButtons = document.querySelectorAll('.faq-btn');

// 1. Toggle Chat Modal Open/Close
chatIcon.addEventListener('click', () => {
    chatModal.classList.toggle('open');
});

chatCloseBtn.addEventListener('click', () => {
    chatModal.classList.remove('open');
});

// 2. Handle FAQ Button Clicks (Questions & Answers display)
faqButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const questionText = btn.textContent;
        const answerText = btn.getAttribute('data-answer');

        // Append User Question
        appendMessage(questionText, 'user-msg');

        // Remove quick questions temporarily so chat stays clean
        document.querySelector('.quick-questions').style.display = 'none';

        // Simulate AI typing response after a short delay
        setTimeout(() => {
            appendMessage(answerText, 'bot-msg');
            
            // Show quick questions again after answering if needed
            setTimeout(() => {
                document.querySelector('.quick-questions').style.display = 'flex';
            }, 1000);
        }, 500);
    });
});

// 3. Handle Text Input Sending
chatSendBtn.addEventListener('click', sendUserMessage);
chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendUserMessage();
    }
});

function sendUserMessage() {
    const text = chatInput.value.trim();
    if (text === '') return;

    appendMessage(text, 'user-msg');
    chatInput.value = '';

    // Simple automatic bot reply logic
    setTimeout(() => {
        let reply = "Thanks for reaching out! You can select one of the quick questions above or contact our team via WhatsApp.";
        if (text.toLowerCase().includes('price') || text.toLowerCase().includes('cost')) {
            reply = "Our pricing varies based on the project requirements. Feel free to reach out for a custom quote!";
        }
        appendMessage(reply, 'bot-msg');
    }, 600);
}

// Helper function to add messages to chat body
function appendMessage(text, className) {
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('chat-message', className);
    msgDiv.textContent = text;
    chatBody.appendChild(msgDiv);
    
    // Auto scroll to bottom
    chatBody.scrollTop = chatBody.scrollHeight;
}