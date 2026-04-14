<?php
require_once 'config.php';
require_once 'includes/helpers.php';

$site_name = get_setting('site_name', 'Federal Ministry of Women Affairs');
?>
<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Our Mandate - Federal Ministry of Women Affairs</title>
    <!-- Include components -->
    <?php include_once 'includes/js-config.php'; ?>
    <script src="js/include-components.js" defer></script>
    
    <style>
        body { padding-top: 100px; }
    </style>
<!-- Favicon -->
<link href="./images/2025_07_14_13_42_IMG_2808.PNG" rel="icon" type="image/png"/>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
<!-- Custom CSS -->
<link href="./css/style.css" rel="stylesheet"/>
<link href="./css/header.css" rel="stylesheet"/>
<link href="./css/carousel.css" rel="stylesheet"/>
<link href="./css/general.css" rel="stylesheet"/>
<link href="./css/footer.css" rel="stylesheet"/>
<link href="./css/downloads.css" rel="stylesheet"/>
<link href="./css/leadership.css" rel="stylesheet"/>
<!-- Bootstrap JS Bundle with Popper -->
<script crossorigin="anonymous" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Initialize Bootstrap dropdowns with submenu support -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all dropdowns
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

        // Handle submenu hover for desktop
        if (window.innerWidth >= 992) {
            // Main dropdown hover
            document.querySelectorAll('.dropdown, .dropdown-submenu').forEach(function(element) {
                element.addEventListener('mouseenter', function(e) {
                    const toggle = this.querySelector('.dropdown-toggle');
                    if (toggle) {
                        const dropdown = bootstrap.Dropdown.getInstance(toggle);
                        if (dropdown) {
                            // Close any open dropdowns at this level
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
                    // Only hide if not hovering over a child dropdown
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
        
        // Handle touch events for mobile
        document.addEventListener('click', function(e) {
            const target = e.target;
            if (window.innerWidth < 992) {
                // Handle dropdown toggle on mobile
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
<body class="d-flex flex-column min-vh-100">

<!-- Mandate Content -->
<section class="py-5">
<div class="container">
<div class="row">
<div class="col-lg-8 mx-auto">
<!-- Overview -->
<div class="mb-5">
<h2 class="text-primary mb-4">Mandate Overview</h2>
<p class="lead"><?php echo htmlspecialchars(get_setting('our_mandate', 'The broad mandate of the Ministry is to advise government...')); ?></p>
</div>
<!-- Functions of the Ministry -->
<div class="mb-5">
<h3 class="text-primary mb-4">Functions of the Ministry</h3>
<ol class="mb-0 functions-list">
<li>Promote the advancement of women in the socio-cultural, political and economic development of Nigeria;</li>
<li>Provide an enabling environment that ensures maximum and holistic development as well as nation building;</li>
<li>Promote health and reproductive rights of women to enhance responsible motherhood and maternal health;</li>
<li>Initiate actions towards elimination of all social and cultural practices that discriminate against the overall development of women and the girl child;</li>
<li>Ensure the integration of women in national process and promoting the mainstreaming of gender in all issues of national importance;</li>
<li>Conduct awareness creation and formulation of policies and legislation on survival, development, protection and participatory rights of women and children in Nigeria;</li>
<li>Advocate for the protection and observance of the rights of the child within the definition of international laws on Human Rights;</li>
<li>Promote, formulate and propagate moral values within the family units and the general public;</li>
<li>Establish programmes with institutions and organizations to inculcate civic, political, cultural, social, economic and moral education of women, children and youth;</li>
<li>Support the work of relevant non-governmental organizations (NGOs) and encourage the sense and essence of cooperative societies among women and girls both in urban and rural areas to stimulate creativity and entrepreneurship in them;</li>
<li>Advocate the passage and enforcement of laws that protect and project the interest of the Ministry's target groups including the recognition of women's work in all sectors in National Accounting System;</li>
<li>Promote a multi-sectoral programme synergy for the realization and enhancement of the survival, development, protection and participation of children in Nigeria with particular reference to the achievement of set targets enumerated in the Child's Right Act, 2003 (CRA); the National Gender Policy, the National Economic Recovery and Growth Plan (ERGP) and the Sustainable Development Goals (SDGs);</li>
<li>Formulate and update various policies and implementation of strategies for the development of various categories of Persons with Disabilities (PWDs);</li>
<li>Train all categories of PWDs and resettle them after their training;</li>
<li>Management of Braille Presses and Braille Library Services;</li>
<li>Co-ordinate Inter-Governmental and Inter-State Social Welfare activities;</li>
<li>Conduct research and surveys in various aspects of gender;</li>
<li>Rehabilitation of victims of Boko Haram insurgency, surrendered insurgents and particularly, the re-integration and ensuring that the recovered Chibok Girls are back to school to complete their studies;</li>
<li>Training of professional social workers and the organization/coordination of training facilities for government and non-governmental social welfare agencies;</li>
<li>Care and support for the vulnerable groups.</li>
<li>Formulate policies, programmes and actions within the contexts of National development plans and global agenda for women development in the social, economic and political areas;</li>
<li>Serve as central-programme coordination, advisory and harmonization point for the country on women's total concerns;</li>
<li>Provide training, research and dissemination of information on women issues/concerns and development;</li>
<li>Networking and liaison with National, Regional and International Institutions/organizations, development partners, private sectors, media and non-governmental organizations on matters affecting women's empowerment and development;</li>
<li>Promote women-centred sustainable development through provision of basic and life-long education, literacy and empowerment programmes for self-reliance;</li>
<li>Ensure high level advocacy campaign at national and to states to sensitize Policy makers on women's plights and raise general public awareness on women's roles in nation building, disseminate information and galvanize support for women advancement/participation;</li>
<li>Engage in social orientation and IEC development/dissemination on women matters;</li>
<li>Ensure women's access to education and information including women's sexual, physical, mental, emotional health and reproductive rights;</li>
<li>Undertake supervisory/follow-up activities on the national implementation of all women related frameworks/protocols across all boards;</li>
<li>Mobilize and facilitate the provision of palliatives/relief materials for indigent women.</li>
</ol>
</div>
<!-- Key Responsibilities -->
<div class="mb-5">
<h3 class="text-primary mb-4">Key Responsibilities</h3>
<div class="row">
<div class="col-md-6 mb-3">
<div class="d-flex align-items-start">
<i class="fas fa-check-circle text-success me-3 mt-1"></i>
<div>
<h5>Policy Development</h5>
<p>Formulate and implement comprehensive policies on gender equality and women's empowerment.</p>
</div>
</div>
</div>
<div class="col-md-6 mb-3">
<div class="d-flex align-items-start">
<i class="fas fa-check-circle text-success me-3 mt-1"></i>
<div>
<h5>Women's Rights Advocacy</h5>
<p>Advocate for women's rights and ensure their active participation in all sectors of society.</p>
</div>
</div>
</div>
<div class="col-md-6 mb-3">
<div class="d-flex align-items-start">
<i class="fas fa-check-circle text-success me-3 mt-1"></i>
<div>
<h5>Children's Welfare</h5>
<p>Coordinate programs for children's welfare, protection, and development across the nation.</p>
</div>
</div>
</div>
<div class="col-md-6 mb-3">
<div class="d-flex align-items-start">
<i class="fas fa-check-circle text-success me-3 mt-1"></i>
<div>
<h5>Disability Support</h5>
<p>Support the integration and empowerment of persons with disabilities in society.</p>
</div>
</div>
</div>
<div class="col-md-6 mb-3">
<div class="d-flex align-items-start">
<i class="fas fa-check-circle text-success me-3 mt-1"></i>
<div>
<h5>Elderly Care</h5>
<p>Develop and implement strategies for elderly care and support systems.</p>
</div>
</div>
</div>
<div class="col-md-6 mb-3">
<div class="d-flex align-items-start">
<i class="fas fa-check-circle text-success me-3 mt-1"></i>
<div>
<h5>International Collaboration</h5>
<p>Collaborate with international organizations on gender equality and social development issues.</p>
</div>
</div>
</div>
</div>
</div>
<!-- Strategic Focus Areas -->
<div class="mb-5">
<h3 class="text-primary mb-4">Strategic Focus Areas</h3>
<div class="row">
<div class="col-md-6 mb-4">
<div class="card h-100 border-0 shadow-sm">
<div class="card-body">
<h5 class="card-title text-primary">
<i class="fas fa-flag me-2"></i>National Level
                                        </h5>
<ul class="list-unstyled">
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Policy development and implementation</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Inter-ministerial coordination</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Community engagement programs</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Capacity building initiatives</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Monitoring and evaluation systems</li>
</ul>
</div>
</div>
</div>
<div class="col-md-6 mb-4">
<div class="card h-100 border-0 shadow-sm">
<div class="card-body">
<h5 class="card-title text-primary">
<i class="fas fa-globe me-2"></i>International Level
                                        </h5>
<ul class="list-unstyled">
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>UN Women collaboration</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Regional partnerships and networks</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Global best practices adoption</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>International treaty compliance</li>
<li class="mb-2"><i class="fas fa-arrow-right text-warning me-2"></i>Cross-border initiatives</li>
</ul>
</div>
</div>
</div>
</div>
</div>
<!-- Legal Framework -->
<div class="mb-5">
<h3 class="text-primary mb-4">Legal Framework</h3>
<div class="bg-light p-4 rounded">
<p class="mb-3">Our mandate is anchored on several key legal instruments and international commitments:</p>
<ul>
<li><strong>Constitution of the Federal Republic of Nigeria (1999)</strong> - Fundamental rights and non-discrimination provisions</li>
<li><strong>National Gender Policy (2006)</strong> - Framework for gender mainstreaming</li>
<li><strong>Violence Against Persons (Prohibition) Act (2015)</strong> - Protection against gender-based violence</li>
<li><strong>Convention on the Elimination of All Forms of Discrimination Against Women (CEDAW)</strong></li>
<li><strong>Beijing Platform for Action</strong> - Global commitment to gender equality</li>
<li><strong>Sustainable Development Goals (SDGs)</strong> - Particularly Goal 5 on Gender Equality</li>
</ul>
</div>
</div>
<!-- Call to Action -->
<div class="text-center">
<div class="bg-primary text-white p-4 rounded">
<h4 class="mb-3">Join Us in Building an Inclusive Nigeria</h4>
<p class="mb-3">Together, we can create a society where every woman, child, elderly person, and person with disabilities has equal opportunities to thrive.</p>
<a class="btn btn-light btn-lg" href="./index.php#contact">Get Involved</a>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Footer -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="./js/main.js"></script>
<script src="./js/highlight-section.js"></script>
<script src="./js/division-submenu.js"></script>
<script src="./js/shrink-dropdown-options.js"></script>
<script src="./js/dropdown-tooltip.js"></script>
<script src="./js/remove-latest-news.js"></script>
    <!-- Footer -->
    <footer class="footer bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3"><?php echo htmlspecialchars($site_name); ?></h5>
                    <p class="text-light"><?php echo htmlspecialchars(get_setting('footer_about_text', 'Empowering women and promoting gender equality...')); ?></p>
                    <div class="social-links mt-3">
                        <?php if ($fb = get_setting('social_facebook')): ?>
                            <a href="<?php echo htmlspecialchars($fb); ?>" class="text-light me-3" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($tw = get_setting('social_twitter')): ?>
                            <a href="<?php echo htmlspecialchars($tw); ?>" class="text-light me-3" title="X (Twitter)" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if ($ig = get_setting('social_instagram')): ?>
                            <a href="<?php echo htmlspecialchars($ig); ?>" class="text-light me-3" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($yt = get_setting('social_youtube')): ?>
                            <a href="<?php echo htmlspecialchars($yt); ?>" class="text-light" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
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
                            <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                            <?php echo nl2br(htmlspecialchars(get_setting('contact_address', 'Federal Secretariat Complex, Abuja'))); ?>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_phone', '+234-9-461-0000')); ?>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_email', 'info@fmwa.gov.ng')); ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_hours', 'Mon - Fri: 8:00 AM - 4:00 PM')); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom border-top border-secondary pt-4 mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">
                            &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_name); ?>. All rights reserved.
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
    </footer></body>
</html>
