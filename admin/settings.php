<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();

// Get system info
$phpVersion = phpversion();
$mysqlVersion = $pdo->query('SELECT VERSION()')->fetchColumn();
$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';

// Get storage info
$uploadDir = '../images/uploads';
$uploadSize = 0;
if (is_dir($uploadDir)) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadDir));
    foreach ($files as $file) {
        if ($file->isFile()) {
            $uploadSize += $file->getSize();
        }
    }
}

// Get database size
try {
    $stmt = $pdo->query("SELECT SUM(data_length + index_length) as size FROM information_schema.TABLES WHERE table_schema = DATABASE()");
    $dbSize = $stmt->fetch()['size'] ?? 0;
} catch (Exception $e) {
    $dbSize = 0;
}

function formatBytes($bytes) {
    if ($bytes == 0) return '0 B';
    $k = 1024;
    $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sidebar-width: 260px;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--primary-gradient);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            margin: 5px 15px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }
        
        .top-bar {
            background: white;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .info-row {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            color: #6c757d;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .action-btn {
            width: 100%;
            margin-bottom: 10px;
            text-align: left;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">
                <i class="fas fa-shield-alt me-2"></i>
                FMWA Admin
            </h4>
            <small class="text-white-50">Content Management</small>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="posts.php" class="nav-link">
                    <i class="fas fa-newspaper"></i>
                    Posts & News
                </a>
            </div>
            <div class="nav-item">
                <a href="media.php" class="nav-link">
                    <i class="fas fa-images"></i>
                    Media Library
                </a>
            </div>
            <div class="nav-item">
                <a href="pages.php" class="nav-link">
                    <i class="fas fa-file-code"></i>
                    Page Editor
                </a>
            </div>
            <div class="nav-item">
                <a href="categories.php" class="nav-link">
                    <i class="fas fa-tags"></i>
                    Categories
                </a>
            </div>
            <div class="nav-item">
                <a href="settings.php" class="nav-link active">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </div>
            <div class="nav-item mt-4">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h3 class="mb-0">Settings</h3>
            <small class="text-muted">System information and user settings</small>
        </div>

        <div class="row">
            <!-- User Profile -->
            <div class="col-md-6">
                <div class="content-card">
                    <h5 class="mb-4"><i class="fas fa-user me-2"></i>User Profile</h5>
                    
                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <h5><?php echo htmlspecialchars($user['username']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                        <span class="badge bg-success"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">
                            <?php echo date('F j, Y', strtotime($user['created_at'])); ?>
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Last Login</span>
                        <span class="info-value">
                            <?php 
                            if (isset($user['last_login']) && $user['last_login']) {
                                echo date('F j, Y g:i A', strtotime($user['last_login']));
                            } else {
                                echo 'Never';
                            }
                            ?>
                        </span>
                    </div>
                    
                    <div class="mt-4">
                        <button class="btn btn-outline-primary action-btn" onclick="alert('Password change feature coming soon!')">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                        <button class="btn btn-outline-secondary action-btn" onclick="alert('Profile edit feature coming soon!')">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- System Information -->
            <div class="col-md-6">
                <div class="content-card">
                    <h5 class="mb-4"><i class="fas fa-server me-2"></i>System Information</h5>
                    
                    <div class="info-row">
                        <span class="info-label">PHP Version</span>
                        <span class="info-value"><?php echo $phpVersion; ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">MySQL Version</span>
                        <span class="info-value"><?php echo $mysqlVersion; ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Server Software</span>
                        <span class="info-value"><?php echo $serverSoftware; ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Upload Directory Size</span>
                        <span class="info-value"><?php echo formatBytes($uploadSize); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Database Size</span>
                        <span class="info-value"><?php echo formatBytes($dbSize); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Max Upload Size</span>
                        <span class="info-value"><?php echo ini_get('upload_max_filesize'); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Memory Limit</span>
                        <span class="info-value"><?php echo ini_get('memory_limit'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-primary w-100" onclick="window.location.href='posts.php'">
                        <i class="fas fa-newspaper fa-2x mb-2 d-block"></i>
                        Manage Posts
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-success w-100" onclick="window.location.href='media.php'">
                        <i class="fas fa-images fa-2x mb-2 d-block"></i>
                        Upload Media
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-info w-100" onclick="window.location.href='pages.php'">
                        <i class="fas fa-file-code fa-2x mb-2 d-block"></i>
                        Edit Pages
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-warning w-100" onclick="window.location.href='categories.php'">
                        <i class="fas fa-tags fa-2x mb-2 d-block"></i>
                        Categories
                    </button>
                </div>
            </div>
        </div>

        <!-- Help & Documentation -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-question-circle me-2"></i>Help & Documentation</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-book me-2"></i>Documentation</h6>
                        <p class="mb-2">Access the comprehensive admin panel documentation:</p>
                        <ul class="mb-0">
                            <li><a href="../GETTING_STARTED.md" target="_blank">Getting Started Guide</a></li>
                            <li><a href="../ADMIN_README.md" target="_blank">Admin README</a></li>
                            <li><a href="../ADMIN_SETUP_GUIDE.md" target="_blank">Setup Guide</a></li>
                            <li><a href="../ADMIN_SUMMARY.md" target="_blank">Technical Summary</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                        <ul class="mb-0">
                            <li>Always backup your database before making changes</li>
                            <li>Use HTTPS in production environments</li>
                            <li>Change default passwords immediately</li>
                            <li>Monitor activity logs regularly</li>
                            <li>Keep PHP and MySQL updated</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-life-ring me-2"></i>Support</h5>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h6>Email Support</h6>
                        <p class="text-muted">admin@fmwa.gov.ng</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <i class="fas fa-bug fa-3x text-danger mb-3"></i>
                        <h6>Report Issues</h6>
                        <p class="text-muted">Check error logs for details</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <i class="fas fa-code fa-3x text-success mb-3"></i>
                        <h6>Version</h6>
                        <p class="text-muted">FMWA Admin v1.0.0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
