<?php
// Get the current page name for active navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Federal Ministry of Women Affairs'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/2025_07_14_13_42_IMG_2808.PNG">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/department-navigation.css">
    <link rel="stylesheet" href="../css/logo-position-fix.css">
    <link rel="stylesheet" href="../css/welcome-banner.css">
    
    <style>
        /* Department page specific styles */
        body {
            padding-top: 80px;
        }
        
        .department-header {
            background: linear-gradient(135deg, #013a04 0%, #025a06 100%);
            color: white;
            padding: 60px 0 40px 0;
            margin-top: 0;
        }
        
        .department-header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .department-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }
        
        .department-header .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .department-header .breadcrumb-item.active {
            color: #ffc107;
        }
        
        .department-content {
            padding: 40px 0;
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .department-header {
                padding: 40px 0 30px 0;
            }
            
            .department-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="../index.html">
                <img alt="Federal Ministry of Women Affairs Logo" class="fmwa-logo" src="../images/2025_07_14_13_42_IMG_2808.PNG" style="height: 50px;">
                <span class="logo-divider"></span>
                <span class="fmwa-navbar-title">FEDERAL MINISTRY OF WOMEN AFFAIRS</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="../index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="../about.html">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="departmentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Departments
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="departmentsDropdown">
                            <li><a class="dropdown-item <?php echo ($current_page == 'child-development') ? 'active' : ''; ?>" href="./departments/child-development.php">Child Development</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'community-development-social-intervention') ? 'active' : ''; ?>" href="./departments/community-development-social-intervention.php">Community Development & Social Intervention</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'finance-accounting') ? 'active' : ''; ?>" href="./departments/finance-accounting.php">Finance & Accounting</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'gender-affairs') ? 'active' : ''; ?>" href="./departments/gender-affairs.php">Gender Affairs</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'general-services') ? 'active' : ''; ?>" href="./departments/general-services.php">General Services</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'nutrition') ? 'active' : ''; ?>" href="./departments/nutrition.php">Nutrition</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'planning-research-statistics') ? 'active' : ''; ?>" href="./departments/planning-research-statistics.php">Planning, Research & Statistics</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'procurement') ? 'active' : ''; ?>" href="./departments/procurement.php">Procurement</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'reform-coordination-service-improvement') ? 'active' : ''; ?>" href="./departments/reform-coordination-service-improvement.php">Reform Coordination & Service Improvement</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'women-development') ? 'active' : ''; ?>" href="./departments/women-development.php">Women Development</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'mandate') ? 'active' : ''; ?>" href="../mandate.html">Mandate</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Maintenance Notice (if needed) -->
    <?php if (isset($show_maintenance_notice) && $show_maintenance_notice): ?>
    <div class="maintenance-banner bg-warning text-dark py-2" style="margin-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <div class="scrolling-text">
                        <i class="fas fa-tools"></i>
                        <strong>Notice:</strong> This website is currently under maintenance. Some features may be temporarily unavailable. We apologize for any inconvenience.
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
