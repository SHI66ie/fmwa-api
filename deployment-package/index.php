<?php
// Homepage configuration
$page_title = "Federal Ministry of Women Affairs";
$show_maintenance_notice = true; // Show maintenance notice on homepage
?>
<?php include 'components/head.php'; ?>
<body>
    <?php include 'components/navigation.php'; ?>

    <!-- Maintenance Notice -->
    <?php if ($show_maintenance_notice): ?>
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

    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5" style="margin-top: <?php echo $show_maintenance_notice ? '0' : '80px'; ?>;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Empowering Women, Strengthening Nigeria</h1>
                    <p class="lead mb-4">The Federal Ministry of Women Affairs is committed to promoting gender equality, women's empowerment, and the welfare of children across Nigeria.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="about.html" class="btn btn-light btn-lg">Learn More</a>
                        <a href="departments/women-development.php" class="btn btn-outline-light btn-lg">Our Programs</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="images/hero-image.jpg" alt="Women Empowerment" class="img-fluid rounded shadow" 
                         onerror="this.src='images/2025_07_14_13_42_IMG_2808.PNG'">
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="stats-section py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">13</h3>
                        <p class="text-muted">Departments</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-female fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">1M+</h3>
                        <p class="text-muted">Women Empowered</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-child fa-3x text-warning mb-3"></i>
                        <h3 class="fw-bold">500K+</h3>
                        <p class="text-muted">Children Supported</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <i class="fas fa-map-marker-alt fa-3x text-info mb-3"></i>
                        <h3 class="fw-bold">36</h3>
                        <p class="text-muted">States Covered</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Departments -->
    <section class="departments-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold">Our Key Departments</h2>
                    <p class="lead text-muted">Discover our specialized departments working to advance women's rights and welfare</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-venus fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Women Development</h5>
                            <p class="card-text">Advancing women's empowerment through comprehensive development programs and initiatives.</p>
                            <a href="departments/women-development.php" class="btn btn-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-baby fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Child Development</h5>
                            <p class="card-text">Protecting and nurturing children through comprehensive development programs and welfare initiatives.</p>
                            <a href="departments/child-development.php" class="btn btn-success">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-balance-scale fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Gender Affairs</h5>
                            <p class="card-text">Promoting gender equality and mainstreaming gender perspectives in policies and programs.</p>
                            <a href="departments/gender-affairs.php" class="btn btn-warning">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-coins fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Economic Services</h5>
                            <p class="card-text">Empowering women through economic opportunities, financial inclusion, and entrepreneurship development.</p>
                            <a href="departments/economic-services.php" class="btn btn-info">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-apple-alt fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">Nutrition</h5>
                            <p class="card-text">Improving nutritional status and food security for women, children, and vulnerable populations.</p>
                            <a href="departments/nutrition.php" class="btn btn-danger">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-users-cog fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">Human Resource Management</h5>
                            <p class="card-text">Managing human capital and organizational development to enhance ministry performance.</p>
                            <a href="departments/human-resource-management.php" class="btn btn-secondary">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center mt-4">
                    <a href="#" class="btn btn-outline-primary btn-lg" onclick="document.querySelector('#departmentsDropdown').click(); return false;">View All Departments</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News & Updates -->
    <section class="news-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold">Latest News & Updates</h2>
                    <p class="lead text-muted">Stay informed about our latest initiatives and achievements</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary">Latest</span>
                                <small class="text-muted">March 8, 2025</small>
                            </div>
                            <h5 class="card-title">International Women's Day Celebration</h5>
                            <p class="card-text">Join us as we celebrate the achievements of women across Nigeria and launch new empowerment initiatives.</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-success">Program</span>
                                <small class="text-muted">February 20, 2025</small>
                            </div>
                            <h5 class="card-title">New Microfinance Initiative</h5>
                            <p class="card-text">Expanded financial support program for women entrepreneurs across all 36 states of Nigeria.</p>
                            <a href="#" class="btn btn-outline-success btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-warning">Policy</span>
                                <small class="text-muted">February 15, 2025</small>
                            </div>
                            <h5 class="card-title">Gender Equality Framework</h5>
                            <p class="card-text">New policy framework to strengthen gender mainstreaming across all government sectors.</p>
                            <a href="#" class="btn btn-outline-warning btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-4">Get Involved</h2>
                    <p class="lead mb-4">Join us in our mission to empower women and strengthen communities across Nigeria. Together, we can create lasting change.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="#" class="btn btn-light btn-lg">Partner With Us</a>
                        <a href="#" class="btn btn-outline-light btn-lg">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visitor Counter -->
    <div class="visitor-counter-container">
        <div class="visitor-counter">
            <i class="fas fa-eye"></i>
            <span>Visitors: </span>
            <span id="visitor-count">Loading...</span>
        </div>
    </div>

    <?php include 'components/main-footer.php'; ?>

    <style>
        .hero-section {
            background: linear-gradient(135deg, #013a04 0%, #025a06 100%);
        }
        
        .stat-card {
            padding: 2rem 1rem;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .scrolling-text {
            display: inline-block;
            animation: scroll-text 20s linear infinite;
        }
        
        @keyframes scroll-text {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        .visitor-counter-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .visitor-counter {
            background: rgba(1, 58, 4, 0.9);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
            .visitor-counter-container {
                bottom: 10px;
                right: 10px;
            }
            
            .visitor-counter {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>

    <script>
        // Initialize visitor counter
        document.addEventListener('DOMContentLoaded', function() {
            // Simple visitor counter (you can replace with actual backend implementation)
            let count = localStorage.getItem('visitorCount') || 0;
            count = parseInt(count) + 1;
            localStorage.setItem('visitorCount', count);
            document.getElementById('visitor-count').textContent = count.toLocaleString();
        });
    </script>
