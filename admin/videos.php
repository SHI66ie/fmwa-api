<?php
require_once '../config.php';
require_once 'auth.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $auth = new Auth($pdo);
    $auth->requireLogin();
    $user = $auth->getCurrentUser();
} catch (Exception $e) {
    die('Error: ' . $e->getMessage() . '<br><small>Please check database connection and try again.</small>');
}

// Get video statistics
try {
    // Debug: Check if media table exists and has video files
    $stmt = $pdo->query("SHOW TABLES LIKE 'media'");
    if ($stmt->rowCount() === 0) {
        throw new Exception("Media table does not exist");
    }
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM media WHERE file_type = 'video'");
    $video_count = $stmt->fetch()['count'] ?? 0;
    
    $stmt = $pdo->query("SELECT SUM(file_size) as total_size FROM media WHERE file_type = 'video'");
    $total_size = $stmt->fetch()['total_size'] ?? 0;
    
    // Get recent videos
    $stmt = $pdo->query("SELECT * FROM media WHERE file_type = 'video' ORDER BY created_at DESC LIMIT 10");
    $recent_videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug: Log what we found
    error_log("Videos Debug: Found $video_count video files");
    
} catch (Exception $e) {
    error_log("Videos Query Error: " . $e->getMessage());
    $video_count = 0;
    $total_size = 0;
    $recent_videos = [];
    $error_message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .video-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .video-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }
        
        .video-thumbnail {
            width: 100%;
            height: 200px;
            background: #000;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .video-thumbnail video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-thumbnail .play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .video-thumbnail .play-overlay:hover {
            background: rgba(102, 126, 234, 0.8);
            transform: translate(-50%, -50%) scale(1.1);
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
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
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
                <a href="videos.php" class="nav-link active">
                    <i class="fas fa-video"></i>
                    Videos
                </a>
            </div>
            <div class="nav-item">
                <a href="director-photos.php" class="nav-link">
                    <i class="fas fa-user-tie"></i>
                    Director Photos
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
            <div>
                <h3 class="mb-0">Videos Management</h3>
                <small class="text-muted">Manage uploaded videos and YouTube embeds</small>
            </div>
            <div>
                <button class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#youtubeModal">
                    <i class="fab fa-youtube me-2"></i>
                    Add YouTube Video
                </button>
                <a href="media.php" class="btn btn-gradient">
                    <i class="fas fa-upload me-2"></i>
                    Upload Video
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number" id="uploadedVideoCount"><?php echo $video_count; ?></div>
                    <div>Uploaded Videos</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number" id="youtubeVideoCount">0</div>
                    <div>YouTube Videos</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo number_format($total_size / 1024 / 1024, 1); ?> MB</div>
                    <div>Storage Used</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number" id="totalVideoCount"><?php echo $video_count; ?></div>
                    <div>Total Videos</div>
                </div>
            </div>
        </div>

        <!-- Video Type Tabs -->
        <div class="content-card mb-4">
            <ul class="nav nav-tabs" id="videoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="uploaded-tab" data-bs-toggle="tab" data-bs-target="#uploaded-videos" type="button" role="tab">
                        <i class="fas fa-upload me-2"></i>Uploaded Videos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="youtube-tab" data-bs-toggle="tab" data-bs-target="#youtube-videos" type="button" role="tab">
                        <i class="fab fa-youtube me-2"></i>YouTube Videos
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="content-card">
            <div class="tab-content" id="videoTabContent">
                <!-- Uploaded Videos Tab -->
                <div class="tab-pane fade show active" id="uploaded-videos" role="tabpanel">
                    <h5 class="mb-4">
                        <i class="fas fa-upload me-2"></i>
                        Uploaded Videos
                        <?php if (isset($error_message)): ?>
                            <small class="text-danger ms-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Database Error: <?php echo htmlspecialchars($error_message); ?>
                            </small>
                        <?php endif; ?>
                    </h5>
                    
                    <div id="uploadedVideosContainer">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Database Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                                <br><small class="text-muted">Please check your database configuration and try again.</small>
                                <div class="mt-2">
                                    <a href="videos-debug.php" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-bug me-1"></i>Debug Page
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning" onclick="location.reload()">
                                        <i class="fas fa-sync me-1"></i>Retry
                                    </button>
                                </div>
                            </div>
                        <?php elseif (empty($recent_videos)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-upload fa-4x text-muted mb-3"></i>
                                <h5>No Uploaded Videos</h5>
                                <p class="text-muted mb-4">You haven't uploaded any videos yet.</p>
                                <a href="media.php" class="btn btn-gradient">
                                    <i class="fas fa-upload me-2"></i>
                                    Upload Your First Video
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($recent_videos as $video): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="video-card">
                                            <div class="video-thumbnail">
                                                <?php if (file_exists('../' . $video['file_url'])): ?>
                                                    <video muted>
                                                        <source src="../<?php echo htmlspecialchars($video['file_url']); ?>" type="<?php echo htmlspecialchars($video['mime_type']); ?>">
                                                    </video>
                                                <?php endif; ?>
                                                <div class="play-overlay" onclick="playVideo('<?php echo htmlspecialchars($video['file_url']); ?>', '<?php echo htmlspecialchars($video['mime_type']); ?>')">
                                                    <i class="fas fa-play"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($video['title'] || $video['original_filename']); ?></h6>
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-file me-1"></i>
                                                    <?php echo htmlspecialchars($video['original_filename']); ?>
                                                </p>
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-weight me-1"></i>
                                                    <?php echo number_format($video['file_size'] / 1024 / 1024, 2); ?> MB
                                                </p>
                                                <p class="text-muted small mb-3">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php echo date('M j, Y', strtotime($video['created_at'])); ?>
                                                </p>
                                                <div class="btn-group w-100" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="playVideo('<?php echo htmlspecialchars($video['file_url']); ?>', '<?php echo htmlspecialchars($video['mime_type']); ?>')">
                                                        <i class="fas fa-play me-1"></i>Play
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" onclick="copyVideoUrl('<?php echo htmlspecialchars($video['file_url']); ?>')">
                                                        <i class="fas fa-copy me-1"></i>Copy URL
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteVideo(<?php echo $video['id']; ?>)">
                                                        <i class="fas fa-trash me-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- YouTube Videos Tab -->
                <div class="tab-pane fade" id="youtube-videos" role="tabpanel">
                    <h5 class="mb-4">
                        <i class="fab fa-youtube me-2"></i>
                        YouTube Videos
                    </h5>
                    
                    <div id="youtubeVideosContainer">
                        <div class="text-center py-5">
                            <i class="fab fa-youtube fa-4x text-muted mb-3"></i>
                            <h5>No YouTube Videos</h5>
                            <p class="text-muted mb-4">You haven't added any YouTube videos yet.</p>
                            <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#youtubeModal">
                                <i class="fab fa-youtube me-2"></i>
                                Add YouTube Video
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Player Modal -->
    <div class="modal fade" id="videoPlayerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Video Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <video id="videoPlayer" controls style="width: 100%; max-height: 500px;">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <!-- YouTube Video Modal -->
    <div class="modal fade" id="youtubeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fab fa-youtube me-2"></i>
                        Add YouTube Video
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="youtubeForm">
                        <div class="mb-3">
                            <label for="youtubeUrl" class="form-label">
                                <i class="fab fa-youtube me-1"></i>
                                YouTube URL
                            </label>
                            <input type="url" class="form-control" id="youtubeUrl" 
                                   placeholder="https://www.youtube.com/watch?v=..." 
                                   required>
                            <small class="text-muted">
                                Paste any YouTube video URL (watch, embed, or youtu.be)
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="youtubeTitle" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Title (Optional)
                            </label>
                            <input type="text" class="form-control" id="youtubeTitle" 
                                   placeholder="Video title">
                        </div>
                        <div class="mb-3">
                            <label for="youtubeDescription" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description (Optional)
                            </label>
                            <textarea class="form-control" id="youtubeDescription" rows="3" 
                                      placeholder="Video description"></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fab fa-youtube me-1"></i>
                                Add YouTube Video
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let videoPlayerModal;
        let youtubeModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            videoPlayerModal = new bootstrap.Modal(document.getElementById('videoPlayerModal'));
            youtubeModal = new bootstrap.Modal(document.getElementById('youtubeModal'));
            
            // Load YouTube videos
            loadYouTubeVideos();
            
            // Handle YouTube form submission
            document.getElementById('youtubeForm').addEventListener('submit', handleYouTubeSubmit);
        });
        
        function playVideo(url, mimeType) {
            const player = document.getElementById('videoPlayer');
            player.src = '../' + url;
            player.type = mimeType;
            player.load();
            videoPlayerModal.show();
        }
        
        function playYouTubeVideo(videoId) {
            const embedUrl = `https://www.youtube.com/embed/${videoId}`;
            const player = document.getElementById('videoPlayer');
            player.innerHTML = `<iframe src="${embedUrl}" frameborder="0" allowfullscreen style="width: 100%; height: 500px;"></iframe>`;
            videoPlayerModal.show();
        }
        
        function copyVideoUrl(url) {
            const fullUrl = window.location.origin + '/' + url;
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('Video URL copied to clipboard!\n\n' + fullUrl);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Failed to copy URL');
            });
        }
        
        function copyYouTubeUrl(youtubeUrl, embedUrl) {
            const embedCode = `<iframe width="560" height="315" src="${embedUrl}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`;
            navigator.clipboard.writeText(embedCode).then(() => {
                alert('YouTube embed code copied to clipboard!\n\n' + embedCode);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Failed to copy embed code');
            });
        }
        
        function deleteVideo(videoId) {
            if (!confirm('Are you sure you want to delete this video? This action cannot be undone.')) {
                return;
            }
            
            fetch('api/media.php?id=' + videoId, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Video deleted successfully!');
                    location.reload();
                } else {
                    alert('Failed to delete video: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete video: ' + error.message);
            });
        }
        
        function deleteYouTubeVideo(videoId) {
            if (!confirm('Are you sure you want to delete this YouTube video? This action cannot be undone.')) {
                return;
            }
            
            fetch('api/youtube-api.php?id=' + videoId, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('YouTube video deleted successfully!');
                    loadYouTubeVideos();
                } else {
                    alert('Failed to delete YouTube video: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete YouTube video: ' + error.message);
            });
        }
        
        function handleYouTubeSubmit(e) {
            e.preventDefault();
            
            const youtubeUrl = document.getElementById('youtubeUrl').value;
            const title = document.getElementById('youtubeTitle').value;
            const description = document.getElementById('youtubeDescription').value;
            
            if (!youtubeUrl) {
                alert('Please enter a YouTube URL');
                return;
            }
            
            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            submitBtn.disabled = true;
            
            fetch('api/youtube-api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    youtube_url: youtubeUrl,
                    title: title,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('YouTube video added successfully!');
                    youtubeModal.hide();
                    document.getElementById('youtubeForm').reset();
                    loadYouTubeVideos();
                    updateYouTubeCount();
                } else {
                    alert('Failed to add YouTube video: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add YouTube video: ' + error.message);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
        
        function loadYouTubeVideos() {
            fetch('api/youtube-api.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayYouTubeVideos(data.data);
                    updateYouTubeCount();
                } else {
                    console.error('Failed to load YouTube videos:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading YouTube videos:', error);
            });
        }
        
        function displayYouTubeVideos(videos) {
            const container = document.getElementById('youtubeVideosContainer');
            
            if (videos.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fab fa-youtube fa-4x text-muted mb-3"></i>
                        <h5>No YouTube Videos</h5>
                        <p class="text-muted mb-4">You haven't added any YouTube videos yet.</p>
                        <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#youtubeModal">
                            <i class="fab fa-youtube me-2"></i>
                            Add YouTube Video
                        </button>
                    </div>
                `;
                return;
            }
            
            let html = '<div class="row">';
            videos.forEach(video => {
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="video-card">
                            <div class="video-thumbnail">
                                ${video.thumbnail_url ? 
                                    `<img src="${video.thumbnail_url}" alt="${video.title}" style="width: 100%; height: 100%; object-fit: cover;">` :
                                    `<div class="d-flex align-items-center justify-content-center bg-dark h-100">
                                        <i class="fab fa-youtube fa-3x text-white"></i>
                                    </div>`
                                }
                                <div class="play-overlay" onclick="playYouTubeVideo('${video.video_id}')">
                                    <i class="fab fa-youtube"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="mb-1">${video.title || 'YouTube Video'}</h6>
                                ${video.description ? `<p class="text-muted small mb-2">${video.description.substring(0, 100)}...</p>` : ''}
                                <p class="text-muted small mb-2">
                                    <i class="fab fa-youtube me-1"></i>
                                    YouTube Video
                                </p>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${new Date(video.created_at).toLocaleDateString()}
                                </p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-danger" onclick="playYouTubeVideo('${video.video_id}')">
                                        <i class="fab fa-youtube me-1"></i>Play
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="copyYouTubeUrl('${video.youtube_url}', 'https://www.youtube.com/embed/${video.video_id}')">
                                        <i class="fas fa-copy me-1"></i>Copy Embed
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteYouTubeVideo(${video.id})">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }
        
        function updateYouTubeCount() {
            fetch('api/youtube-api.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('youtubeVideoCount').textContent = data.pagination.total;
                    const uploadedCount = parseInt(document.getElementById('uploadedVideoCount').textContent);
                    document.getElementById('totalVideoCount').textContent = uploadedCount + data.pagination.total;
                }
            })
            .catch(error => {
                console.error('Error updating YouTube count:', error);
            });
        }
    </script>
</body>
</html>
