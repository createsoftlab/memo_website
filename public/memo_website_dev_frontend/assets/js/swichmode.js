const themeButtons = document.querySelectorAll('.theme-btn');
const body = document.body;

// 1. Check saved theme on page load
const savedTheme = localStorage.getItem('theme') || 'light';
if (savedTheme === 'dark') {
    body.classList.add('dark-mode');
    updateActiveButton('dark');
} else {
    body.classList.remove('dark-mode');
    updateActiveButton('light');
}

// 2. Click event for switcher buttons
themeButtons.forEach(button => {
    button.addEventListener('click', () => {
        const selectedMode = button.getAttribute('data-mode');
        
        if (selectedMode === 'dark') {
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
        
        updateActiveButton(selectedMode);
    });
});

// Helper function to update the highlighted button
function updateActiveButton(mode) {
    themeButtons.forEach(btn => {
        if (btn.getAttribute('data-mode') === mode) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}