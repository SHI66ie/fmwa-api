<?php
/**
 * Videos Debug Page
 * Shows detailed debugging information for videos
 */

require_once '../config.php';
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
    <title>Videos Debug - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1><i class="fas fa-bug me-2"></i>Videos Debug</h1>
        
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
                        <h5><i class="fas fa-video me-2"></i>Video Database Query</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            // Test basic query
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM media");
                            $total_media = $stmt->fetch()['count'];
                            echo "<p><strong>Total Media Files:</strong> $total_media</p>";
                            
                            // Test video query
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM media WHERE file_type = 'video'");
                            $video_count = $stmt->fetch()['count'];
                            echo "<p><strong>Video Files:</strong> $video_count</p>";
                            
                            // Show all file types
                            $stmt = $pdo->query("SELECT file_type, COUNT(*) as count FROM media GROUP BY file_type");
                            $file_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<h6>File Types Found:</h6>";
                            echo "<ul>";
                            foreach ($file_types as $type) {
                                echo "<li><strong>{$type['file_type']}:</strong> {$type['count']} files</li>";
                            }
                            echo "</ul>";
                            
                            // Show recent videos if any
                            if ($video_count > 0) {
                                $stmt = $pdo->query("SELECT * FROM media WHERE file_type = 'video' ORDER BY created_at DESC LIMIT 5");
                                $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                echo "<h6>Recent Videos:</h6>";
                                echo "<table class='table table-sm'>";
                                echo "<tr><th>ID</th><th>Filename</th><th>Size</th><th>Created</th><th>File Exists</th></tr>";
                                foreach ($videos as $video) {
                                    $file_path = '../' . $video['file_url'];
                                    $file_exists = file_exists($file_path);
                                    echo "<tr>";
                                    echo "<td>{$video['id']}</td>";
                                    echo "<td>{$video['original_filename']}</td>";
                                    echo "<td>" . number_format($video['file_size'] / 1024 / 1024, 2) . " MB</td>";
                                    echo "<td>{$video['created_at']}</td>";
                                    echo "<td>" . ($file_exists ? '<span class="text-success">✅ Yes</span>' : '<span class="text-danger">❌ No</span>') . "</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                            }
                            
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">Database Error: ' . $e->getMessage() . '</div>';
                        }
                        ?>
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
                                <td><strong>Videos API:</strong></td>
                                <td><?php echo file_exists('api/videos-api.php') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Media API:</strong></td>
                                <td><?php echo file_exists('api/media.php') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Uploads Directory:</strong></td>
                                <td><?php echo is_dir('../images/uploads') ? '<span class="text-success">✅ Exists</span>' : '<span class="text-danger">❌ Missing</span>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Uploads Writable:</strong></td>
                                <td><?php echo is_writable('../images/uploads') ? '<span class="text-success">✅ Yes</span>' : '<span class="text-danger">❌ No</span>'; ?></td>
                            </tr>
                        </table>
                        
                        <?php
                        // Check for video files in uploads directory
                        if (is_dir('../images/uploads')) {
                            $files = scandir('../images/uploads');
                            $video_files = array_filter($files, function($file) {
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                return in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
                            });
                            
                            if (!empty($video_files)) {
                                echo "<h6>Video Files in Uploads Directory:</h6>";
                                echo "<ul>";
                                foreach ($video_files as $file) {
                                    echo "<li><code>$file</code></li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p class='text-muted'>No video files found in uploads directory</p>";
                            }
                        }
                        ?>
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
                                <p class="mt-2">Testing video APIs...</p>
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
                            <a href="videos.php" class="btn btn-primary">
                                <i class="fas fa-video me-1"></i>Try Videos Page
                            </a>
                            <a href="media.php" class="btn btn-success">
                                <i class="fas fa-images me-1"></i>Media Library
                            </a>
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                            <a href="videos-debug.php" class="btn btn-info">
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
                // Test Videos API
                const videosResponse = await fetch('api/videos-api.php');
                const videosResult = await videosResponse.json();
                
                // Test Media API
                const mediaResponse = await fetch('api/media.php');
                const mediaResult = await mediaResponse.json();
                
                let html = '<div class="row">';
                
                // Videos API Result
                html += '<div class="col-md-6">';
                html += '<h6><i class="fas fa-video me-2"></i>Videos API:</h6>';
                html += videosResult.success ? 
                    '<span class="text-success">✅ Working</span><br><small>' + (videosResult.data?.length || 0) + ' videos found</small>' :
                    '<span class="text-danger">❌ Error</span><br><small>' + videosResult.message + '</small>';
                html += '</div>';
                
                // Media API Result
                html += '<div class="col-md-6">';
                html += '<h6><i class="fas fa-images me-2"></i>Media API:</h6>';
                html += mediaResult.success ? 
                    '<span class="text-success">✅ Working</span><br><small>' + (mediaResult.data?.length || 0) + ' files found</small>' :
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
