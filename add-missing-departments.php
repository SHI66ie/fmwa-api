<?php
// Script to add missing departments from backup to the current PHP site

$missing_departments = [
    'human-resource-management' => [
        'name' => 'Human Resource Management',
        'description' => 'Managing human capital and organizational development to enhance ministry performance and staff welfare.',
        'director' => 'Mrs. Folake Adeyemi',
        'email' => 'hr@fmwa.gov.ng',
        'phone' => '+234-9-461-0011',
        'objectives' => [
            'Develop and implement human resource policies and procedures',
            'Manage recruitment, selection, and staff development programs',
            'Ensure compliance with civil service rules and regulations',
            'Promote staff welfare and organizational development'
        ],
        'programs' => [
            ['icon' => 'fas fa-users', 'title' => 'Staff Development', 'desc' => 'Training and capacity building programs for ministry personnel.'],
            ['icon' => 'fas fa-clipboard-list', 'title' => 'Performance Management', 'desc' => 'Performance evaluation and improvement systems.'],
            ['icon' => 'fas fa-handshake', 'title' => 'Staff Welfare', 'desc' => 'Employee welfare programs and support services.'],
            ['icon' => 'fas fa-chart-line', 'title' => 'Organizational Development', 'desc' => 'Strategic planning and organizational improvement initiatives.']
        ]
    ],
    'special-duties' => [
        'name' => 'Special Duties',
        'description' => 'Addressing unique challenges and implementing special initiatives for women and children\'s welfare.',
        'director' => 'Dr. Zainab Usman',
        'email' => 'specialduties@fmwa.gov.ng',
        'phone' => '+234-9-461-0012',
        'objectives' => [
            'Handle special assignments and emergency interventions',
            'Coordinate inter-agency collaborations and partnerships',
            'Implement special programs for vulnerable populations',
            'Address emerging issues affecting women and children'
        ],
        'programs' => [
            ['icon' => 'fas fa-exclamation-triangle', 'title' => 'Emergency Response', 'desc' => 'Rapid response to crises affecting women and children.'],
            ['icon' => 'fas fa-link', 'title' => 'Inter-Agency Coordination', 'desc' => 'Collaboration with other ministries and agencies.'],
            ['icon' => 'fas fa-heart', 'title' => 'Vulnerable Groups Support', 'desc' => 'Special programs for at-risk populations.'],
            ['icon' => 'fas fa-lightbulb', 'title' => 'Innovation Projects', 'desc' => 'Pilot programs and innovative solutions.']
        ]
    ],
    'economic-services' => [
        'name' => 'Economic Services',
        'description' => 'Empowering women through economic opportunities, financial inclusion, and entrepreneurship development.',
        'director' => 'Mrs. Ngozi Okonkwo',
        'email' => 'economics@fmwa.gov.ng',
        'phone' => '+234-9-461-0013',
        'objectives' => [
            'Promote women\'s economic empowerment and financial inclusion',
            'Develop and implement entrepreneurship programs',
            'Facilitate access to microcredit and financial services',
            'Support women\'s cooperatives and trade associations'
        ],
        'programs' => [
            ['icon' => 'fas fa-coins', 'title' => 'Microfinance Programs', 'desc' => 'Access to credit and financial services for women entrepreneurs.'],
            ['icon' => 'fas fa-store', 'title' => 'Business Development', 'desc' => 'Training and support for women-owned businesses.'],
            ['icon' => 'fas fa-users-cog', 'title' => 'Cooperatives Support', 'desc' => 'Development and strengthening of women\'s cooperatives.'],
            ['icon' => 'fas fa-chart-bar', 'title' => 'Market Access', 'desc' => 'Connecting women producers to markets and value chains.']
        ]
    ]
];

// Create each missing department page
foreach ($missing_departments as $slug => $dept) {
    $filename = "departments/{$slug}.php";
    
    $content = "<?php
// Page configuration
\$page_title = \"{$dept['name']} Department - Federal Ministry of Women Affairs\";
\$department_name = \"{$dept['name']}\";
\$show_maintenance_notice = false; // Set to true if you want to show maintenance notice

// Include header
include '../components/header.php';
?>

<!-- Department Header -->
<section class=\"department-header\">
    <div class=\"container\">
        <nav aria-label=\"breadcrumb\">
            <ol class=\"breadcrumb\">
                <li class=\"breadcrumb-item\"><a href=\"../index.html\">Home</a></li>
                <li class=\"breadcrumb-item\"><a href=\"#\" onclick=\"history.back()\">Departments</a></li>
                <li class=\"breadcrumb-item active\" aria-current=\"page\"><?php echo \$department_name; ?></li>
            </ol>
        </nav>
        <h1><?php echo \$department_name; ?> Department</h1>
        <p class=\"lead\">{$dept['description']}</p>
    </div>
</section>

<!-- Department Content -->
<section class=\"department-content\">
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <!-- Main Content -->
                <div class=\"card shadow-sm mb-4\">
                    <div class=\"card-body\">
                        <h2 class=\"h4 mb-3\">About the Department</h2>
                        <p>{$dept['description']}</p>
                        
                        <h3 class=\"h5 mt-4 mb-3\">Our Objectives</h3>
                        <ul class=\"list-group list-group-flush\">";

    foreach ($dept['objectives'] as $objective) {
        $content .= "
                            <li class=\"list-group-item border-0 px-0\">
                                <i class=\"fas fa-check-circle text-success me-2\"></i>
                                {$objective}
                            </li>";
    }

    $content .= "
                        </ul>
                    </div>
                </div>

                <!-- Programs and Services -->
                <div class=\"card shadow-sm mb-4\">
                    <div class=\"card-body\">
                        <h2 class=\"h4 mb-3\">Programs and Services</h2>
                        <div class=\"row\">";

    foreach ($dept['programs'] as $program) {
        $content .= "
                            <div class=\"col-md-6 mb-3\">
                                <div class=\"d-flex\">
                                    <div class=\"flex-shrink-0\">
                                        <i class=\"{$program['icon']} text-primary fa-2x\"></i>
                                    </div>
                                    <div class=\"flex-grow-1 ms-3\">
                                        <h5>{$program['title']}</h5>
                                        <p class=\"text-muted\">{$program['desc']}</p>
                                    </div>
                                </div>
                            </div>";
    }

    $content .= "
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class=\"col-lg-4\">
                <!-- Contact Information -->
                <div class=\"card shadow-sm mb-4\">
                    <div class=\"card-header bg-primary text-white\">
                        <h5 class=\"card-title mb-0\">
                            <i class=\"fas fa-user-tie me-2\"></i>Department Director
                        </h5>
                    </div>
                    <div class=\"card-body text-center\">
                        <!-- Director Photo -->
                        <div class=\"director-photo-container mb-3\">
                            <img src=\"../images/directors/{$slug}-director.jpg\" 
                                 alt=\"{$dept['director']} - Director\" 
                                 class=\"director-photo img-fluid rounded-circle\"
                                 onerror=\"this.style.display='none'; this.nextElementSibling.style.display='block';\">
                            <div class=\"director-placeholder\" style=\"display: none;\">
                                <i class=\"fas fa-user-tie fa-3x text-white\"></i>
                                <div class=\"placeholder-text\">
                                    <small>DIRECTOR</small><br>
                                    <small>Photo Coming Soon</small>
                                </div>
                            </div>
                        </div>
                        <h6 class=\"mb-1\">{$dept['director']}</h6>
                        <p class=\"text-muted small mb-3\">Director, {$dept['name']}</p>
                        
                        <div class=\"contact-details text-start\">
                            <p class=\"mb-2\">
                                <i class=\"fas fa-phone text-primary me-2\"></i>
                                <strong>Phone:</strong> {$dept['phone']}
                            </p>
                            <p class=\"mb-2\">
                                <i class=\"fas fa-envelope text-primary me-2\"></i>
                                <strong>Email:</strong> {$dept['email']}
                            </p>
                            <p class=\"mb-0\">
                                <i class=\"fas fa-clock text-primary me-2\"></i>
                                <strong>Office Hours:</strong> Mon - Fri: 8:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class=\"card shadow-sm mb-4\">
                    <div class=\"card-header bg-success text-white\">
                        <h5 class=\"card-title mb-0\">
                            <i class=\"fas fa-link me-2\"></i>Related Departments
                        </h5>
                    </div>
                    <div class=\"card-body\">
                        <ul class=\"list-unstyled mb-0\">
                            <li class=\"mb-2\">
                                <a href=\"women-development.php\" class=\"text-decoration-none\">
                                    <i class=\"fas fa-arrow-right me-2\"></i>Women Development
                                </a>
                            </li>
                            <li class=\"mb-2\">
                                <a href=\"gender-affairs.php\" class=\"text-decoration-none\">
                                    <i class=\"fas fa-arrow-right me-2\"></i>Gender Affairs
                                </a>
                            </li>
                            <li class=\"mb-2\">
                                <a href=\"child-development.php\" class=\"text-decoration-none\">
                                    <i class=\"fas fa-arrow-right me-2\"></i>Child Development
                                </a>
                            </li>
                            <li class=\"mb-0\">
                                <a href=\"nutrition.php\" class=\"text-decoration-none\">
                                    <i class=\"fas fa-arrow-right me-2\"></i>Nutrition
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Latest News -->
                <div class=\"card shadow-sm\">
                    <div class=\"card-header bg-info text-white\">
                        <h5 class=\"card-title mb-0\">
                            <i class=\"fas fa-newspaper me-2\"></i>Latest Updates
                        </h5>
                    </div>
                    <div class=\"card-body\">
                        <div class=\"mb-3\">
                            <h6 class=\"card-subtitle mb-1\">Department Workshop 2025</h6>
                            <small class=\"text-muted\">March 15, 2025</small>
                            <p class=\"small mt-1\">Join us for our annual department workshop and training session.</p>
                        </div>
                        <div class=\"mb-3\">
                            <h6 class=\"card-subtitle mb-1\">New Program Launch</h6>
                            <small class=\"text-muted\">February 20, 2025</small>
                            <p class=\"small mt-1\">Exciting new initiatives to better serve our beneficiaries.</p>
                        </div>
                        <a href=\"#\" class=\"btn btn-sm btn-outline-info\">View All Updates</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include '../components/footer.php';
?>";

    file_put_contents($filename, $content);
    echo "Created: {$filename} for {$dept['director']}\n";
}

echo "\nMissing department pages have been created successfully!\n";
echo "\nNew departments added:\n";
foreach ($missing_departments as $slug => $dept) {
    echo "- {$dept['name']} (Director: {$dept['director']})\n";
}
?>
