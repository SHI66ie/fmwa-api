<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();

// Get dashboard statistics
try {
    // Count posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 'published'");
    $published_posts = $stmt->fetch()['count'] ?? 0;
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 'draft'");
    $draft_posts = $stmt->fetch()['count'] ?? 0;
    
    // Count media files
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM media");
    $media_count = $stmt->fetch()['count'] ?? 0;
    
    // Count categories
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories WHERE status = 'active'");
    $categories_count = $stmt->fetch()['count'] ?? 0;
    
    // Get recent posts
    $stmt = $pdo->query("SELECT title, created_at, status FROM posts ORDER BY created_at DESC LIMIT 5");
    $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get recent activity
    $stmt = $pdo->query("SELECT u.username, al.action, al.description, al.created_at 
                        FROM activity_logs al 
                        LEFT JOIN users u ON al.user_id = u.id 
                        ORDER BY al.created_at DESC LIMIT 10");
    $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $published_posts = $draft_posts = $media_count = $categories_count = 0;
    $recent_posts = $recent_activity = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMWA Admin Dashboard</title>
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
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }
        
        .stats-card.success::before {
            background: var(--success-gradient);
        }
        
        .stats-card.warning::before {
            background: var(--warning-gradient);
        }
        
        .stats-card.info::before {
            background: var(--info-gradient);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stats-icon {
            position: absolute;
            right: 30px;
            top: 30px;
            font-size: 3rem;
            opacity: 0.1;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 0.9rem;
        }
        
        .activity-icon.login {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .activity-icon.logout {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .activity-icon.create {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
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
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .top-bar {
                padding: 15px 20px;
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
                <a href="dashboard.php" class="nav-link active">
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
                <a href="settings.php" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </div>
            <hr class="my-3" style="border-color: rgba(255,255,255,0.1);">
            <div class="nav-item">
                <a href="../index.php" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    View Website
                </a>
            </div>
            <div class="nav-item">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h2 class="mb-0">Dashboard</h2>
                <small class="text-muted">Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</small>
            </div>
            <div class="text-end">
                <span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span>
                <small class="text-muted ms-3">
                    <i class="fas fa-clock me-1"></i>
                    <?php echo date('M j, Y g:i A'); ?>
                </small>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-number text-primary"><?php echo $published_posts; ?></div>
                    <div class="stats-label">Published Posts</div>
                    <i class="fas fa-newspaper stats-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card success">
                    <div class="stats-number text-success"><?php echo $draft_posts; ?></div>
                    <div class="stats-label">Draft Posts</div>
                    <i class="fas fa-edit stats-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card warning">
                    <div class="stats-number text-warning"><?php echo $media_count; ?></div>
                    <div class="stats-label">Media Files</div>
                    <i class="fas fa-images stats-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card info">
                    <div class="stats-number text-info"><?php echo $categories_count; ?></div>
                    <div class="stats-label">Categories</div>
                    <i class="fas fa-tags stats-icon"></i>
                </div>
            </div>
        </div>
        
        <!-- Content Row -->
        <div class="row">
            <!-- Recent Posts -->
            <div class="col-lg-8 mb-4">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Recent Posts</h5>
                        <a href="posts.php" class="btn btn-gradient btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            New Post
                        </a>
                    </div>
                    
                    <?php if (empty($recent_posts)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No posts yet. Create your first post!</p>
                            <a href="posts.php" class="btn btn-gradient">Create Post</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_posts as $post): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $post['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($post['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="col-lg-4 mb-4">
                <div class="content-card">
                    <h5 class="mb-4">Recent Activity</h5>
                    
                    <?php if (empty($recent_activity)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_activity as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon <?php echo $activity['action']; ?>">
                                    <i class="fas fa-<?php 
                                        echo $activity['action'] === 'login' ? 'sign-in-alt' : 
                                            ($activity['action'] === 'logout' ? 'sign-out-alt' : 'plus'); 
                                    ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">
                                        <?php echo htmlspecialchars($activity['username'] ?? 'System'); ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($activity['description']); ?>
                                    </small>
                                    <div class="small text-muted">
                                        <?php echo date('M j, g:i A', strtotime($activity['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="content-card">
            <h5 class="mb-4">Quick Actions</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="posts.php" class="btn btn-gradient w-100">
                        <i class="fas fa-plus me-2"></i>
                        New Post
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="media.php" class="btn btn-outline-primary w-100">
                        <i class="fas fa-upload me-2"></i>
                        Upload Media
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="pages.php" class="btn btn-outline-success w-100">
                        <i class="fas fa-edit me-2"></i>
                        Edit Pages
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="../index.php" target="_blank" class="btn btn-outline-info w-100">
                        <i class="fas fa-external-link-alt me-2"></i>
                        View Site
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
