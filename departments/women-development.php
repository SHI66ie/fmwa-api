<?php
// Page configuration
$page_title = "Women Development Department - Federal Ministry of Women Affairs";
$department_name = "Women Development";
$show_maintenance_notice = false; // Set to true if you want to show maintenance notice

// Include header
include '../components/header.php';
?>

<!-- Department Header -->
<section class="department-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()">Departments</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $department_name; ?></li>
            </ol>
        </nav>
        <h1><?php echo $department_name; ?> Department</h1>
        <p class="lead">Advancing women's empowerment and gender equality through comprehensive development programs and initiatives.</p>
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
                        <p>The Women Development Department is at the forefront of implementing policies and programs designed to empower women across Nigeria. Our mission is to create opportunities for women to participate fully in social, economic, and political activities.</p>
                        
                        <h3 class="h5 mt-4 mb-3">Our Objectives</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Promote women's economic empowerment through skills development and entrepreneurship
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Advocate for women's rights and gender equality in all sectors
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Implement programs to reduce gender-based violence and discrimination
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Support women's participation in leadership and decision-making roles
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
                                        <i class="fas fa-graduation-cap text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Skills Development</h5>
                                        <p class="text-muted">Vocational training programs to enhance women's employability and entrepreneurial skills.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-handshake text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Microfinance Support</h5>
                                        <p class="text-muted">Financial assistance and credit facilities for women-owned businesses and cooperatives.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Legal Support</h5>
                                        <p class="text-muted">Legal aid and counseling services for women facing discrimination or violence.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Leadership Training</h5>
                                        <p class="text-muted">Capacity building programs to prepare women for leadership roles in various sectors.</p>
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
                            <i class="fas fa-phone me-2"></i>Contact Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Director:</strong><br>
                            Dr. Amina Hassan
                        </p>
                        <p class="mb-2">
                            <strong>Phone:</strong><br>
                            +234-9-461-0001
                        </p>
                        <p class="mb-2">
                            <strong>Email:</strong><br>
                            womendevelopment@fmwa.gov.ng
                        </p>
                        <p class="mb-0">
                            <strong>Office Hours:</strong><br>
                            Monday - Friday: 8:00 AM - 4:00 PM
                        </p>
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
                                <a href="gender-affairs.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Gender Affairs
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="child-development.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Child Development
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="community-development-social-intervention.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Community Development
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
                            <h6 class="card-subtitle mb-1">Women Empowerment Summit 2025</h6>
                            <small class="text-muted">March 8, 2025</small>
                            <p class="small mt-1">Join us for the annual summit focusing on economic empowerment and leadership development.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="card-subtitle mb-1">New Microfinance Program Launch</h6>
                            <small class="text-muted">February 15, 2025</small>
                            <p class="small mt-1">Expanded financial support for women entrepreneurs across all states.</p>
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
