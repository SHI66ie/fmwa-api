<?php
// Get the current page name for active navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!-- Navigation Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.html">
            <img alt="Federal Ministry of Women Affairs Logo" class="fmwa-logo" src="images/2025_07_14_13_42_IMG_2808.PNG" style="height: 50px;">
            <span class="logo-divider"></span>
            <span class="fmwa-navbar-title">FEDERAL MINISTRY OF WOMEN AFFAIRS</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="about.html">About Us</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="departmentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Departments
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="departmentsDropdown">
                        <li><a class="dropdown-item" href="departments/child-development.php">Child Development</a></li>
                        <li><a class="dropdown-item" href="departments/community-development-social-intervention.php">Community Development & Social Intervention</a></li>
                        <li><a class="dropdown-item" href="departments/finance-accounting.php">Finance & Accounting</a></li>
                        <li><a class="dropdown-item" href="departments/gender-affairs.php">Gender Affairs</a></li>
                        <li><a class="dropdown-item" href="departments/general-services.php">General Services</a></li>
                        <li><a class="dropdown-item" href="departments/nutrition.php">Nutrition</a></li>
                        <li><a class="dropdown-item" href="departments/planning-research-statistics.php">Planning, Research & Statistics</a></li>
                        <li><a class="dropdown-item" href="departments/procurement.php">Procurement</a></li>
                        <li><a class="dropdown-item" href="departments/reform-coordination-service-improvement.php">Reform Coordination & Service Improvement</a></li>
                        <li><a class="dropdown-item" href="departments/women-development.php">Women Development</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'mandate') ? 'active' : ''; ?>" href="mandate.html">Mandate</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
