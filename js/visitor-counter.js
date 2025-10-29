// Visitor Counter JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Simple visitor counter implementation
    function updateVisitorCount() {
        // Get or initialize visitor count from localStorage
        let count = localStorage.getItem('visitorCount') || 0;
        count = parseInt(count) + 1;
        localStorage.setItem('visitorCount', count);
        
        // Find visitor counter element and update it
        const counterElement = document.querySelector('.visitor-count');
        if (counterElement) {
            counterElement.textContent = count.toLocaleString();
        }
    }
    
    // Update visitor count on page load
    updateVisitorCount();
});
