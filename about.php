<?php
// about.php - About page for Federal Ministry of Women Affairs
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Federal Ministry of Women Affairs</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/2025_07_14_13_42_IMG_2808.PNG">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/department-navigation.css">
    <link rel="stylesheet" href="css/logo-position-fix.css?v=1.1">
    <link rel="stylesheet" href="css/welcome-banner.css">
    <link rel="stylesheet" href="css/visitor-counter.css">
    
    <!-- Include components -->
    <script src="js/include-components.js" defer></script>
    
    <style>
        body {
            padding-top: 80px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #013a04 0%, #025a06 100%);
            color: white;
            padding: 60px 0 40px 0;
            margin-top: 0;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .page-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }
        
        .page-header .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .page-header .breadcrumb-item.active {
            color: #ffc107;
        }
        
        .content-section {
            padding: 40px 0;
        }
        
        .section-title {
            color: #013a04;
            font-weight: 600;
            margin-bottom: 30px;
            position: relative;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: #ffc107;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card .icon {
            font-size: 3rem;
            color: #013a04;
            margin-bottom: 20px;
        }
        
        .feature-card h4 {
            color: #013a04;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .stats-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #013a04;
            display: block;
        }
        
        .stat-label {
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #013a04;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 80px;
            margin-bottom: 30px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 22px;
            top: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ffc107;
            border: 3px solid #013a04;
        }
        
        .timeline-year {
            font-weight: 700;
            color: #013a04;
            font-size: 1.2rem;
        }
        
        .leadership-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .leadership-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 5px solid #f8f9fa;
        }
        
        .leadership-name {
            color: #013a04;
            font-weight: 600;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .leadership-title {
            color: #ffc107;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 40px 0 30px 0;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .timeline:before {
                left: 15px;
            }
            
            .timeline-item {
                padding-left: 50px;
            }
            
            .timeline-item:before {
                left: 7px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation will be inserted here by js/include-components.js -->

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                    <h1><i class="fas fa-users me-3"></i>About Us</h1>
                    <p class="lead">Learn about our mission, vision, and commitment to empowering women across Nigeria</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision & Values -->
    <section class="content-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <p>To help build a Nigerian Society that guarantees equal access to social, economic and wealth creation opportunities to all, irrespective of gender, places premium on protection of the child, the aged and persons with disabilities.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h4>Our Vision</h4>
                        <p>A society where women, children, the aged and persons with disabilities are empowered to participate fully in national development and enjoy equal opportunities in all spheres of life.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Our Values</h4>
                        <ul class="text-start">
                            <li>Integrity and Transparency</li>
                            <li>Gender Equality</li>
                            <li>Social Justice</li>
                            <li>Inclusivity</li>
                            <li>Excellence in Service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Our Impact in Numbers</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Departments</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">36</span>
                        <span class="stat-label">States Covered</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Programs Implemented</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Years of Service</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="content-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title">Who We Are</h2>
                    <p class="lead">The Federal Ministry of Women Affairs was established to champion the cause of women, children, and vulnerable groups in Nigeria.</p>
                    
                    <p>The Federal Ministry of Women Affairs and Social Development was created in response to the United Nations agreement to establish Institutional Mechanisms for the advancement of women. The Ministry serves as the focal point for coordinating policies, programs, and activities aimed at promoting gender equality and empowering women across Nigeria.</p>
                    
                    <p>Our mandate encompasses a wide range of responsibilities including policy formulation, program implementation, advocacy, and coordination of efforts to ensure that women, children, the elderly, and persons with disabilities are fully integrated into the national development process.</p>
                    
                    <h3 class="mt-4 mb-3">Key Focus Areas</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Women's Economic Empowerment</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gender Equality Advocacy</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Child Protection Services</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Social Welfare Programs</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Disability Rights Advocacy</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Elderly Care Services</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Policy Development</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>International Cooperation</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="leadership-card">
                        <img src="images/minister-placeholder.jpg" alt="Honourable Minister" class="leadership-photo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDE1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiBmaWxsPSIjRjhGOUZBIi8+CjxjaXJjbGUgY3g9Ijc1IiBjeT0iNjAiIHI9IjI1IiBmaWxsPSIjREVERURFIi8+CjxwYXRoIGQ9Ik0zMCAxMjBDMzAgMTA0LjUgNDcuNSA5MCA3NSA5MEM5Ny41IDkwIDEyMCAxMDQuNSAxMjAgMTIwVjE1MEgzMFYxMjBaIiBmaWxsPSIjREVERURFIi8+Cjwvc3ZnPgo='">
                        <h4 class="leadership-name">Hon. Imaan Sulaiman Ibrahim</h4>
                        <p class="leadership-title">Honourable Minister</p>
                        <p class="text-muted">Leading the Ministry with dedication to women's empowerment and social development across Nigeria.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- History Timeline -->
    <section class="content-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title text-center">Our Journey</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-year">1995</div>
                            <h5>Beijing Platform for Action</h5>
                            <p>Nigeria committed to the Beijing Platform for Action, setting the foundation for institutional mechanisms for women's advancement.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">1999</div>
                            <h5>Ministry Establishment</h5>
                            <p>The Federal Ministry of Women Affairs was officially established to coordinate women's development programs nationwide.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2006</div>
                            <h5>National Gender Policy</h5>
                            <p>Launch of Nigeria's National Gender Policy to mainstream gender equality across all sectors.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2015</div>
                            <h5>VAPP Act</h5>
                            <p>Violence Against Persons (Prohibition) Act was signed into law, strengthening protection for vulnerable groups.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2023</div>
                            <h5>Renewed Hope Agenda</h5>
                            <p>Alignment with President Tinubu's Renewed Hope Agenda, focusing on inclusive governance and women's empowerment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="content-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="bg-primary text-white p-5 rounded">
                        <h3 class="mb-3">Join Us in Building an Inclusive Nigeria</h3>
                        <p class="lead mb-4">Together, we can create a society where every woman, child, elderly person, and person with disabilities has equal opportunities to thrive and contribute to national development.</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="mandate.php" class="btn btn-light btn-lg">Our Mandate</a>
                            <a href="departments/women-development" class="btn btn-outline-light btn-lg">Our Departments</a>
                            <a href="#contact" class="btn btn-warning btn-lg">Get Involved</a>
                        </div>
                    </div>
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
                    <p class="text-muted">Empowering women and promoting gender equality across Nigeria through comprehensive policies, programs, and initiatives that advance the welfare of women and children.</p>
                    <div class="social-links mt-3">
                        <a href="https://www.facebook.com/FMWAngr" class="text-light me-3" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/FMWA_ng" class="text-light me-3" title="Twitter" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/FMWAngr" class="text-light me-3" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="http://www.youtube.com/@fmwangr" class="text-light" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="./" class="text-muted text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="mandate.php" class="text-muted text-decoration-none">Our Mandate</a></li>
                        <li class="mb-2"><a href="organogram.php" class="text-muted text-decoration-none">Organogram</a></li>
                    </ul>
                </div>
                
                <!-- Departments -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Key Departments</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="departments/women-development" class="text-muted text-decoration-none">Women Development</a></li>
                        <li class="mb-2"><a href="departments/child-development" class="text-muted text-decoration-none">Child Development</a></li>
                        <li class="mb-2"><a href="departments/gender-affairs" class="text-muted text-decoration-none">Gender Affairs</a></li>
                        <li class="mb-2"><a href="departments/nutrition" class="text-muted text-decoration-none">Nutrition</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <div class="contact-info">
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Plot 1070, Central Business District, Cadastral Zone AO, Shehu Shagari Way, by Ralph Shodeinde Street, opposite the Court of Appeal, in Abuja, FCT
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            info@womenaffairs.gov.ng
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Mon - Fri: 8:00 AM - 4:00 PM
                        </p>
                    </div>
                    <div class="mt-4">
                        <h6 class="mb-3">Newsletter</h6>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your Email" aria-label="Email">
                            <button class="btn btn-warning" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom border-top border-secondary pt-4 mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">
                            © 2025 Federal Ministry of Women Affairs. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted me-3">Privacy Policy</a>
                        <a href="#" class="text-muted">Terms of Use</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate statistics on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const finalNumber = stat.textContent;
                        let currentNumber = 0;
                        const increment = parseInt(finalNumber) / 50;
                        
                        const timer = setInterval(() => {
                            currentNumber += increment;
                            if (currentNumber >= parseInt(finalNumber)) {
                                stat.textContent = finalNumber;
                                clearInterval(timer);
                            } else {
                                stat.textContent = Math.floor(currentNumber) + (finalNumber.includes('+') ? '+' : '');
                            }
                        }, 30);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>
