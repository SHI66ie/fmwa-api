<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();

// Get all categories for the form
try {
    $stmt = $pdo->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts & News - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Load TinyMCE from CDN instead of missing local assets path -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
        
        .btn-success-gradient {
            background: var(--success-gradient);
            border: none;
            color: white;
        }
        
        .btn-danger-gradient {
            background: var(--warning-gradient);
            border: none;
            color: white;
        }
        
        .post-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .post-item:hover {
            background: #f8f9fa;
        }
        
        .post-item:last-child {
            border-bottom: none;
        }
        
        .badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .modal-content {
            border-radius: 15px;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px;
        }
        
        .form-control:focus, .form-select:focus {
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
                <a href="posts.php" class="nav-link active">
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
                <h3 class="mb-0">Posts & News</h3>
                <small class="text-muted">Manage your posts and news articles</small>
            </div>
            <div>
                <button class="btn btn-gradient" onclick="showAddPostModal()">
                    <i class="fas fa-plus me-2"></i>New Post
                </button>
            </div>
        </div>

        <div class="content-card">
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="searchPosts" class="form-control" placeholder="Search posts...">
                    </div>
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filterCategory" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div id="postsContainer">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Loading posts...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Post Modal -->
    <div class="modal fade" id="postModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalTitle">New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="postForm">
                        <input type="hidden" id="postId" name="id">
                        
                        <div class="mb-3">
                            <label for="postTitle" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="postTitle" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="postExcerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="postExcerpt" name="excerpt" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="postContent" class="form-label">Content *</label>
                            <textarea class="form-control" id="postContent" name="content" rows="10"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postStatus" class="form-label">Status *</label>
                                <select class="form-select" id="postStatus" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="postFeaturedImage" class="form-label">Featured Image URL</label>
                                <input type="text" class="form-control" id="postFeaturedImage" name="featured_image">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <div id="categoriesCheckboxes">
                                <?php foreach ($categories as $cat): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="categories[]" value="<?php echo $cat['id']; ?>" 
                                               id="cat<?php echo $cat['id']; ?>">
                                        <label class="form-check-label" for="cat<?php echo $cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-gradient" onclick="savePost()">Save Post</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let postModal;
        let posts = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            postModal = new bootstrap.Modal(document.getElementById('postModal'));
            
            // Initialize TinyMCE only if it loaded successfully
            if (window.tinymce) {
                tinymce.init({
                    selector: '#postContent',
                    height: 400,
                    menubar: false,
                    plugins: 'lists link image table code',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
                });
            } else {
                console.error('TinyMCE script not loaded; posts editor will use plain textarea.');
            }

            loadPosts();
            
            // Search and filters
            document.getElementById('searchPosts').addEventListener('input', filterPosts);
            document.getElementById('filterStatus').addEventListener('change', filterPosts);
            document.getElementById('filterCategory').addEventListener('change', filterPosts);
        });
        
        function loadPosts() {
            fetch('/admin/api/posts.php')
                .then(response => response.text())
                .then(text => {
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse posts API response:', e, text);
                        showError('Failed to load posts');
                        const container = document.getElementById('postsContainer');
                        if (container) {
                            container.innerHTML = `
                                <div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <p class="text-muted">Failed to load posts. Please try reloading the page.</p>
                                </div>
                            `;
                        }
                        return;
                    }

                    if (data && data.success) {
                        posts = data.data || data.posts || [];
                        displayPosts(posts);
                    } else {
                        showError((data && data.message) || 'Failed to load posts');
                        const container = document.getElementById('postsContainer');
                        if (container) {
                            container.innerHTML = `
                                <div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <p class="text-muted">Unable to load posts from the server.</p>
                                </div>
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading posts:', error);
                    showError('An error occurred while loading posts');
                    const container = document.getElementById('postsContainer');
                    if (container) {
                        container.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <p class="text-muted">Network error while loading posts. Please check your connection or try again.</p>
                            </div>
                        `;
                    }
                });
        }
        
        function displayPosts(postsToDisplay) {
            const container = document.getElementById('postsContainer');
            
            if (postsToDisplay.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No posts found</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            postsToDisplay.forEach(post => {
                const statusClass = post.status === 'published' ? 'success' : 'warning';
                const date = new Date(post.created_at).toLocaleDateString();
                
                html += `
                    <div class="post-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-2">${escapeHtml(post.title)}</h5>
                                ${post.excerpt ? `<p class="text-muted mb-2">${escapeHtml(post.excerpt)}</p>` : ''}
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-${statusClass}">${post.status}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>${date}
                                    </small>
                                    ${post.categories ? `
                                        <small class="text-muted">
                                            <i class="fas fa-tags me-1"></i>${post.categories}
                                        </small>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="editPost(${post.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deletePost(${post.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        function filterPosts() {
            const search = document.getElementById('searchPosts').value.toLowerCase();
            const status = document.getElementById('filterStatus').value;
            const category = document.getElementById('filterCategory').value;
            
            let filtered = posts.filter(post => {
                const matchesSearch = !search || 
                    post.title.toLowerCase().includes(search) || 
                    (post.excerpt && post.excerpt.toLowerCase().includes(search));
                const matchesStatus = !status || post.status === status;
                const matchesCategory = !category || 
                    (post.category_ids && post.category_ids.includes(parseInt(category)));
                
                return matchesSearch && matchesStatus && matchesCategory;
            });
            
            displayPosts(filtered);
        }
        
        function showAddPostModal() {
            document.getElementById('postForm').reset();
            document.getElementById('postId').value = '';
            document.getElementById('postModalTitle').textContent = 'New Post';
            document.querySelectorAll('input[name="categories[]"]').forEach(cb => cb.checked = false);
            
            if (window.tinymce && tinymce.get('postContent')) {
                tinymce.get('postContent').setContent('');
            }
            
            postModal.show();
        }
        
        function editPost(id) {
            fetch(`/admin/api/posts.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const post = data.data || data.post;
                        
                        document.getElementById('postId').value = post.id;
                        document.getElementById('postTitle').value = post.title;
                        document.getElementById('postExcerpt').value = post.excerpt || '';
                        document.getElementById('postStatus').value = post.status;
                        document.getElementById('postFeaturedImage').value = post.featured_image || '';
                        
                        if (window.tinymce && tinymce.get('postContent')) {
                            tinymce.get('postContent').setContent(post.content || '');
                        }
                        
                        // Set categories
                        document.querySelectorAll('input[name="categories[]"]').forEach(cb => {
                            cb.checked = post.category_ids && post.category_ids.includes(parseInt(cb.value));
                        });
                        
                        document.getElementById('postModalTitle').textContent = 'Edit Post';
                        postModal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load post');
                });
        }
        
        function savePost() {
            const form = document.getElementById('postForm');
            const formData = new FormData(form);
            
            // Get content from TinyMCE if available
            if (window.tinymce && tinymce.get('postContent')) {
                formData.set('content', tinymce.get('postContent').getContent());
            }
            
            // Get selected categories
            const categories = [];
            document.querySelectorAll('input[name="categories[]"]:checked').forEach(cb => {
                categories.push(cb.value);
            });
            formData.set('categories', JSON.stringify(categories));
            
            const postId = document.getElementById('postId').value;
            const method = postId ? 'PUT' : 'POST';
            const url = '/admin/api/posts.php';
            
            // Convert FormData to JSON
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key !== 'categories[]') {
                    data[key] = value;
                }
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    postModal.hide();
                    loadPosts();
                    showSuccess(postId ? 'Post updated successfully' : 'Post created successfully');
                } else {
                    showError(data.message || 'Failed to save post');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while saving the post');
            });
        }
        
        function deletePost(id) {
            if (!confirm('Are you sure you want to delete this post?')) {
                return;
            }
            
            fetch('/admin/api/posts.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPosts();
                    showSuccess('Post deleted successfully');
                } else {
                    showError(data.message || 'Failed to delete post');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while deleting the post');
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function showSuccess(message) {
            alert(message); // You can replace this with a better notification system
        }
        
        function showError(message) {
            alert('Error: ' + message); // You can replace this with a better notification system
        }
    </script>
</body>
</html>
