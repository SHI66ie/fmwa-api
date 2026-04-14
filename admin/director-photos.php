<?php
require_once 'config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Photos - FMWA Admin</title>
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
        
        .director-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .director-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }
        
        .director-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .director-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
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
                <a href="director-photos.php" class="nav-link active">
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
                <h3 class="mb-0">Director Photos Management</h3>
                <small class="text-muted">Update department director photos from media library</small>
            </div>
        </div>

        <div class="content-card">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>How it works:</strong> Upload photos to the Media Library first, then assign them to department directors here. Changes will reflect immediately on the website.
            </div>
            
            <div id="directorsContainer">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Loading director photos...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Selection Modal -->
    <div class="modal fade" id="mediaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Photo from Media Library</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="mediaSearch" class="form-control" placeholder="Search media files...">
                    </div>
                    <div id="mediaGrid" class="row g-3">
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="mt-2 text-muted">Loading media...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let mediaModal;
        let currentDepartment = null;
        let allMedia = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            mediaModal = new bootstrap.Modal(document.getElementById('mediaModal'));
            
            loadDirectorPhotos();
            
            // Media search
            document.getElementById('mediaSearch').addEventListener('input', filterMedia);
        });
        
        function loadDirectorPhotos() {
            fetch('api/media-director.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayDirectors(data.data);
                    } else {
                        showError('Failed to load director photos');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load director photos');
                });
        }
        
        function displayDirectors(directors) {
            const container = document.getElementById('directorsContainer');
            
            let html = '';
            Object.entries(directors).forEach(([slug, info]) => {
                html += `
                    <div class="director-card">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                ${info.exists 
                                    ? `<img src="${info.photo_url}" alt="${info.name} Director" class="director-photo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                       <div class="director-placeholder" style="display: none;">
                                           <i class="fas fa-user-tie"></i>
                                       </div>`
                                    : `<div class="director-placeholder">
                                           <i class="fas fa-user-tie"></i>
                                       </div>`
                                }
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-1">${info.name}</h5>
                                <p class="text-muted mb-2">Department Director</p>
                                <small class="text-muted">
                                    ${info.exists ? 'Photo uploaded' : 'No photo uploaded'}
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-gradient" onclick="selectPhoto('${slug}')">
                                    <i class="fas fa-image me-2"></i>
                                    ${info.exists ? 'Change Photo' : 'Select Photo'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        function selectPhoto(department) {
            currentDepartment = department;
            loadMedia();
            mediaModal.show();
        }
        
        function loadMedia() {
            fetch('api/media.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allMedia = data.data || [];
                        displayMedia(allMedia);
                    } else {
                        showError('Failed to load media');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load media');
                });
        }
        
        function displayMedia(media) {
            const grid = document.getElementById('mediaGrid');
            
            if (media.length === 0) {
                grid.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-images fa-2x text-muted"></i>
                        <p class="mt-2 text-muted">No media files found. Upload some first!</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            media.forEach(file => {
                if (file.mime_type && file.mime_type.startsWith('image/')) {
                    const thumbnail = `<img src="../${file.file_url}" alt="${file.title || file.filename}" class="img-fluid rounded" style="height: 100px; object-fit: cover; cursor: pointer;" onclick="assignPhoto(${file.id})">`;
                    html += `
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body p-2">
                                    ${thumbnail}
                                    <small class="text-muted d-block mt-1">${file.title || file.filename}</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            grid.innerHTML = html;
        }
        
        function filterMedia() {
            const search = document.getElementById('mediaSearch').value.toLowerCase();
            const filtered = allMedia.filter(file => 
                file.mime_type.startsWith('image/') && (
                    (file.title && file.title.toLowerCase().includes(search)) ||
                    (file.filename && file.filename.toLowerCase().includes(search))
                )
            );
            displayMedia(filtered);
        }
        
        function assignPhoto(mediaId) {
            if (!currentDepartment) return;
            
            const data = {
                department: currentDepartment,
                media_id: mediaId
            };
            
            fetch('api/media-director.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Director photo updated successfully');
                    mediaModal.hide();
                    loadDirectorPhotos();
                } else {
                    showError(data.message || 'Failed to update photo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to update photo');
            });
        }
        
        function showSuccess(message) {
            alert('Success: ' + message);
        }
        
        function showError(message) {
            alert('Error: ' + message);
        }
    </script>
</body>
</html>
