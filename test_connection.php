<?php
/**
 * Simple Database Connection Test
 * Tests if the database connection is working properly
 */

// Load configuration
require_once 'config.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin: 15px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin: 15px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        .check {
            color: #28a745;
            font-weight: bold;
        }
        .cross {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🗄️ Database Connection Test</h1>";

// Test 1: Configuration Check
echo "<h2>1. Configuration Check</h2>";
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>Database Host</td><td>" . DB_HOST . "</td></tr>";
echo "<tr><td>Database Name</td><td>" . DB_NAME . "</td></tr>";
echo "<tr><td>Database User</td><td>" . DB_USER . "</td></tr>";
echo "<tr><td>Password Set</td><td>" . (DB_PASS ? 'Yes' : 'No') . "</td></tr>";
echo "</table>";

// Test 2: PDO Extension Check
echo "<h2>2. PHP Extensions</h2>";
$pdoAvailable = extension_loaded('pdo');
$pdoMysqlAvailable = extension_loaded('pdo_mysql');

echo "<table>";
echo "<tr><th>Extension</th><th>Status</th></tr>";
echo "<tr><td>PDO</td><td>" . ($pdoAvailable ? "<span class='check'>✓ Available</span>" : "<span class='cross'>✗ Not Available</span>") . "</td></tr>";
echo "<tr><td>PDO MySQL</td><td>" . ($pdoMysqlAvailable ? "<span class='check'>✓ Available</span>" : "<span class='cross'>✗ Not Available</span>") . "</td></tr>";
echo "</table>";

if (!$pdoAvailable || !$pdoMysqlAvailable) {
    echo "<div class='error'><strong>Error:</strong> Required PHP extensions are not available. Please install PDO and PDO MySQL extensions.</div>";
    echo "</div></body></html>";
    exit;
}

// Test 3: Database Connection
echo "<h2>3. Database Connection</h2>";

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);
    
    echo "<div class='success'><strong>✓ Success!</strong> Connected to database successfully.</div>";
    
    // Get MySQL version
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "<div class='info'><strong>MySQL Version:</strong> " . $result->version . "</div>";
    
    // Test 4: Check Tables
    echo "<h2>4. Database Tables</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<div class='success'><strong>✓ Found " . count($tables) . " tables</strong></div>";
        echo "<table>";
        echo "<tr><th>Table Name</th><th>Row Count</th></tr>";
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch();
            echo "<tr><td>$table</td><td>" . $count->count . " rows</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'><strong>Warning:</strong> No tables found in database. Please run the schema.sql file to create tables.</div>";
    }
    
    // Test 5: Check Default Admin User
    echo "<h2>5. Default Admin User</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE username = 'admin'");
    $result = $stmt->fetch();
    
    if ($result->count > 0) {
        echo "<div class='success'><strong>✓ Admin user exists</strong></div>";
        echo "<div class='info'>
            <strong>Default Login Credentials:</strong><br>
            Username: admin<br>
            Password: admin123<br>
            <br>
            <strong>⚠️ IMPORTANT:</strong> Change this password immediately after first login!
        </div>";
    } else {
        echo "<div class='error'><strong>Warning:</strong> Admin user not found. Please import the schema.sql file.</div>";
    }
    
    // Test 6: Test Write Operations
    echo "<h2>6. Database Operations Test</h2>";
    
    try {
        // Test INSERT
        $stmt = $pdo->prepare("INSERT INTO activity_log (action, description, ip_address) VALUES (?, ?, ?)");
        $stmt->execute(['test_connection', 'Database connection test', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1']);
        $insertId = $pdo->lastInsertId();
        
        echo "<div class='success'><strong>✓ INSERT:</strong> Successfully inserted test record (ID: $insertId)</div>";
        
        // Test SELECT
        $stmt = $pdo->prepare("SELECT * FROM activity_log WHERE id = ?");
        $stmt->execute([$insertId]);
        $record = $stmt->fetch();
        
        echo "<div class='success'><strong>✓ SELECT:</strong> Successfully retrieved test record</div>";
        
        // Test UPDATE
        $stmt = $pdo->prepare("UPDATE activity_log SET description = ? WHERE id = ?");
        $stmt->execute(['Updated test record', $insertId]);
        
        echo "<div class='success'><strong>✓ UPDATE:</strong> Successfully updated test record</div>";
        
        // Test DELETE
        $stmt = $pdo->prepare("DELETE FROM activity_log WHERE id = ?");
        $stmt->execute([$insertId]);
        
        echo "<div class='success'><strong>✓ DELETE:</strong> Successfully deleted test record</div>";
        
        echo "<div class='success'><strong>✓ All database operations working correctly!</strong></div>";
        
    } catch (PDOException $e) {
        echo "<div class='error'><strong>Error during operations test:</strong> " . $e->getMessage() . "</div>";
    }
    
    // Final Summary
    echo "<h2>✅ Summary</h2>";
    echo "<div class='success'>
        <strong>Database is ready to use!</strong><br><br>
        Next steps:<br>
        1. Access the admin panel at <a href='/admin/login.php'>/admin/login.php</a><br>
        2. Login with username: admin, password: admin123<br>
        3. Change the default password immediately<br>
        4. Start creating content!
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>✗ Connection Failed!</strong><br><br>";
    echo "<strong>Error:</strong> " . $e->getMessage() . "<br><br>";
    echo "<strong>Troubleshooting:</strong><br>";
    echo "1. Check if MySQL service is running<br>";
    echo "2. Verify database credentials in config.php<br>";
    echo "3. Ensure database '" . DB_NAME . "' exists<br>";
    echo "4. Check if user '" . DB_USER . "' has proper permissions<br>";
    echo "</div>";
}

echo "    </div>
</body>
</html>";
?>
