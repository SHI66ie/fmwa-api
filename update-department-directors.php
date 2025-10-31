<?php
// Script to update all department pages with director photo placeholders

$departments = [
    'child-development' => [
        'name' => 'Child Development',
        'director' => 'Dr. Fatima Abdullahi',
        'email' => 'childdevelopment@fmwa.gov.ng',
        'phone' => '+234-9-461-0002'
    ],
    'community-development-social-intervention' => [
        'name' => 'Community Development & Social Intervention',
        'director' => 'Mallam Ibrahim Yusuf',
        'email' => 'community@fmwa.gov.ng',
        'phone' => '+234-9-461-0003'
    ],
    'finance-accounting' => [
        'name' => 'Finance & Accounting',
        'director' => 'Mrs. Adunni Ogundimu',
        'email' => 'finance@fmwa.gov.ng',
        'phone' => '+234-9-461-0004'
    ],
    'gender-affairs' => [
        'name' => 'Gender Affairs',
        'director' => 'Dr. Hauwa Mohammed',
        'email' => 'gender@fmwa.gov.ng',
        'phone' => '+234-9-461-0005'
    ],
    'general-services' => [
        'name' => 'General Services',
        'director' => 'Mr. Chukwuma Okafor',
        'email' => 'services@fmwa.gov.ng',
        'phone' => '+234-9-461-0006'
    ],
    'nutrition' => [
        'name' => 'Nutrition',
        'director' => 'Dr. Kemi Adebayo',
        'email' => 'nutrition@fmwa.gov.ng',
        'phone' => '+234-9-461-0007'
    ],
    'planning-research-statistics' => [
        'name' => 'Planning, Research & Statistics',
        'director' => 'Prof. Aisha Garba',
        'email' => 'planning@fmwa.gov.ng',
        'phone' => '+234-9-461-0008'
    ],
    'procurement' => [
        'name' => 'Procurement',
        'director' => 'Mrs. Blessing Okoro',
        'email' => 'procurement@fmwa.gov.ng',
        'phone' => '+234-9-461-0009'
    ],
    'reform-coordination-service-improvement' => [
        'name' => 'Reform Coordination & Service Improvement',
        'director' => 'Dr. Musa Abdullahi',
        'email' => 'reform@fmwa.gov.ng',
        'phone' => '+234-9-461-0010'
    ]
];

// Update each department page (excluding women-development which is already updated)
foreach ($departments as $slug => $dept) {
    $filename = "departments/{$slug}.php";
    
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        
        // Find and replace the contact information section
        $oldPattern = '/<!-- Contact Information -->.*?<div class="card shadow-sm mb-4">.*?<div class="card-header bg-primary text-white">.*?<h5 class="card-title mb-0">.*?<i class="fas fa-phone me-2"><\/i>Contact Information.*?<\/h5>.*?<\/div>.*?<div class="card-body">.*?<p class="mb-2">.*?<strong>Director:<\/strong><br>.*?' . preg_quote($dept['director'], '/') . '.*?<\/p>.*?<p class="mb-2">.*?<strong>Phone:<\/strong><br>.*?' . preg_quote($dept['phone'], '/') . '.*?<\/p>.*?<p class="mb-2">.*?<strong>Email:<\/strong><br>.*?' . preg_quote($dept['email'], '/') . '.*?<\/p>.*?<p class="mb-0">.*?<strong>Office Hours:<\/strong><br>.*?Monday - Friday: 8:00 AM - 4:00 PM.*?<\/p>.*?<\/div>.*?<\/div>/s';
        
        $newContactSection = '<!-- Contact Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tie me-2"></i>Department Director
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Director Photo -->
                        <div class="director-photo-container mb-3">
                            <img src="../images/directors/' . $slug . '-director.jpg" 
                                 alt="' . $dept['director'] . ' - Director" 
                                 class="director-photo img-fluid rounded-circle"
                                 onerror="this.src=\'../images/directors/placeholder-director.svg\'">
                        </div>
                        <h6 class="mb-1">' . $dept['director'] . '</h6>
                        <p class="text-muted small mb-3">Director, ' . $dept['name'] . '</p>
                        
                        <div class="contact-details text-start">
                            <p class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Phone:</strong> ' . $dept['phone'] . '
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong> ' . $dept['email'] . '
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Office Hours:</strong> Mon - Fri: 8:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>
                </div>';
        
        // Try a simpler replacement approach
        $patterns = [
            '/(<div class="card-header bg-primary text-white">.*?<i class="fas fa-phone me-2"><\/i>Contact Information.*?<\/div>.*?<div class="card-body">.*?<\/div>.*?<\/div>)/s',
            '/(<div class="card shadow-sm mb-4">.*?Contact Information.*?<\/div>)/s'
        ];
        
        $replaced = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $newContactSection, $content);
                $replaced = true;
                break;
            }
        }
        
        if (!$replaced) {
            // Manual replacement for specific structure
            $searchFor = '<i class="fas fa-phone me-2"></i>Contact Information';
            $replaceWith = '<i class="fas fa-user-tie me-2"></i>Department Director';
            
            if (strpos($content, $searchFor) !== false) {
                $content = str_replace($searchFor, $replaceWith, $content);
                
                // Replace the card body content
                $bodyStart = strpos($content, '<div class="card-body">', strpos($content, $replaceWith));
                $bodyEnd = strpos($content, '</div>', $bodyStart) + 6;
                
                if ($bodyStart !== false && $bodyEnd !== false) {
                    $newBody = '<div class="card-body text-center">
                        <!-- Director Photo -->
                        <div class="director-photo-container mb-3">
                            <img src="../images/directors/' . $slug . '-director.jpg" 
                                 alt="' . $dept['director'] . ' - Director" 
                                 class="director-photo img-fluid rounded-circle"
                                 onerror="this.src=\'../images/directors/placeholder-director.svg\'">
                        </div>
                        <h6 class="mb-1">' . $dept['director'] . '</h6>
                        <p class="text-muted small mb-3">Director, ' . $dept['name'] . '</p>
                        
                        <div class="contact-details text-start">
                            <p class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Phone:</strong> ' . $dept['phone'] . '
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong> ' . $dept['email'] . '
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Office Hours:</strong> Mon - Fri: 8:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>';
                    
                    $content = substr_replace($content, $newBody, $bodyStart, $bodyEnd - $bodyStart);
                    $replaced = true;
                }
            }
        }
        
        if ($replaced) {
            file_put_contents($filename, $content);
            echo "Updated: {$filename} with director photo for {$dept['director']}\n";
        } else {
            echo "Could not update: {$filename} - pattern not found\n";
        }
    } else {
        echo "File not found: {$filename}\n";
    }
}

echo "\nDirector photo placeholders have been added to all department pages!\n";
echo "Photo files expected:\n";
foreach ($departments as $slug => $dept) {
    echo "- images/directors/{$slug}-director.jpg (for {$dept['director']})\n";
}
echo "- images/directors/placeholder-director.svg (fallback)\n";
?>
