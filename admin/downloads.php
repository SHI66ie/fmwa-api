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
    <title>Downloads & Publications - FMWA Admin</title>
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
        
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .table-custom {
            margin-top: 20px;
        }
        
        .table-custom th {
            border-top: none;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table-custom td {
            vertical-align: middle;
            color: #495057;
            border-color: #f8f9fa;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.2s ease;
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
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="posts.php" class="nav-link">
                    <i class="fas fa-newspaper"></i> Posts & News
                </a>
            </div>
            <div class="nav-item">
                <a href="media.php" class="nav-link">
                    <i class="fas fa-images"></i> Media Library
                </a>
            </div>
            <div class="nav-item">
                <a href="downloads.php" class="nav-link active">
                    <i class="fas fa-download"></i> Downloads
                </a>
            </div>
            <div class="nav-item">
                <a href="pages.php" class="nav-link">
                    <i class="fas fa-file-code"></i> Page Editor
                </a>
            </div>
            <div class="nav-item">
                <a href="categories.php" class="nav-link">
                    <i class="fas fa-tags"></i> Categories
                </a>
            </div>
            <div class="nav-item">
                <a href="settings.php" class="nav-link">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </div>
            <div class="nav-item mt-4">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <h3 class="mb-0">Downloads & Publications</h3>
                <small class="text-muted">Manage downloadable resources</small>
            </div>
            <button class="btn btn-gradient" onclick="showAddModal()">
                <i class="fas fa-plus me-2"></i>Upload File
            </button>
        </div>

        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-hover table-custom" id="downloadsTable">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Size</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="downloadsList">
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin text-muted mb-2"></i>
                                <p class="text-muted mb-0">Loading downloads...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Upload Download</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="downloadForm">
                        <input type="hidden" id="downloadId" name="id">
                        
                        <div class="mb-3" id="fileGroup">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" id="downloadFile" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.txt,.csv">
                            <small class="text-muted">Allowed: PDF, Word, Excel, PowerPoint, ZIP, RAR, 7Z, TXT, CSV</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="downloadTitle" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="downloadDescription" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-gradient" onclick="saveDownload()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let downloadModal;
        let downloads = [];

        document.addEventListener('DOMContentLoaded', () => {
            downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
            loadDownloads();
        });

        function loadDownloads() {
            fetch('api/downloads.php')
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        downloads = data.downloads || [];
                        renderDownloads();
                    } else {
                        alert('Failed to load downloads');
                    }
                })
                .catch(err => console.error(err));
        }

        function renderDownloads() {
            const tbody = document.getElementById('downloadsList');
            if (downloads.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">No downloads found.</td></tr>`;
                return;
            }

            let html = '';
            downloads.forEach(d => {
                const icon = getFileIcon(d.file_name);
                const size = formatFileSize(d.file_size);
                const date = new Date(d.created_at).toLocaleDateString();
                const url = '../' + d.file_path; // Frontend path

                html += `
                    <tr>
                        <td style="width:50px"><i class="${icon} fa-2x text-primary"></i></td>
                        <td class="fw-bold">${escapeHtml(d.title)}</td>
                        <td><small class="text-muted d-block" style="max-width:250px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(d.description || '-')}</small></td>
                        <td>${size}</td>
                        <td>${date}</td>
                        <td class="text-end">
                            <a href="${url}" target="_blank" class="btn btn-sm btn-outline-info action-btn" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-primary action-btn" onclick="editDownload(${d.id})" title="Edit Info">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" onclick="deleteDownload(${d.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function showAddModal() {
            document.getElementById('downloadForm').reset();
            document.getElementById('downloadId').value = '';
            document.getElementById('modalTitle').textContent = 'Upload Download';
            document.getElementById('fileGroup').style.display = 'block';
            document.getElementById('downloadFile').required = true;
            downloadModal.show();
        }

        function editDownload(id) {
            const d = downloads.find(x => x.id === id);
            if(!d) return;

            document.getElementById('downloadForm').reset();
            document.getElementById('downloadId').value = d.id;
            document.getElementById('downloadTitle').value = d.title;
            document.getElementById('downloadDescription').value = d.description || '';
            
            document.getElementById('modalTitle').textContent = 'Edit Download Info';
            document.getElementById('fileGroup').style.display = 'none';
            document.getElementById('downloadFile').required = false;
            
            downloadModal.show();
        }

        async function saveDownload() {
            const id = document.getElementById('downloadId').value;
            const title = document.getElementById('downloadTitle').value;
            const desc = document.getElementById('downloadDescription').value;
            const fileInput = document.getElementById('downloadFile');

            if (!title) {
                alert('Title is required');
                return;
            }

            try {
                if (id) {
                    // Update
                    const res = await fetch('api/downloads.php', {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: id, title: title, description: desc})
                    });
                    const data = await res.json();
                    if(data.success) {
                        downloadModal.hide();
                        loadDownloads();
                    } else {
                        alert(data.message);
                    }
                } else {
                    // Create
                    if (!fileInput.files.length) {
                        alert('File is required for new uploads');
                        return;
                    }
                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);
                    formData.append('title', title);
                    formData.append('description', desc);

                    const res = await fetch('api/downloads.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if(data.success) {
                        alert(data.message || 'File uploaded successfully');
                        downloadModal.hide();
                        loadDownloads();
                    } else {
                        alert(data.message || 'Upload failed. The file might be too large or type not allowed.');
                    }
                }
            } catch(e) {
                console.error(e);
                alert('An error occurred');
            }
        }

        async function deleteDownload(id) {
            if(!confirm('Are you sure you want to delete this file? This action cannot be undone.')) return;
            
            try {
                const res = await fetch('api/downloads.php', {
                    method: 'DELETE',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                });
                const data = await res.json();
                if(data.success) {
                    loadDownloads();
                } else {
                    alert(data.message);
                }
            } catch(e) {
                console.error(e);
                alert('An error occurred');
            }
        }

        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if(['pdf'].includes(ext)) return 'fas fa-file-pdf';
            if(['doc','docx'].includes(ext)) return 'fas fa-file-word';
            if(['xls','xlsx'].includes(ext)) return 'fas fa-file-excel';
            if(['ppt','pptx'].includes(ext)) return 'fas fa-file-powerpoint';
            if(['zip','rar','7z'].includes(ext)) return 'fas fa-file-archive';
            return 'fas fa-file-alt';
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
