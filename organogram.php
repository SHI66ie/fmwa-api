<?php
// organogram.php - Organogram page for Federal Ministry of Women Affairs
?>
<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Organogram - Federal Ministry of Women Affairs</title>
<link href="./images/2025_07_14_13_42_IMG_2808.PNG" rel="icon" type="image/png"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
<link href="./css/style.css" rel="stylesheet"/>
<link href="./css/header.css" rel="stylesheet"/>
<link href="./css/carousel.css" rel="stylesheet"/>
<link href="./css/general.css" rel="stylesheet"/>
<link href="./css/footer.css" rel="stylesheet"/>
<link href="./css/downloads.css" rel="stylesheet"/>
<link href="./css/leadership.css" rel="stylesheet"/>
<link href="./style.css" rel="stylesheet"/>
<script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl, {
                autoClose: 'outside',
                popperConfig: function(defaultBsPopperConfig) {
                    return {
                        ...defaultBsPopperConfig,
                        strategy: 'fixed'
                    };
                }
            });
        });
        if (window.innerWidth >= 992) {
            document.querySelectorAll('.dropdown, .dropdown-submenu').forEach(function(element) {
                element.addEventListener('mouseenter', function(e) {
                    const toggle = this.querySelector('.dropdown-toggle');
                    if (toggle) {
                        const dropdown = bootstrap.Dropdown.getInstance(toggle);
                        if (dropdown) {
                            const parentNav = this.closest('.dropdown-menu');
                            if (parentNav) {
                                parentNav.querySelectorAll('.dropdown-menu').forEach(menu => {
                                    if (menu !== this.querySelector('.dropdown-menu')) {
                                        const toggleBtn = menu.previousElementSibling;
                                        if (toggleBtn && toggleBtn.matches('.dropdown-toggle')) {
                                            const dd = bootstrap.Dropdown.getInstance(toggleBtn);
                                            if (dd) dd.hide();
                                        }
                                    }
                                });
                            }
                            dropdown.show();
                        }
                    }
                });
                element.addEventListener('mouseleave', function(e) {
                    if (!this.contains(e.relatedTarget) || !e.relatedTarget) {
                        const toggle = this.querySelector('.dropdown-toggle');
                        if (toggle) {
                            const dropdown = bootstrap.Dropdown.getInstance(toggle);
                            if (dropdown) dropdown.hide();
                        }
                    }
                });
            });
        }
        document.addEventListener('click', function(e) {
            const target = e.target;
            if (window.innerWidth < 992) {
                if (target.matches('.dropdown-toggle')) {
                    e.preventDefault();
                    const dropdown = bootstrap.Dropdown.getInstance(target);
                    if (dropdown) {
                        dropdown.toggle();
                    }
                }
            }
        });
    });
    </script>
</head>
<body><nav class="navbar navbar-expand-lg navbar-dark main-nav px-0">
<div class="container-fluid gx-0">
<a class="navbar-brand d-flex align-items-center me-0" href="./index.php">
<img alt="Federal Ministry of Women Affairs Logo" class="fmwa-logo" src="./images/2025_07_14_13_42_IMG_2808.PNG"/>
<span class="logo-divider"></span>
<span class="fmwa-navbar-title">FEDERAL MINISTRY OF WOMEN AFFAIRS</span>
</a>
<button aria-expanded="false" class="navbar-toggler" data-bs-target="#navbarNav" data-bs-toggle="collapse" title="Toggle navigation menu" type="button">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto">
<li class="nav-item">
<a class="nav-link" href="./index.php" title="Go to home page">Home</a>
</li>
<li class="nav-item">
<a class="nav-link" href="./about.php" title="Learn about the ministry">About Us</a>
</li>
<li class="nav-item dropdown">
<a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="departmentsDropdown" role="button" title="Explore our departments">
                            Departments
                        </a>
<ul aria-labelledby="departmentsDropdown" class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="./departments/child-development.php" title="Child Development Department">
                                Child Development
                            </a></li>
<li><a class="dropdown-item" href="./departments/community-development-social-intervention.php" title="Community Development &amp; Social Intervention Department">
                                Community Development &amp; Social Intervention
                            </a></li>
<li><a class="dropdown-item" href="./departments/finance-accounting.php" title="Finance &amp; Accounting Department">
                                Finance &amp; Accounting
                            </a></li>
<li><a class="dropdown-item" href="./departments/gender-affairs.php" title="Gender Affairs Department">
                                Gender Affairs
                            </a></li>
<li><a class="dropdown-item" href="./departments/general-services.php" title="General Services Department">
                                General Services
                            </a></li>
<li><a class="dropdown-item" href="./departments/nutrition.php" title="Nutrition Department">
                                Nutrition
                            </a></li>
<li><a class="dropdown-item" href="./departments/planning-research-statistics.php" title="Planning, Research &amp; Statistics Department">
                                Planning, Research &amp; Statistics
                            </a></li>
<li><a class="dropdown-item" href="./departments/procurement.php" title="Procurement Department">
                                Procurement
                            </a></li>
<li><a class="dropdown-item" href="./departments/reform-coordination-service-improvement.php" title="Reform Coordination &amp; Service Improvement Department">
                                Reform Coordination &amp; Service Improvement
                            </a></li>
<li><a class="dropdown-item" href="./departments/women-development.php" title="Women Development Department">
                                Women Development
                            </a></li>
</ul>
</li>
</ul>
</div>
</div>
</nav>

<!-- Organogram Section -->
<section class="py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-10">
<div class="card border-0 shadow-sm">
<div class="card-body">
<h2 class="h4 card-title mb-4 text-center">Organizational Structure</h2>
<div class="text-center">
<img alt="Organogram – Federal Ministry of Women Affairs" class="img-fluid" src="./images/organogram_final.png"/>
</div>
</div>
</div>
</div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="./js/main.js"></script>
<script src="./js/highlight-section.js"></script>
<footer>
<div class="container">
<div class="row">
<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
<div class="footer-widget">
<h4>Federal Ministry of Women Affairs</h4>
<p>Empowering women for national development</p>
<div class="social-links mt-3">
<a class="me-2" href="https://www.facebook.com/FMWAngr" rel="noopener noreferrer" target="_blank" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
<a class="me-2" href="https://x.com/FMWA_ng" rel="noopener noreferrer" target="_blank" title="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
<a class="me-2" href="https://www.instagram.com/FMWAngr" rel="noopener noreferrer" target="_blank" title="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
<a class="me-2" href="http://www.youtube.com/@fmwangr" rel="noopener noreferrer" target="_blank" title="Watch us on YouTube"><i class="fab fa-youtube"></i></a>
</div>
</div>
</div>
<div class="col-lg-2 col-md-6 mb-4 mb-md-0">
<div class="footer-widget">
<h4>Quick Links</h4>
<ul class="footer-links">
<li><a href="https://webmail.womenaffairs.gov.ng/" rel="noopener" target="_blank" title="Access Staff Email Services">Staff Email Services</a></li>
<li><a href="./index.php" title="Go to home page">Home</a></li>
<li><a href="./about.php" title="Learn about us">About Us</a></li>
<li><a href="#" title="View our services">Services</a></li>
<li><a href="#" title="Access resources">Resources</a></li>
<li><a href="#">News &amp; Events</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
</div>
<div class="col-lg-4 col-md-6 ms-auto">
<div class="footer-widget">
<h4>Contact Us</h4>
<address>
<p><i class="fas fa-map-marker-alt me-2"></i> Plot 1070, Central Business District, Cadastral Zone AO, Shehu Shagari Way, by Ralph Shodeinde Street, opposite the Court of Appeal, in Abuja, FCT</p>
<p class="mb-2"><i class="fas fa-envelope me-2"></i> info@womenaffairs.gov.ng</p>
<p><i class="fas fa-clock me-2"></i> Mon - Fri: 8:00 AM - 4:00 PM</p>
</address>
<div class="mt-4">
<h5>Newsletter</h5>
<div class="input-group mb-3">
<input class="form-control" placeholder="Your Email" type="email"/>
<button class="btn btn-warning" type="button">Subscribe</button>
</div>
</div>
</div>
</div>
</div>
<div class="copyright">
<div class="row">
<div class="col-md-6 text-center text-md-start">
                        © 2025 Federal Ministry of Women Affairs. All Rights Reserved.
                    </div>
<div class="col-md-6 text-center text-md-end">
<a class="text-white me-3" href="#">Privacy Policy</a>
<a class="text-white" href="#">Terms of Use</a>
</div>
</div>
</div>
</div>
</footer></body>
</html>
