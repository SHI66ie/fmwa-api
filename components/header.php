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
        
        /* Director Photo Styles */
        .director-photo-container {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            border-radius: 50%;
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .director-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .director-photo:hover {
            transform: scale(1.05);
        }
        
        .contact-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .contact-details p {
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .contact-details i {
            width: 20px;
        }
        
        .director-placeholder {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #013a04 0%, #025a06 100%);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .director-placeholder .placeholder-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 10px;
            text-align: center;
            margin-top: 5px;
            line-height: 1.2;
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
            
            .director-photo-container {
                width: 100px;
                height: 100px;
            }
            
            .contact-details {
                padding: 12px;
            }
            
            .director-placeholder {
                width: 100px;
                height: 100px;
            }
            
            .director-placeholder .fa-3x {
                font-size: 2rem;
            }
            
            .director-placeholder .placeholder-text {
                font-size: 9px;
            }
        }
        
        /* Force logo to be 100px on all department pages */
        .fmwa-logo {
            height: 100px !important;
            width: auto !important;
        }
        
        .logo-divider {
            height: 80px !important;
        }
        
        @media (max-width: 768px) {
            .fmwa-logo {
                height: 70px !important;
            }
            .logo-divider {
                height: 60px !important;
            }
        }
        
        @media (max-width: 576px) {
            .fmwa-logo {
                height: 60px !important;
            }
            .logo-divider {
                height: 50px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
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
                        <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="../about.html">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="departmentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Departments
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="departmentsDropdown">
                            <li><a class="dropdown-item <?php echo ($current_page == 'child-development') ? 'active' : ''; ?>" href="child-development.php">Child Development</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'community-development-social-intervention') ? 'active' : ''; ?>" href="community-development-social-intervention.php">Community Development & Social Intervention</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'economic-services') ? 'active' : ''; ?>" href="economic-services.php">Economic Services</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'finance-accounting') ? 'active' : ''; ?>" href="finance-accounting.php">Finance & Accounting</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'gender-affairs') ? 'active' : ''; ?>" href="gender-affairs.php">Gender Affairs</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'general-services') ? 'active' : ''; ?>" href="general-services.php">General Services</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'human-resource-management') ? 'active' : ''; ?>" href="human-resource-management.php">Human Resource Management</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'nutrition') ? 'active' : ''; ?>" href="nutrition.php">Nutrition</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'planning-research-statistics') ? 'active' : ''; ?>" href="planning-research-statistics.php">Planning, Research & Statistics</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'procurement') ? 'active' : ''; ?>" href="procurement.php">Procurement</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'reform-coordination-service-improvement') ? 'active' : ''; ?>" href="reform-coordination-service-improvement.php">Reform Coordination & Service Improvement</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'special-duties') ? 'active' : ''; ?>" href="special-duties.php">Special Duties</a></li>
                            <li><a class="dropdown-item <?php echo ($current_page == 'women-development') ? 'active' : ''; ?>" href="women-development.php">Women Development</a></li>
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
