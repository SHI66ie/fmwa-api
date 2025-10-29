// Include Components JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Basic header HTML structure
    const headerHTML = `
        <nav class="navbar navbar-expand-lg navbar-dark main-nav px-0">
            <div class="container-fluid gx-0">
                <a class="navbar-brand d-flex align-items-center me-0" href="./index.html">
                    <img alt="Federal Ministry of Women Affairs Logo" class="fmwa-logo" src="./images/2025_07_14_13_42_IMG_2808.PNG" style="height: 50px;">
                    <span class="logo-divider"></span>
                    <span class="fmwa-navbar-title">FEDERAL MINISTRY OF WOMEN AFFAIRS</span>
                </a>
                <button aria-expanded="false" class="navbar-toggler" data-bs-target="#navbarNav" data-bs-toggle="collapse" title="Toggle navigation menu" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.html" title="Go to home page">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./about.html" title="Learn about the ministry">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./mandate.html" title="Our mandate">Mandate</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    `;
    
    // Insert header at the beginning of body
    document.body.insertAdjacentHTML('afterbegin', headerHTML);
    
    // Initialize scrolling text animation
    const scrollingText = document.querySelector('.scrolling-text');
    if (scrollingText) {
        // Make the text visible immediately
        scrollingText.style.display = 'inline-block';
        
        // Start animation after a short delay
        setTimeout(() => {
            scrollingText.classList.add('animate');
        }, 100);
    }
});
