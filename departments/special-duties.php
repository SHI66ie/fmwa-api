<?php
// Page configuration
$page_title = "Special Duties Department - Federal Ministry of Women Affairs";
$department_name = "Special Duties";
$show_maintenance_notice = false; // Set to true if you want to show maintenance notice

// Include header
include '../components/header.php';
?>

<!-- Department Title Bar -->
<section class="department-title-bar bg-primary text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="h2 mb-2"><?php echo $department_name; ?> Department</h1>
                <p class="lead mb-0">Handling special assignments and strategic initiatives to support ministry objectives and government priorities.</p>
            </div>
        </div>
    </div>
</section>

<!-- Department Content -->
<section class="department-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Main Content -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About the Department</h2>
                        <p>Addressing unique challenges and implementing special initiatives for women and children's welfare.</p>
                        
                        <h3 class="h5 mt-4 mb-3">Our Objectives</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Handle special assignments and emergency interventions
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Coordinate inter-agency collaborations and partnerships
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Implement special programs for vulnerable populations
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Address emerging issues affecting women and children
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Programs and Services -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Programs and Services</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Emergency Response</h5>
                                        <p class="text-muted">Rapid response to crises affecting women and children.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-link text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Inter-Agency Coordination</h5>
                                        <p class="text-muted">Collaboration with other ministries and agencies.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-heart text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Vulnerable Groups Support</h5>
                                        <p class="text-muted">Special programs for at-risk populations.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-lightbulb text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Innovation Projects</h5>
                                        <p class="text-muted">Pilot programs and innovative solutions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tie me-2"></i>Department Director
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Director Photo -->
                        <div class="director-photo-container mb-3">
                            <img src="../images/directors/special-duties-director.jpg" 
                                 alt="Dr. Zainab Usman - Director" 
                                 class="director-photo img-fluid rounded-circle"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="director-placeholder" style="display: none;">
                                <i class="fas fa-user-tie fa-3x text-white"></i>
                                <div class="placeholder-text">
                                    <small>DIRECTOR</small><br>
                                    <small>Photo Coming Soon</small>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1">Dr. Zainab Usman</h6>
                        <p class="text-muted small mb-3">Director, Special Duties</p>
                        
                        <div class="contact-details text-start">
                            <p class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Phone:</strong> +234-9-461-0012
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong> specialduties@fmwa.gov.ng
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Office Hours:</strong> Mon - Fri: 8:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-link me-2"></i>Related Departments
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="women-development.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Women Development
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="gender-affairs.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Gender Affairs
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="child-development.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Child Development
                                </a>
                            </li>
                            <li class="mb-0">
                                <a href="nutrition.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Nutrition
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Latest News -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-newspaper me-2"></i>Latest Updates
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="card-subtitle mb-1">Department Workshop 2025</h6>
                            <small class="text-muted">March 15, 2025</small>
                            <p class="small mt-1">Join us for our annual department workshop and training session.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="card-subtitle mb-1">New Program Launch</h6>
                            <small class="text-muted">February 20, 2025</small>
                            <p class="small mt-1">Exciting new initiatives to better serve our beneficiaries.</p>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-info">View All Updates</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include '../components/footer.php';
?>