<?php
// Page configuration
$page_title = "Internal Audit Department - Federal Ministry of Women Affairs";
$department_name = "Internal Audit";
$show_maintenance_notice = false; // Set to true if you want to show maintenance notice

// Include header
include '../components/header.php';
?>

<!-- Department Title Bar -->
<section class="department-title-bar text-white py-4" style="background: linear-gradient(135deg, #013a04 0%, #025a06 100%); margin-top: 0; padding-top: 3rem !important;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="mb-2 fw-bold" style="color: #ffffff !important; font-size: 1.5rem !important; display: block !important; opacity: 1 !important; visibility: visible !important;"><?php echo $department_name; ?> Department</h1>
                <p class="mb-0" style="font-size: 0.85rem; line-height: 1.3; max-width: 650px; margin: 0 auto;">Ensuring accountability, transparency, and effective risk management through comprehensive internal auditing services.</p>
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
                        <p>Ensuring accountability, transparency, and effective risk management through comprehensive internal auditing services.</p>
                        
                        <h3 class="h5 mt-4 mb-3">Our Objectives</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Conduct comprehensive internal audits of ministry operations
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Ensure compliance with financial regulations and procedures
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Identify and assess operational risks and controls
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Provide recommendations for process improvements
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Programs and Services -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Services</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-search text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Financial Audits</h5>
                                        <p class="text-muted">Comprehensive review of financial transactions and controls.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-cogs text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Operational Audits</h5>
                                        <p class="text-muted">Assessment of operational efficiency and effectiveness.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Risk Assessment</h5>
                                        <p class="text-muted">Identification and evaluation of organizational risks.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clipboard-check text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Compliance Review</h5>
                                        <p class="text-muted">Ensuring adherence to policies and regulatory requirements.</p>
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
                            <img src="../images/directors/internal-audit-director.jpg" 
                                 alt="Mrs. Suleiman Shehu Zainab - Director" 
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
                        <h6 class="mb-1">Mrs. Suleiman Shehu Zainab</h6>
                        <p class="text-muted small mb-3">Director, Internal Audit</p>
                        
                        <div class="contact-details text-start">
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong> audit@fmwa.gov.ng
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
                                <a href="finance-accounting.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Finance & Accounting
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="procurement.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Procurement
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="legal-services.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Legal Services
                                </a>
                            </li>
                            <li class="mb-0">
                                <a href="planning-research-statistics.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2"></i>Planning, Research & Statistics
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
                            <h6 class="card-subtitle mb-1">Annual Audit Plan 2025</h6>
                            <small class="text-muted">March 15, 2025</small>
                            <p class="small mt-1">Comprehensive audit plan for the fiscal year 2025.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="card-subtitle mb-1">Risk Assessment Report</h6>
                            <small class="text-muted">February 20, 2025</small>
                            <p class="small mt-1">Quarterly risk assessment and mitigation strategies.</p>
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
