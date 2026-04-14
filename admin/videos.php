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
                <small class="text-muted">Manage uploaded videos and embedded content</small>
            </div>
            <div>
                <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#uploadOptionsModal">
                    <i class="fas fa-plus me-2"></i>
                    Add Video
                </button>
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
                    <div class="stats-number" id="embedVideoCount">0</div>
                    <div>Embedded Videos</div>
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

        <!-- Upload Options Modal -->
        <div class="modal fade" id="uploadOptionsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus me-2"></i>
                            Add Video
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-grid gap-3">
                            <button class="btn btn-outline-primary btn-lg" onclick="openUploadModal()">
                                <i class="fas fa-upload me-2"></i>
                                Upload Video File
                                <small class="d-block text-muted">Upload MP4, WebM, or OGG files</small>
                            </button>
                            <button class="btn btn-outline-danger btn-lg" onclick="openYouTubeModal()">
                                <i class="fab fa-youtube me-2"></i>
                                Add YouTube Video
                                <small class="d-block text-muted">Paste YouTube URL</small>
                            </button>
                            <button class="btn btn-outline-success btn-lg" onclick="openEmbedModal()">
                                <i class="fas fa-code me-2"></i>
                                Add Embedded Video
                                <small class="d-block text-muted">Paste iframe embed code</small>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Videos Content -->
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-video me-2"></i>
                All Videos
                <?php if (isset($error_message)): ?>
                    <small class="text-danger ms-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Database Error: <?php echo htmlspecialchars($error_message); ?>
                    </small>
                <?php endif; ?>
            </h5>
            
            <div id="allVideosContainer">
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
                        <i class="fas fa-video fa-4x text-muted mb-3"></i>
                        <h5>No Videos Yet</h5>
                        <p class="text-muted mb-4">You haven't added any videos yet.</p>
                        <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#uploadOptionsModal">
                            <i class="fas fa-plus me-2"></i>
                            Add Your First Video
                        </button>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($recent_videos as $video): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="video-card">
                                    <div class="video-thumbnail">
                                        <?php 
                                        $videoPath = '../' . $video['file_url'];
                                        $fileExists = file_exists($videoPath);
                                        ?>
                                        <?php if ($fileExists): ?>
                                            <video muted>
                                                <source src="../<?php echo htmlspecialchars($video['file_url']); ?>" type="<?php echo htmlspecialchars($video['mime_type']); ?>">
                                            </video>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center bg-dark h-100">
                                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="play-overlay" onclick="playVideo('<?php echo htmlspecialchars($video['file_url']); ?>', '<?php echo htmlspecialchars($video['mime_type']); ?>', <?php echo $fileExists ? 'true' : 'false'; ?>)">
                                            <i class="fas fa-play"></i>
                                        </div>
                                        <?php if (!$fileExists): ?>
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <span class="badge bg-danger">File Missing</span>
                                            </div>
                                        <?php endif; ?>
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
                                            <?php if ($fileExists): ?>
                                                <button class="btn btn-sm btn-outline-primary" onclick="playVideo('<?php echo htmlspecialchars($video['file_url']); ?>', '<?php echo htmlspecialchars($video['mime_type']); ?>', true)">
                                                    <i class="fas fa-play me-1"></i>Play
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-warning" onclick="alert('⚠️ Video file not found!\n\nThe video file cannot be played because it does not exist on the server.\n\nPlease delete this record or re-upload the video.')">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>File Missing
                                                </button>
                                            <?php endif; ?>
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
                    
                    <!-- YouTube and Embedded Videos will be loaded here -->
                    <div id="youtubeVideosList"></div>
                    <div id="embedVideosList"></div>
                <?php endif; ?>
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

    <!-- Embedded Video Modal -->
    <div class="modal fade" id="embedModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-code me-2"></i>
                        Add Embedded Video
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="embedForm">
                        <div class="mb-3">
                            <label for="embedCode" class="form-label">
                                <i class="fas fa-code me-1"></i>
                                Embed Code
                            </label>
                            <textarea class="form-control" id="embedCode" rows="6" 
                                      placeholder="Paste your iframe embed code here..." required></textarea>
                            <small class="text-muted">
                                Paste the full iframe embed code from YouTube, Vimeo, or any video platform
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="embedTitle" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Title (Optional)
                            </label>
                            <input type="text" class="form-control" id="embedTitle" 
                                   placeholder="Video title">
                        </div>
                        <div class="mb-3">
                            <label for="embedDescription" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Description (Optional)
                            </label>
                            <textarea class="form-control" id="embedDescription" rows="3" 
                                      placeholder="Video description"></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-code me-1"></i>
                                Add Embedded Video
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
        let embedModal;
        let uploadOptionsModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals
            videoPlayerModal = new bootstrap.Modal(document.getElementById('videoPlayerModal'));
            youtubeModal = new bootstrap.Modal(document.getElementById('youtubeModal'));
            embedModal = new bootstrap.Modal(document.getElementById('embedModal'));
            uploadOptionsModal = new bootstrap.Modal(document.getElementById('uploadOptionsModal'));
            
            // Load all videos
            loadAllVideos();
            
            // Handle form submissions
            document.getElementById('youtubeForm').addEventListener('submit', handleYouTubeSubmit);
            document.getElementById('embedForm').addEventListener('submit', handleEmbedSubmit);
        });
        
        function openUploadModal() {
            uploadOptionsModal.hide();
            window.location.href = 'media.php';
        }
        
        function openYouTubeModal() {
            uploadOptionsModal.hide();
            youtubeModal.show();
        }
        
        function openEmbedModal() {
            uploadOptionsModal.hide();
            embedModal.show();
        }
        
        function playVideo(url, mimeType, fileExists = true) {
            if (!fileExists) {
                alert('⚠️ Video file not found!\n\nThe video file "' + url + '" does not exist on the server.\n\nThis may happen if:\n• The file was deleted from the server\n• The file path is incorrect\n• The upload was incomplete\n\nOptions:\n• Delete this video record\n• Re-upload the video file');
                return;
            }
            
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
                    loadAllVideos();
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
        
        function loadAllVideos() {
            // Load YouTube videos
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
                    displayYouTubeVideosUnified(data.data);
                    updateYouTubeCount();
                } else {
                    console.error('Failed to load YouTube videos:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading YouTube videos:', error);
            });
            
            // Load embedded videos
            fetch('api/embed-api.php', {
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
                    displayEmbedVideosUnified(data.data);
                    updateEmbedCount();
                } else {
                    console.error('Failed to load embed videos:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading embed videos:', error);
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
                    displayYouTubeVideosUnified(data.data);
                    updateYouTubeCount();
                } else {
                    console.error('Failed to load YouTube videos:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading YouTube videos:', error);
            });
        }
        
        function displayYouTubeVideosUnified(videos) {
            const container = document.getElementById('youtubeVideosList');
            
            if (videos.length === 0) {
                container.innerHTML = '';
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
                    updateTotalCount();
                }
            })
            .catch(error => {
                console.error('Error updating YouTube count:', error);
            });
        }
        
        function handleEmbedSubmit(e) {
            e.preventDefault();
            
            const embedCode = document.getElementById('embedCode').value;
            const title = document.getElementById('embedTitle').value;
            const description = document.getElementById('embedDescription').value;
            
            if (!embedCode) {
                alert('Please enter embed code');
                return;
            }
            
            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            submitBtn.disabled = true;
            
            fetch('api/embed-api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    embed_code: embedCode,
                    title: title,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Embedded video added successfully!');
                    embedModal.hide();
                    document.getElementById('embedForm').reset();
                    loadAllVideos();
                } else {
                    alert('Failed to add embedded video: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add embedded video: ' + error.message);
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
        
        function loadEmbedVideos() {
            fetch('api/embed-api.php', {
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
                    displayEmbedVideos(data.data);
                    updateEmbedCount();
                } else {
                    console.error('Failed to load embed videos:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading embed videos:', error);
            });
        }
        
        function displayEmbedVideosUnified(videos) {
            const container = document.getElementById('embedVideosList');
            
            if (videos.length === 0) {
                container.innerHTML = '';
                return;
            }
            
            let html = '<div class="row">';
            videos.forEach(video => {
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="video-card">
                            <div class="video-thumbnail">
                                <div class="d-flex align-items-center justify-content-center bg-dark h-100">
                                    <i class="fas fa-code fa-3x text-white"></i>
                                </div>
                                <div class="play-overlay" onclick="playEmbedVideo('${video.id}')">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="mb-1">${video.title || 'Embedded Video'}</h6>
                                ${video.description ? `<p class="text-muted small mb-2">${video.description.substring(0, 100)}...</p>` : ''}
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-code me-1"></i>
                                    Embedded Video
                                </p>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${new Date(video.created_at).toLocaleDateString()}
                                </p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="playEmbedVideo('${video.id}')">
                                        <i class="fas fa-play me-1"></i>Play
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="copyEmbedCode('${video.id}')">
                                        <i class="fas fa-copy me-1"></i>Copy Code
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteEmbedVideo(${video.id})">
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
        
        function displayEmbedVideos(videos) {
            const container = document.getElementById('embedVideosContainer');
            
            if (videos.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-code fa-4x text-muted mb-3"></i>
                        <h5>No Embedded Videos</h5>
                        <p class="text-muted mb-4">You haven't added any embedded videos yet.</p>
                        <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#embedModal">
                            <i class="fas fa-code me-2"></i>
                            Add Embedded Video
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
                                <div class="d-flex align-items-center justify-content-center bg-dark h-100">
                                    <i class="fas fa-code fa-3x text-white"></i>
                                </div>
                                <div class="play-overlay" onclick="playEmbedVideo('${video.id}')">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="mb-1">${video.title || 'Embedded Video'}</h6>
                                ${video.description ? `<p class="text-muted small mb-2">${video.description.substring(0, 100)}...</p>` : ''}
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-code me-1"></i>
                                    Embedded Video
                                </p>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${new Date(video.created_at).toLocaleDateString()}
                                </p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="playEmbedVideo('${video.id}')">
                                        <i class="fas fa-play me-1"></i>Play
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="copyEmbedCode('${video.id}')">
                                        <i class="fas fa-copy me-1"></i>Copy Code
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteEmbedVideo(${video.id})">
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
        
        function playEmbedVideo(videoId) {
            fetch('api/embed-api.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const video = data.data.find(v => v.id == videoId);
                    if (video) {
                        const player = document.getElementById('videoPlayer');
                        player.innerHTML = video.embed_code;
                        videoPlayerModal.show();
                    }
                }
            })
            .catch(error => {
                console.error('Error playing embed video:', error);
                alert('Failed to play embedded video');
            });
        }
        
        function copyEmbedCode(videoId) {
            fetch('api/embed-api.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const video = data.data.find(v => v.id == videoId);
                    if (video) {
                        navigator.clipboard.writeText(video.embed_code).then(() => {
                            alert('Embed code copied to clipboard!');
                        }).catch(err => {
                            console.error('Failed to copy:', err);
                            alert('Failed to copy embed code');
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error copying embed code:', error);
                alert('Failed to copy embed code');
            });
        }
        
        function deleteEmbedVideo(videoId) {
            if (!confirm('Are you sure you want to delete this embedded video? This action cannot be undone.')) {
                return;
            }
            
            fetch('api/embed-api.php?id=' + videoId, {
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
                    alert('Embedded video deleted successfully!');
                    loadEmbedVideos();
                } else {
                    alert('Failed to delete embedded video: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete embedded video: ' + error.message);
            });
        }
        
        function updateEmbedCount() {
            fetch('api/embed-api.php', {
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
                    document.getElementById('embedVideoCount').textContent = data.pagination.total;
                    updateTotalCount();
                }
            })
            .catch(error => {
                console.error('Error updating embed count:', error);
            });
        }
        
        function updateTotalCount() {
            const uploaded = parseInt(document.getElementById('uploadedVideoCount').textContent) || 0;
            const youtube = parseInt(document.getElementById('youtubeVideoCount').textContent) || 0;
            const embed = parseInt(document.getElementById('embedVideoCount').textContent) || 0;
            document.getElementById('totalVideoCount').textContent = uploaded + youtube + embed;
        }
    </script>
</body>
</html>
