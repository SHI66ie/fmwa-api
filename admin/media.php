<?php
require_once '../config.php';
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
    <title>Media Library - FMWA Admin</title>
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
        
        .upload-area {
            border: 3px dashed #dee2e6;
            border-radius: 15px;
            padding: 60px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 30px;
        }
        
        .upload-area.dragover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .upload-area:hover {
            border-color: #667eea;
        }
        
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .media-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .media-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .media-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }
        
        .media-info {
            padding: 10px;
            background: white;
        }
        
        .media-title {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .media-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .media-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
            gap: 5px;
        }
        
        .media-item:hover .media-actions {
            display: flex;
        }
        
        .modal-content {
            border-radius: 15px;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .media-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
                <a href="media.php" class="nav-link active">
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
                <h3 class="mb-0">Media Library</h3>
                <small class="text-muted">Upload and manage your media files</small>
            </div>
        </div>

        <div class="content-card">
            <!-- Upload Area -->
            <div class="upload-area" id="uploadArea">
                <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-3"></i>
                <h5>Drop files here or click to upload</h5>
                <p class="text-muted">Supported: JPG, PNG, GIF, WebP, SVG, MP4, WebM, PDF</p>
                <input type="file" id="fileInput" multiple accept="image/*,video/*,.pdf" style="display: none;">
            </div>

            <!-- Search -->
            <div class="mb-4">
                <input type="text" id="searchMedia" class="form-control" placeholder="Search media files...">
            </div>

            <!-- Media Grid -->
            <div id="mediaGrid" class="media-grid">
                <div class="text-center py-5" style="grid-column: 1/-1;">
                    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Loading media...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Media Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="mediaPreview" class="mb-3 text-center"></div>
                    <form id="editForm">
                        <input type="hidden" id="mediaId">
                        
                        <div class="mb-3">
                            <label for="mediaTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="mediaTitle" name="title">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mediaAlt" class="form-label">Alt Text</label>
                            <input type="text" class="form-control" id="mediaAlt" name="alt_text">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mediaCaption" class="form-label">Caption</label>
                            <textarea class="form-control" id="mediaCaption" name="caption" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">File URL</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="mediaUrl" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyUrl()">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="deleteMedia()">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-gradient" onclick="saveMedia()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let editModal;
        let mediaFiles = [];
        let currentMediaId = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            editModal = new bootstrap.Modal(document.getElementById('editModal'));
            
            // Upload area handlers
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            
            uploadArea.addEventListener('click', () => fileInput.click());
            
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                handleFiles(e.dataTransfer.files);
            });
            
            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });
            
            // Search
            document.getElementById('searchMedia').addEventListener('input', filterMedia);
            
            loadMedia();
        });
        
        function handleFiles(files) {
            Array.from(files).forEach(file => {
                uploadFile(file);
            });
        }
        
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            
            fetch('api/media.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('File uploaded successfully');
                    loadMedia();
                } else {
                    showError(data.message || 'Upload failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Upload failed');
            });
        }
        
        function loadMedia() {
            fetch('api/media.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // API may return the list under data.media or data.data; support both
                        const list = Array.isArray(data.media)
                            ? data.media
                            : (Array.isArray(data.data) ? data.data : []);

                        mediaFiles = list;
                        displayMedia(mediaFiles);
                    } else {
                        showError(data.message || 'Failed to load media');
                        displayMedia([]);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load media');
                    displayMedia([]);
                });
        }
        
        function displayMedia(files) {
            const grid = document.getElementById('mediaGrid');
            const list = Array.isArray(files) ? files : [];
            
            if (list.length === 0) {
                grid.innerHTML = `
                    <div class="text-center py-5" style="grid-column: 1/-1;">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No media files found</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            list.forEach(file => {
                const thumbnail = getThumbnail(file);
                const size = formatFileSize(file.file_size);
                const date = new Date(file.created_at).toLocaleDateString();
                
                html += `
                    <div class="media-item" onclick="editMediaModal(${file.id})">
                        ${thumbnail}
                        <div class="media-actions">
                            <button class="btn btn-sm btn-light" onclick="event.stopPropagation(); editMediaModal(${file.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-light" onclick="event.stopPropagation(); deleteMedia(${file.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="media-info">
                            <div class="media-title">${escapeHtml(file.title || file.filename)}</div>
                            <div class="media-meta">${size} • ${date}</div>
                        </div>
                    </div>
                `;
            });
            
            grid.innerHTML = html;
        }
        
        function getThumbnail(file) {
            const isImage = file.mime_type && file.mime_type.startsWith('image/');
            const isVideo = file.mime_type && file.mime_type.startsWith('video/');
            const isPdf = file.mime_type === 'application/pdf';
            
            if (isImage) {
                return `<img src="${file.file_url}" alt="${escapeHtml(file.title || '')}" class="media-thumbnail">`;
            } else if (isVideo) {
                return `<div class="media-thumbnail d-flex align-items-center justify-content-center bg-dark"><i class="fas fa-video fa-3x text-white"></i></div>`;
            } else if (isPdf) {
                return `<div class="media-thumbnail d-flex align-items-center justify-content-center bg-danger"><i class="fas fa-file-pdf fa-3x text-white"></i></div>`;
            } else {
                return `<div class="media-thumbnail d-flex align-items-center justify-content-center bg-secondary"><i class="fas fa-file fa-3x text-white"></i></div>`;
            }
        }
        
        function editMediaModal(id) {
            const media = mediaFiles.find(m => m.id === id);
            if (!media) return;
            
            currentMediaId = id;
            
            document.getElementById('mediaId').value = media.id;
            document.getElementById('mediaTitle').value = media.title || '';
            document.getElementById('mediaAlt').value = media.alt_text || '';
            document.getElementById('mediaCaption').value = media.caption || '';
            document.getElementById('mediaUrl').value = window.location.origin + '/' + media.file_url;
            
            // Show preview
            const preview = document.getElementById('mediaPreview');
            if (media.mime_type && media.mime_type.startsWith('image/')) {
                preview.innerHTML = `<img src="${media.file_url}" alt="" style="max-width: 100%; max-height: 300px; border-radius: 10px;">`;
            } else {
                preview.innerHTML = `<div class="alert alert-info">${escapeHtml(media.filename)}</div>`;
            }
            
            editModal.show();
        }
        
        function saveMedia() {
            const id = document.getElementById('mediaId').value;
            const data = {
                id: id,
                title: document.getElementById('mediaTitle').value,
                alt_text: document.getElementById('mediaAlt').value,
                caption: document.getElementById('mediaCaption').value
            };
            
            fetch('api/media.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    editModal.hide();
                    loadMedia();
                    showSuccess('Media updated successfully');
                } else {
                    showError(data.message || 'Update failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Update failed');
            });
        }
        
        function deleteMedia(id = null) {
            const mediaId = id || currentMediaId;
            if (!mediaId) return;
            
            if (!confirm('Are you sure you want to delete this media file?')) {
                return;
            }
            
            fetch('api/media.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: mediaId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (editModal._isShown) {
                        editModal.hide();
                    }
                    loadMedia();
                    showSuccess('Media deleted successfully');
                } else {
                    showError(data.message || 'Delete failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Delete failed');
            });
        }
        
        function copyUrl() {
            const urlInput = document.getElementById('mediaUrl');
            urlInput.select();
            document.execCommand('copy');
            showSuccess('URL copied to clipboard');
        }
        
        function filterMedia() {
            const search = document.getElementById('searchMedia').value.toLowerCase();
            
            const filtered = mediaFiles.filter(file => {
                return !search || 
                    (file.title && file.title.toLowerCase().includes(search)) ||
                    (file.filename && file.filename.toLowerCase().includes(search));
            });
            
            displayMedia(filtered);
        }
        
        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function showSuccess(message) {
            alert(message);
        }
        
        function showError(message) {
            alert('Error: ' + message);
        }
    </script>
</body>
</html>
