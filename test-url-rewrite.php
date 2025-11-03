<?php
// Test file to verify URL rewriting is working
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Rewrite Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 5px; }
        .info { color: #17a2b8; background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .test-links { margin: 20px 0; }
        .test-links a { display: block; margin: 5px 0; padding: 10px; background: #f8f9fa; text-decoration: none; border-radius: 3px; }
        .test-links a:hover { background: #e9ecef; }
    </style>
</head>
<body>
    <h1>URL Rewrite Test - SUCCESS!</h1>
    
    <div class="success">
        <strong>✓ URL Rewriting is Working!</strong><br>
        If you can see this page, your .htaccess rules are functioning correctly.
    </div>
    
    <div class="info">
        <strong>Current URL:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?><br>
        <strong>Script Name:</strong> <?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?><br>
        <strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?>
    </div>
    
    <h2>Test These URLs:</h2>
    <div class="test-links">
        <a href="/services/mail">✓ /services/mail (should work - no .php extension)</a>
        <a href="/services/spesse">✓ /services/spesse (should work - no .php extension)</a>
        <a href="/services/happy-woman">✓ /services/happy-woman (should work - no .php extension)</a>
        <a href="/about">✓ /about (should work if about.php exists)</a>
        <a href="/mandate">✓ /mandate (should work if mandate.php exists)</a>
    </div>
    
    <h2>These URLs Should Redirect:</h2>
    <div class="test-links">
        <a href="/services/mail.php">→ /services/mail.php (should redirect to clean URL)</a>
        <a href="/test-url-rewrite.php">→ /test-url-rewrite.php (should redirect to /test-url-rewrite)</a>
    </div>
    
    <p><strong>Note:</strong> If any links above don't work, check that the corresponding PHP files exist in your project.</p>
</body>
</html>
