// Admin Interface Utilities

// Copy to clipboard function with visual feedback
window.copyToClipboard = function(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        const originalContent = button.innerHTML;
        button.innerHTML = '✓ Copied!';
        button.disabled = true;
        
        setTimeout(function() {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy to clipboard');
    });
};

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+S to save (prevent default browser save)
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const saveButton = document.querySelector('button[type="submit"]');
        if (saveButton) {
            saveButton.click();
        }
    }
});