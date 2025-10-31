<?php
// Page configuration
$page_title = "Nutrition Department - Federal Ministry of Women Affairs";
$department_name = "Nutrition";
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
        <p class="lead">Improving nutritional status and food security for women, children, and vulnerable populations across Nigeria.</p>
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
                        <p>Improving nutritional status and food security for women, children, and vulnerable populations across Nigeria.</p>
                        
                        <h3 class="h5 mt-4 mb-3">Our Objectives</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Reduce malnutrition among women and children
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Promote optimal infant and young child feeding practices
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Strengthen food security and nutrition systems
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Implement nutrition education and awareness programs
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
                                        <i class="fas fa-apple-alt text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Nutrition Education</h5>
                                        <p class="text-muted">Community-based nutrition education and counseling programs.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-baby text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Maternal Nutrition</h5>
                                        <p class="text-muted">Nutritional support for pregnant and lactating mothers.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-utensils text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>School Feeding</h5>
                                        <p class="text-muted">Nutritious meal programs in schools and communities.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-seedling text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Food Security</h5>
                                        <p class="text-muted">Agricultural and food production support programs.</p>
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
                            Dr. Kemi Adebayo
                        </p>
                        <p class="mb-2">
                            <strong>Phone:</strong><br>
                            +234-9-461-0007
                        </p>
                        <p class="mb-2">
                            <strong>Email:</strong><br>
                            nutrition@fmwa.gov.ng
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