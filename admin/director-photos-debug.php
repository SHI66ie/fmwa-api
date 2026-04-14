<?php
/**
 * Director Photos Debug Page
 * Shows detailed debugging information
 */

require_once 'config.php';
require_once 'auth.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Photos Debug - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1><i class="fas fa-bug me-2"></i>Director Photos Debug</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-database me-2"></i>Database Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Connected:</strong></td>
                                <td><?php echo $pdo ? '<span class="text-success">✅ Yes</span>' : '<span class="text-danger">❌ No</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Config Loaded:</strong></td>
                                <td><?php echo defined('DB_HOST') ? '<span class="text-success">✅ Yes</span>' : '<span class="text-danger">❌ No</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>DB Name:</strong></td>
                                <td><code><?php echo defined('DB_NAME') ? DB_NAME : 'Not defined'; ?></code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-user-shield me-2"></i>Authentication Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Session Status:</strong></td>
                                <td><code><?php echo session_status(); ?></code></td>
                            </tr>
                            <tr>
                                <td><strong>Session ID:</strong></td>
                                <td><code><?php echo session_id(); ?></code></td>
                            </tr>
                            <tr>
                                <td><strong>Logged In:</strong></td>
                                <td><?php echo $_SESSION['logged_in'] ?? false ? '<span class="text-success">✅ Yes</span>' : '<span class="text-danger">❌ No</span>'; ?></td>
                            </tr>
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <tr>
                                <td><strong>User ID:</strong></td>
                                <td><code><?php echo $_SESSION['user_id']; ?></code></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-file-code me-2"></i>File System Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Director Photos API:</strong></td>
                                <td><?php echo file_exists('api/media-director.php') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Media API:</strong></td>
                                <td><?php echo file_exists('api/media.php') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Directors Directory:</strong></td>
                                <td><?php echo is_dir('../images/directors') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Uploads Directory:</strong></td>
                                <td><?php echo is_dir('../images/uploads') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs me-2"></i>API Test Results</h5>
                    </div>
                    <div class="card-body">
                        <div id="apiTestResults">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Testing API endpoints...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tools me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="btn-group" role="group">
                            <a href="director-photos.php" class="btn btn-primary">
                                <i class="fas fa-image me-1"></i>Try Director Photos
                            </a>
                            <a href="media.php" class="btn btn-success">
                                <i class="fas fa-images me-1"></i>Media Library
                            </a>
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                            <a href="test-director-photos.php" class="btn btn-info">
                                <i class="fas fa-sync me-1"></i>Refresh Debug
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function testAPIs() {
            const resultsDiv = document.getElementById('apiTestResults');
            
            try {
                // Test Director Photos API
                const directorResponse = await fetch('api/media-director.php');
                const directorResult = await directorResponse.json();
                
                // Test Media API
                const mediaResponse = await fetch('api/media.php');
                const mediaResult = await mediaResponse.json();
                
                let html = '<div class="row">';
                
                // Director Photos API Result
                html += '<div class="col-md-6">';
                html += '<h6><i class="fas fa-user-tie me-2"></i>Director Photos API:</h6>';
                html += directorResult.success ? 
                    '<span class="text-success">✅ Working</span><br><small>' + JSON.stringify(directorResult.data).substring(0, 100) + '...</small>' :
                    '<span class="text-danger">❌ Error</span><br><small>' + directorResult.message + '</small>';
                html += '</div>';
                
                // Media API Result
                html += '<div class="col-md-6">';
                html += '<h6><i class="fas fa-images me-2"></i>Media API:</h6>';
                html += mediaResult.success ? 
                    '<span class="text-success">✅ Working</span><br><small>' + mediaResult.data?.length + ' files found</small>' :
                    '<span class="text-danger">❌ Error</span><br><small>' + mediaResult.message + '</small>';
                html += '</div>';
                
                html += '</div>';
                resultsDiv.innerHTML = html;
                
            } catch (error) {
                resultsDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>API Test Failed: ' + error.message + '</div>';
            }
        }
        
        // Run tests when page loads
        document.addEventListener('DOMContentLoaded', testAPIs);
    </script>
</body>
</html>
