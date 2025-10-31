<?php
// Create HTML redirect files for the new departments

$new_departments = [
    'human-resource-management',
    'special-duties', 
    'economic-services'
];

// Create HTML redirect files
foreach ($new_departments as $dept) {
    $html_content = "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Redirecting...</title>
    <meta http-equiv=\"refresh\" content=\"0; url={$dept}.php\">
    <link rel=\"canonical\" href=\"{$dept}.php\">
</head>
<body>
    <p>If you are not redirected automatically, <a href=\"{$dept}.php\">click here</a>.</p>
    <script>
        window.location.href = '{$dept}.php';
    </script>
</body>
</html>";

    $filename = "departments/{$dept}.html";
    file_put_contents($filename, $html_content);
    echo "Created redirect: {$filename} -> {$dept}.php\n";
}

echo "\nNew HTML redirect files created successfully!\n";
?>
