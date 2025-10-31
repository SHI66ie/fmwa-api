<?php
// Common head section for all pages
$site_title = isset($page_title) ? $page_title : 'Federal Ministry of Women Affairs';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/2025_07_14_13_42_IMG_2808.PNG">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/department-navigation.css">
    <link rel="stylesheet" href="css/logo-position-fix.css">
    <link rel="stylesheet" href="css/welcome-banner.css">
    <link rel="stylesheet" href="css/visitor-counter.css">
    
    <!-- Force logo size -->
    <style>
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
