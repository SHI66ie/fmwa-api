<?php
// Script to create all department pages with consistent header and footer

$departments = [
    'child-development' => [
        'name' => 'Child Development',
        'description' => 'Protecting and nurturing children through comprehensive development programs and child welfare initiatives.',
        'director' => 'Dr. Fatima Abdullahi',
        'email' => 'childdevelopment@fmwa.gov.ng',
        'phone' => '+234-9-461-0002',
        'objectives' => [
            'Ensure child protection and welfare across Nigeria',
            'Implement early childhood development programs',
            'Combat child abuse, trafficking, and exploitation',
            'Promote children\'s rights and access to education'
        ],
        'programs' => [
            ['icon' => 'fas fa-baby', 'title' => 'Early Childhood Care', 'desc' => 'Comprehensive care programs for children aged 0-5 years.'],
            ['icon' => 'fas fa-graduation-cap', 'title' => 'Education Support', 'desc' => 'Educational assistance and scholarship programs for disadvantaged children.'],
            ['icon' => 'fas fa-shield-alt', 'title' => 'Child Protection', 'desc' => 'Prevention and response services for child abuse and trafficking.'],
            ['icon' => 'fas fa-heart', 'title' => 'Health & Nutrition', 'desc' => 'Healthcare and nutritional support for children and mothers.']
        ]
    ],
    'community-development-social-intervention' => [
        'name' => 'Community Development & Social Intervention',
        'description' => 'Building stronger communities through social intervention programs and community-based development initiatives.',
        'director' => 'Mallam Ibrahim Yusuf',
        'email' => 'community@fmwa.gov.ng',
        'phone' => '+234-9-461-0003',
        'objectives' => [
            'Strengthen community resilience and social cohesion',
            'Implement poverty reduction and livelihood programs',
            'Promote community participation in development',
            'Address social issues through targeted interventions'
        ],
        'programs' => [
            ['icon' => 'fas fa-hands-helping', 'title' => 'Community Mobilization', 'desc' => 'Organizing communities for collective development action.'],
            ['icon' => 'fas fa-seedling', 'title' => 'Livelihood Programs', 'desc' => 'Income generation and skills development for community members.'],
            ['icon' => 'fas fa-home', 'title' => 'Social Housing', 'desc' => 'Affordable housing initiatives for vulnerable populations.'],
            ['icon' => 'fas fa-users', 'title' => 'Social Cohesion', 'desc' => 'Programs to promote unity and peaceful coexistence.']
        ]
    ],
    'finance-accounting' => [
        'name' => 'Finance & Accounting',
        'description' => 'Managing financial resources and ensuring transparent, accountable financial operations across all ministry programs.',
        'director' => 'Mrs. Adunni Ogundimu',
        'email' => 'finance@fmwa.gov.ng',
        'phone' => '+234-9-461-0004',
        'objectives' => [
            'Ensure efficient financial management and accountability',
            'Implement transparent budgeting and procurement processes',
            'Monitor and evaluate program financial performance',
            'Maintain compliance with government financial regulations'
        ],
        'programs' => [
            ['icon' => 'fas fa-calculator', 'title' => 'Budget Management', 'desc' => 'Strategic budget planning and allocation for ministry programs.'],
            ['icon' => 'fas fa-chart-line', 'title' => 'Financial Monitoring', 'desc' => 'Real-time tracking of expenditures and financial performance.'],
            ['icon' => 'fas fa-file-invoice', 'title' => 'Procurement Services', 'desc' => 'Transparent procurement processes for ministry operations.'],
            ['icon' => 'fas fa-balance-scale', 'title' => 'Audit & Compliance', 'desc' => 'Internal audit and regulatory compliance management.']
        ]
    ],
    'gender-affairs' => [
        'name' => 'Gender Affairs',
        'description' => 'Promoting gender equality and mainstreaming gender perspectives in policies, programs, and development initiatives.',
        'director' => 'Dr. Hauwa Mohammed',
        'email' => 'gender@fmwa.gov.ng',
        'phone' => '+234-9-461-0005',
        'objectives' => [
            'Mainstream gender perspectives in all government policies',
            'Promote equal opportunities for men and women',
            'Address gender-based discrimination and violence',
            'Strengthen gender-responsive budgeting and planning'
        ],
        'programs' => [
            ['icon' => 'fas fa-balance-scale', 'title' => 'Gender Mainstreaming', 'desc' => 'Integration of gender perspectives in policy development.'],
            ['icon' => 'fas fa-fist-raised', 'title' => 'Anti-GBV Programs', 'desc' => 'Prevention and response to gender-based violence.'],
            ['icon' => 'fas fa-chart-bar', 'title' => 'Gender Statistics', 'desc' => 'Collection and analysis of gender-disaggregated data.'],
            ['icon' => 'fas fa-handshake', 'title' => 'Advocacy & Awareness', 'desc' => 'Public awareness campaigns on gender equality.']
        ]
    ],
    'general-services' => [
        'name' => 'General Services',
        'description' => 'Providing essential administrative and support services to ensure efficient ministry operations and service delivery.',
        'director' => 'Mr. Chukwuma Okafor',
        'email' => 'services@fmwa.gov.ng',
        'phone' => '+234-9-461-0006',
        'objectives' => [
            'Provide efficient administrative support services',
            'Maintain ministry facilities and infrastructure',
            'Ensure effective communication and information systems',
            'Support human resource management and development'
        ],
        'programs' => [
            ['icon' => 'fas fa-building', 'title' => 'Facility Management', 'desc' => 'Maintenance and management of ministry buildings and facilities.'],
            ['icon' => 'fas fa-phone', 'title' => 'Communication Services', 'desc' => 'Telephone, internet, and communication infrastructure.'],
            ['icon' => 'fas fa-car', 'title' => 'Transport Services', 'desc' => 'Vehicle fleet management and transportation services.'],
            ['icon' => 'fas fa-tools', 'title' => 'Maintenance & Repair', 'desc' => 'Equipment maintenance and repair services.']
        ]
    ],
    'nutrition' => [
        'name' => 'Nutrition',
        'description' => 'Improving nutritional status and food security for women, children, and vulnerable populations across Nigeria.',
        'director' => 'Dr. Kemi Adebayo',
        'email' => 'nutrition@fmwa.gov.ng',
        'phone' => '+234-9-461-0007',
        'objectives' => [
            'Reduce malnutrition among women and children',
            'Promote optimal infant and young child feeding practices',
            'Strengthen food security and nutrition systems',
            'Implement nutrition education and awareness programs'
        ],
        'programs' => [
            ['icon' => 'fas fa-apple-alt', 'title' => 'Nutrition Education', 'desc' => 'Community-based nutrition education and counseling programs.'],
            ['icon' => 'fas fa-baby', 'title' => 'Maternal Nutrition', 'desc' => 'Nutritional support for pregnant and lactating mothers.'],
            ['icon' => 'fas fa-utensils', 'title' => 'School Feeding', 'desc' => 'Nutritious meal programs in schools and communities.'],
            ['icon' => 'fas fa-seedling', 'title' => 'Food Security', 'desc' => 'Agricultural and food production support programs.']
        ]
    ],
    'planning-research-statistics' => [
        'name' => 'Planning, Research & Statistics',
        'description' => 'Providing strategic planning, research, and statistical analysis to inform evidence-based policy making and program implementation.',
        'director' => 'Prof. Aisha Garba',
        'email' => 'planning@fmwa.gov.ng',
        'phone' => '+234-9-461-0008',
        'objectives' => [
            'Develop strategic plans and policy frameworks',
            'Conduct research on women and children\'s issues',
            'Generate and analyze statistical data for decision making',
            'Monitor and evaluate program implementation and impact'
        ],
        'programs' => [
            ['icon' => 'fas fa-chart-line', 'title' => 'Strategic Planning', 'desc' => 'Development of ministry strategic plans and policy frameworks.'],
            ['icon' => 'fas fa-search', 'title' => 'Research Studies', 'desc' => 'Conducting research on gender, women, and children\'s issues.'],
            ['icon' => 'fas fa-database', 'title' => 'Data Management', 'desc' => 'Collection, analysis, and management of statistical data.'],
            ['icon' => 'fas fa-clipboard-check', 'title' => 'M&E Systems', 'desc' => 'Monitoring and evaluation of programs and projects.']
        ]
    ],
    'procurement' => [
        'name' => 'Procurement',
        'description' => 'Ensuring transparent, efficient, and cost-effective procurement of goods and services for ministry operations and programs.',
        'director' => 'Mrs. Blessing Okoro',
        'email' => 'procurement@fmwa.gov.ng',
        'phone' => '+234-9-461-0009',
        'objectives' => [
            'Implement transparent and competitive procurement processes',
            'Ensure value for money in all procurement activities',
            'Maintain compliance with procurement laws and regulations',
            'Support ministry programs through timely procurement'
        ],
        'programs' => [
            ['icon' => 'fas fa-gavel', 'title' => 'Tender Management', 'desc' => 'Managing competitive bidding and tender processes.'],
            ['icon' => 'fas fa-handshake', 'title' => 'Vendor Relations', 'desc' => 'Supplier registration, evaluation, and relationship management.'],
            ['icon' => 'fas fa-file-contract', 'title' => 'Contract Management', 'desc' => 'Contract negotiation, execution, and performance monitoring.'],
            ['icon' => 'fas fa-shield-alt', 'title' => 'Compliance Monitoring', 'desc' => 'Ensuring adherence to procurement laws and best practices.']
        ]
    ],
    'reform-coordination-service-improvement' => [
        'name' => 'Reform Coordination & Service Improvement',
        'description' => 'Leading organizational reforms and continuous improvement initiatives to enhance service delivery and operational efficiency.',
        'director' => 'Dr. Musa Abdullahi',
        'email' => 'reform@fmwa.gov.ng',
        'phone' => '+234-9-461-0010',
        'objectives' => [
            'Coordinate ministry-wide reform initiatives',
            'Improve service delivery and customer satisfaction',
            'Implement digital transformation and innovation',
            'Strengthen organizational capacity and performance'
        ],
        'programs' => [
            ['icon' => 'fas fa-cogs', 'title' => 'Process Improvement', 'desc' => 'Streamlining processes and eliminating bureaucratic bottlenecks.'],
            ['icon' => 'fas fa-laptop', 'title' => 'Digital Services', 'desc' => 'Implementing digital platforms for improved service delivery.'],
            ['icon' => 'fas fa-users-cog', 'title' => 'Capacity Building', 'desc' => 'Staff training and development for enhanced performance.'],
            ['icon' => 'fas fa-chart-bar', 'title' => 'Performance Management', 'desc' => 'Monitoring and improving organizational performance metrics.']
        ]
    ]
];

// Create each department page
foreach ($departments as $slug => $dept) {
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
                            <i class=\"fas fa-phone me-2\"></i>Contact Information
                        </h5>
                    </div>
                    <div class=\"card-body\">
                        <p class=\"mb-2\">
                            <strong>Director:</strong><br>
                            {$dept['director']}
                        </p>
                        <p class=\"mb-2\">
                            <strong>Phone:</strong><br>
                            {$dept['phone']}
                        </p>
                        <p class=\"mb-2\">
                            <strong>Email:</strong><br>
                            {$dept['email']}
                        </p>
                        <p class=\"mb-0\">
                            <strong>Office Hours:</strong><br>
                            Monday - Friday: 8:00 AM - 4:00 PM
                        </p>
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
    echo "Created: {$filename}\n";
}

echo "\nAll department pages have been created successfully!\n";
echo "Each page includes:\n";
echo "- Consistent header with navigation\n";
echo "- Department-specific content\n";
echo "- Contact information\n";
echo "- Related links\n";
echo "- Professional footer\n";
?>
