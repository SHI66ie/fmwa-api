    <!-- Footer -->
    <footer class="footer bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3"><?php echo htmlspecialchars(get_setting('site_name', 'Federal Ministry of Women Affairs')); ?></h5>
                    <p class="text-light"><?php echo htmlspecialchars(get_setting('footer_about_text', 'Empowering women and promoting gender equality across Nigeria...')); ?></p>
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
                        <li class="mb-2"><a href="../index.php" class="text-light text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="../about.php" class="text-light text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="../mandate.php" class="text-light text-decoration-none">Our Mandate</a></li>
                        <li class="mb-2"><a href="../organogram.php" class="text-light text-decoration-none">Organogram</a></li>
                    </ul>
                </div>
                
                <!-- Departments -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Key Departments</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="women-development.php" class="text-light text-decoration-none">Women Development</a></li>
                        <li class="mb-2"><a href="child-development.php" class="text-light text-decoration-none">Child Development</a></li>
                        <li class="mb-2"><a href="gender-affairs.php" class="text-light text-decoration-none">Gender Affairs</a></li>
                        <li class="mb-2"><a href="nutrition.php" class="text-light text-decoration-none">Nutrition</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <div class="contact-info">
                        <p class="text-light mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                            <?php echo nl2br(htmlspecialchars(get_setting('contact_address', 'Federal Secretariat Complex, Abuja'))); ?>
                        </p>
                        <p class="text-light mb-2">
                            <i class="fas fa-phone me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_phone', '+234-9-461-0000')); ?>
                        </p>
                        <p class="text-light mb-2">
                            <i class="fas fa-envelope me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_email', 'info@fmwa.gov.ng')); ?>
                        </p>
                        <p class="text-light mb-0">
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
                            &copy; <?php echo date('Y'); ?> Federal Ministry of Women Affairs. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-light text-decoration-none me-3">Terms of Service</a>
                        <a href="#" class="text-light text-decoration-none">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap dropdowns
        const dropdownToggle = document.querySelector('#departmentsDropdown');
        if (dropdownToggle && typeof bootstrap !== 'undefined') {
            new bootstrap.Dropdown(dropdownToggle);
        }
        
        // Handle hover behavior for desktop
        const dropdown = document.querySelector('.dropdown');
        if (dropdown && window.innerWidth >= 992) {
            dropdown.addEventListener('mouseenter', function() {
                const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdownInstance) {
                    dropdownInstance.show();
                }
            });
            
            dropdown.addEventListener('mouseleave', function() {
                const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
            });
        }
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Debug social media links
        const socialLinks = document.querySelectorAll('.social-links a');
        socialLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                console.log('Social link clicked:', this.href);
                // Ensure the link works
                if (this.href && this.href !== '#') {
                    window.open(this.href, '_blank', 'noopener,noreferrer');
                }
            });
        });
    });
    </script>
</body>
</html>
