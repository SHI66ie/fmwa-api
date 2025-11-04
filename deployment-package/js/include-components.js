// Include Components JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Basic header HTML structure
    const headerHTML = `
        <nav class="navbar navbar-expand-lg navbar-dark main-nav px-0">
            <div class="container-fluid gx-0">
                <a class="navbar-brand d-flex align-items-center me-0" href="./">
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
                            <a class="nav-link active" href="./" title="Go to home page">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./about.html" title="Learn about the ministry">About Us</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="departmentsDropdown" role="button" title="Explore our departments">
                                Departments
                            </a>
                            <ul aria-labelledby="departmentsDropdown" class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="./departments/child-development" title="Child Development Department">
                                    Child Development
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/community-development-social-intervention" title="Community Development & Social Intervention Department">
                                    Community Development & Social Intervention
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/economic-services" title="Economic Services Department">
                                    Economic Services
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/finance-accounting" title="Finance & Accounting Department">
                                    Finance & Accounting
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/gender-affairs" title="Gender Affairs Department">
                                    Gender Affairs
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/general-services" title="General Services Department">
                                    General Services
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/human-resource-management" title="Human Resource Management Department">
                                    Human Resource Management
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/nutrition" title="Nutrition Department">
                                    Nutrition
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/planning-research-statistics" title="Planning, Research & Statistics Department">
                                    Planning, Research & Statistics
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/procurement" title="Procurement Department">
                                    Procurement
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/reform-coordination-service-improvement" title="Reform Coordination & Service Improvement Department">
                                    Reform Coordination & Service Improvement
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/special-duties" title="Special Duties Department">
                                    Special Duties
                                </a></li>
                                <li><a class="dropdown-item" href="./departments/women-development" title="Women Development Department">
                                    Women Development
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="servicesDropdown" role="button" title="Our services">
                                Services
                            </a>
                            <ul aria-labelledby="servicesDropdown" class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="https://happywoman.ng/" title="Happy Woman Program" target="_blank" rel="noopener">
                                    Happy Woman
                                </a></li>
                                <li><a class="dropdown-item" href="https://www.spesse.edu.ng/" title="SPESSE Program" target="_blank" rel="noopener">
                                    SPESSE
                                </a></li>
                                <li><a class="dropdown-item" href="https://webmail.womenaffairs.gov.ng/" title="Mail Services" target="_blank" rel="noopener">
                                    Mail
                                </a></li>
                            </ul>
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
    
    // Initialize Bootstrap dropdowns
    setTimeout(() => {
        // Initialize dropdown functionality for both dropdowns
        const departmentsDropdown = document.querySelector('#departmentsDropdown');
        const servicesDropdown = document.querySelector('#servicesDropdown');
        
        if (departmentsDropdown && typeof bootstrap !== 'undefined') {
            new bootstrap.Dropdown(departmentsDropdown);
        }
        
        if (servicesDropdown && typeof bootstrap !== 'undefined') {
            new bootstrap.Dropdown(servicesDropdown);
        }
        
        // Handle hover behavior for desktop
        const dropdown = document.querySelector('.dropdown');
        if (dropdown && window.innerWidth >= 992) {
            dropdown.addEventListener('mouseenter', function() {
                const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdownInstance) {
                    dropdownInstance.show();
                }
            });
            
            dropdown.addEventListener('mouseleave', function() {
                const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
            });
        }
    }, 100);
    
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
