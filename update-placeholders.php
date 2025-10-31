<?php
// Update all department pages with the new placeholder structure

$departments = [
    'child-development' => 'Dr. Fatima Abdullahi',
    'community-development-social-intervention' => 'Mallam Ibrahim Yusuf',
    'finance-accounting' => 'Mrs. Adunni Ogundimu',
    'gender-affairs' => 'Dr. Hauwa Mohammed',
    'general-services' => 'Mr. Chukwuma Okafor',
    'nutrition' => 'Dr. Kemi Adebayo',
    'planning-research-statistics' => 'Prof. Aisha Garba',
    'procurement' => 'Mrs. Blessing Okoro',
    'reform-coordination-service-improvement' => 'Dr. Musa Abdullahi'
];

foreach ($departments as $slug => $director) {
    $filename = "departments/{$slug}.php";
    
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        
        // Replace the image tag with the new structure
        $oldPattern = '/onerror="this\.src=\'\.\.\/images\/directors\/placeholder-director\.svg\'"/';
        $newReplacement = 'onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">
                            <div class="director-placeholder" style="display: none;">
                                <i class="fas fa-user-tie fa-3x text-white"></i>
                                <div class="placeholder-text">
                                    <small>DIRECTOR</small><br>
                                    <small>Photo Coming Soon</small>
                                </div>
                            </div';
        
        $content = preg_replace($oldPattern, $newReplacement, $content);
        
        file_put_contents($filename, $content);
        echo "Updated placeholder for: {$filename}\n";
    }
}

echo "\nAll department pages updated with CSS-based placeholders!\n";
?>
