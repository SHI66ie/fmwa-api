<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Federal Ministry of Women Affairs</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/2025_07_14_13_42_IMG_2808.PNG">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Load optimized CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/department-navigation.css">
    <link rel="stylesheet" href="css/logo-position-fix.css?v=1.1">
    <link rel="stylesheet" href="css/welcome-banner.css">
    <link rel="stylesheet" href="css/visitor-counter.css">
    
    <!-- Profile Image Fix -->
    <style>
        .profile-image-container {
            width: 200px;
            height: 250px;
            margin: 0 auto 20px auto;
            overflow: hidden;
            border-radius: 8px;
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        /* Ensure both images are the same size */
        .profile-image-container.minister .profile-image,
        .profile-image-container .profile-image {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        
        /* Force logo to be 100px */
        .fmwa-logo {
            height: 100px !important;
            width: auto !important;
        }
        
        .logo-divider {
            height: 80px !important;
            background-color: white !important;
        }
        
        /* Green header with white text */
        .navbar {
            background: linear-gradient(135deg, #013a04 0%, #025a06 100%) !important;
        }
        
        .navbar-brand .fmwa-navbar-title {
            color: white !important;
        }
        
        .nav-link {
            color: white !important;
        }
        
        .nav-link:hover {
            color: #ffc107 !important;
        }
        
        .navbar-toggler-icon {
            filter: invert(1);
        }
        
        /* Compact dropdown styling */
        .dropdown-menu {
            min-width: 200px !important;
            padding: 0.25rem 0 !important;
            font-size: 0.875rem !important;
            border-radius: 6px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
        }
        
        .dropdown-item {
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.4 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #025a06 !important;
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
    
    <!-- Include components -->
    <script src="js/include-components.js" defer></script>
    <script src="js/visitor-counter.js" defer></script>
    
    <!-- Preload critical images -->
    <link rel="preload" href="images/2025_07_14_13_42_IMG_2808.PNG" as="image">
</head>
<body>
    <!-- Header will be inserted here by components/header.js -->
    
    <!-- Maintenance Notice Section -->
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
    
    <!-- Hero Section -->
    <section class="hero-section py-5">
        <div class="container">
            <div id="heroCarousel" class="carousel slide">
                <div class="carousel-inner" aria-live="polite">
                    <div class="carousel-item active">
                        <div class="image-container d-flex justify-content-center">
                            <img src="images/2025_07_11_16_37_IMG_2804.JPG" alt="FMWA Event" class="img-fluid me-2 carousel-image">
                            <img src="images/2025_07_11_16_39_IMG_2805.JPG" alt="FMWA Event" class="img-fluid carousel-image">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-container d-flex justify-content-center">
                            <img src="images/2025_07_11_16_40_IMG_2806.JPG" alt="FMWA Event" class="img-fluid me-2 carousel-image">
                            <img src="images/2025_07_11_16_37_IMG_2804.JPG" alt="FMWA Event" class="img-fluid carousel-image">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-container d-flex justify-content-center">
                            <img src="images/2025_07_11_16_39_IMG_2805.JPG" alt="FMWA Event" class="img-fluid me-2 carousel-image">
                            <img src="images/2025_07_11_16_40_IMG_2806.JPG" alt="FMWA Event" class="img-fluid carousel-image">
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="leadership-section py-5">
        <?php
        // Leadership data
        $leadership = [
            'minister' => [
                'name' => 'Hajiya Imaan Sulaiman-Ibrahim',
                'title' => 'Honourable Minister',
                'image' => 'images/imaan_sulaiman.jpg',
                'description' => 'Hajiya Imaan Sulaiman-Ibrahim is the Honourable Minister of Women Affairs, appointed to oversee the affairs of the Federal Ministry of Women Affairs. With a strong background in public service and a passion for women\'s empowerment, she is committed to advancing gender equality, protecting women\'s rights, and implementing policies that promote the welfare of women and children across Nigeria.'
            ],
            'permanent_secretary' => [
                'name' => 'Dr. Maryam Ismaila Keshinro',
                'title' => 'Permanent Secretary',
                'image' => 'images/2025_07_11_16_37_IMG_2803.JPG',
                'description' => 'Dr. Maryam Ismaila Keshinro serves as the Permanent Secretary of the Federal Ministry of Women Affairs, bringing extensive experience in public administration and policy implementation. She works closely with the Honourable Minister to ensure the effective execution of policies and programs that advance gender equality, women\'s empowerment, and the protection of women\'s and children\'s rights across Nigeria.'
            ]
        ];
        ?>
        <div class="container">
            <h2 class="section-title text-center mb-5">Leadership</h2>
            
            <div class="row justify-content-center">
                <!-- Minister -->
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="profile-image-container minister mx-auto">
                                <img src="<?php echo $leadership['minister']['image']; ?>" class="profile-image" alt="<?php echo $leadership['minister']['name']; ?>">
                            </div>
                            <h5 class="text-muted mb-2"><?php echo $leadership['minister']['title']; ?></h5>
                            <h4 class="mb-3"><?php echo $leadership['minister']['name']; ?></h4>
                            <p class="card-text"><?php echo $leadership['minister']['description']; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Permanent Secretary -->
                <div class="col-md-5">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="profile-image-container mx-auto">
                                <img src="<?php echo $leadership['permanent_secretary']['image']; ?>" class="profile-image" alt="<?php echo $leadership['permanent_secretary']['name']; ?>">
                            </div>
                            <h5 class="text-muted mb-2"><?php echo $leadership['permanent_secretary']['title']; ?></h5>
                            <h4 class="mb-3"><?php echo $leadership['permanent_secretary']['name']; ?></h4>
                            <p class="card-text"><?php echo $leadership['permanent_secretary']['description']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about-section" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div id="mandate-section" class="feature-box">
                        <h3><i class="fas fa-landmark me-2"></i>Our Mandate</h3>
                        <p>The broad mandate of the Ministry is to advise government on Gender and Children issues. Issues affecting Persons with Disabilities and the Aged; initiate policy guidelines and lead the process of gender equality and mainstreaming at both the National and International levels.</p>
                        
                        <a href="mandate.php" class="read-more">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div id="vision-section" class="feature-box">
                        <h3><i class="fas fa-eye me-2"></i>Our Vision</h3>
                        <p>To help build a Nigerian Society that guarantees equal access to social, economic and wealth creation opportunities to all, irrespective of gender, places premium on protection of the child, the aged and persons with disabilities; focuses attention of key operators in both private and public sectors on mainstreaming the concerns of these groups of people in national development process.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div id="mission-section" class="feature-box">
                        <h3><i class="fas fa-bullseye me-2"></i>Our Mission</h3>
                        <p>To help build a Nigerian Society that guarantees equal access to social, economic and wealth creation opportunities to all, irrespective of gender, places premium on protection of the child, the aged and persons with disabilities; focuses attention of key operators in both private and public sectors on mainstreaming the concerns of these groups of people in national development process.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="feature-box">
                        <h3><i class="fas fa-users me-2"></i>Who We Are</h3>
                        <p>Federal Ministry of Women Affairs and Social Development was created consequent upon of the response to The United Nations agreement to establish Institutional Mechanisms for the...</p>
                        <a href="about.php" class="read-more" target="_blank" rel="noopener noreferrer">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links Panel -->
    <section class="quick-links-panel py-4 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="bg-white rounded shadow-sm overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/bcVwzskL0PI?si=CDbKSdK6RGx4ZHRj" 
                                    title="Latest Announcements Video" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-100">
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded shadow-sm overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/DDfjkhRuLKA?si=ik_A9beQqziIKpy_" 
                                    title="Upcoming Events Video" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-100">
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded shadow-sm overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/eq7B3EjqYuU?si=ZrPFc7pMlsN_BekL" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    referrerpolicy="strict-origin-when-cross-origin" 
                                    allowfullscreen 
                                    class="w-100">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Updates -->
    <section class="news-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title">Latest News & Updates</h2>
                    <div class="news-horizontal-container">
                        <!-- ACTU Committee Inauguration -->
                        <div class="news-item-horizontal">
                            <div class="news-item">
                                <img src="latest-news/IMG-20250731-WA0019.jpg" loading="lazy" alt="ACTU Committee Inauguration" class="img-fluid news-image">
                                <span class="news-date"><i class="far fa-calendar-alt me-2"></i>July 31, 2025</span>
                                <h4 class="news-title">FMWA Inaugurates ACTU Committee to Bolster Transparency and Ethical Governance</h4>
                                <p>In a landmark move toward institutional integrity, the Federal Ministry of Women Affairs inaugurated its Anti-Corruption and Transparency Unit (ACTU) Committee.</p>
                                <a href="press-release-actu-inauguration.php" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <!-- Press Statement - Super Falcons WAFCON Victory -->
                        <div class="news-item-horizontal">
                            <div class="news-item">
                                <img src="latest-news/2025-07-28at12.08.39PM.jpeg" loading="lazy" alt="Super Falcons WAFCON Victory" class="img-fluid news-image">
                                <span class="news-date"><i class="far fa-calendar-alt me-2"></i>July 28, 2025</span>
                                <h4 class="news-title">Women Affairs Minister Congratulates Super Falcons on Historic 10th WAFCON Victory</h4>
                                <p>The Honourable Minister of Women Affairs, Hon. Imaan Sulaiman Ibrahim, congratulates Nigeria's Super Falcons on their record-breaking victory.</p>
                                <a href="press-statement-super-falcons.php" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <!-- Empty tray for upcoming news -->
                        <div class="news-item-horizontal">
                            <div class="news-item">
                                <div class="news-placeholder" style="height: 200px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                    <i class="far fa-newspaper fa-3x mb-3"></i>
                                    <p>More News Coming Soon</p>
                                </div>
                                <h4 class="news-title mt-3">Stay Tuned for Updates</h4>
                                <p>Check back later for the latest news and updates from the Federal Ministry of Women Affairs.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Downloads Section -->
    <section class="downloads-section py-3 bg-light">
        <div class="container">
            <h2 class="section-title">Publications & Downloads</h2>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-pdf fa-2x mb-2"></i>
                            <h5>Public Service Rules</h5>
                            <a href="/downloadables/PSR_2021_EDITION.pdf" class="btn btn-outline-primary btn-sm" download>Download PDF</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-word fa-2x mb-2"></i>
                            <h5>Annual Report 2024</h5>
                            <p class="text-muted small mb-2">Published: January 2025</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Download DOC</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                            <h5>Performance Data</h5>
                            <p class="text-muted small">Q2 2025</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Download XLS</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-file-archive fa-3x text-warning mb-3"></i>
                            <h5>Policy Documents</h5>
                            <p class="text-muted small">Collection (ZIP)</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Download ZIP</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="#" class="btn btn-success">View All Publications</a>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-5 text-white stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="display-4 fw-bold">50+</div>
                    <p>MDAs Served</p>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="display-4 fw-bold">150K+</div>
                    <p>Public Servants</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="display-4 fw-bold">24/7</div>
                    <p>Service Delivery</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="display-4 fw-bold">100+</div>
                    <p>Training Programs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">Federal Ministry of Women Affairs</h5>
                    <p class="text-light">Empowering women and promoting gender equality across Nigeria through comprehensive policies, programs, and initiatives that advance the welfare of women and children.</p>
                    <div class="social-links mt-3">
                        <a href="https://www.facebook.com/FMWAngr" class="text-light me-3" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/FMWA_ng" class="text-light me-3" title="X (Twitter)" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/FMWAngr" class="text-light me-3" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="http://www.youtube.com/@fmwangr" class="text-light" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="mandate.php" class="text-muted text-decoration-none">Our Mandate</a></li>
                        <li class="mb-2"><a href="organogram.php" class="text-muted text-decoration-none">Organogram</a></li>
                    </ul>
                </div>
                
                <!-- Departments -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Key Departments</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="departments/women-development.php" class="text-muted text-decoration-none">Women Development</a></li>
                        <li class="mb-2"><a href="departments/child-development.php" class="text-muted text-decoration-none">Child Development</a></li>
                        <li class="mb-2"><a href="departments/gender-affairs.php" class="text-muted text-decoration-none">Gender Affairs</a></li>
                        <li class="mb-2"><a href="departments/nutrition.php" class="text-muted text-decoration-none">Nutrition</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <div class="contact-info">
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Federal Secretariat Complex<br>
                            <span class="ms-4">Shehu Shagari Way, Abuja</span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2"></i>
                            +234-9-461-0000
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            info@fmwa.gov.ng
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Mon - Fri: 8:00 AM - 4:00 PM
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom border-top border-secondary pt-4 mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">
                            &copy; 2025 Federal Ministry of Women Affairs. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-muted text-decoration-none me-3">Terms of Service</a>
                        <a href="#" class="text-muted text-decoration-none">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
            crossorigin="anonymous" 
            defer></script>
    
    <!-- Custom Scripts -->
    <script>
    // Initialize components
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any existing offcanvas backdrops
        document.querySelectorAll('.offcanvas-backdrop').forEach(el => el.remove());
        
        // Add smooth scrolling to all links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
    </script>
    
    <!-- Initialize components with defer -->
    <script defer>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.forEach(function (popoverTriggerEl) {
                new bootstrap.Popover(popoverTriggerEl);
            });
        }
    });
    </script>

    <!-- Visitor Counter Display -->
    <div id="visitor-counter"></div>

</body>
</html>
