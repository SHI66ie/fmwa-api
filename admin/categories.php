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
    <title>Categories - FMWA Admin</title>
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
        
        .category-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .category-item:hover {
            background: #f8f9fa;
        }
        
        .category-item:last-child {
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
                <a href="categories.php" class="nav-link active">
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
                <h3 class="mb-0">Categories</h3>
                <small class="text-muted">Organize your content with categories</small>
            </div>
            <div>
                <button class="btn btn-gradient" onclick="showAddCategoryModal()">
                    <i class="fas fa-plus me-2"></i>New Category
                </button>
            </div>
        </div>

        <div class="content-card">
            <div class="mb-4">
                <input type="text" id="searchCategories" class="form-control" placeholder="Search categories...">
            </div>

            <div id="categoriesContainer">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Loading categories...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalTitle">New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        <input type="hidden" id="categoryId" name="id">
                        
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categorySlug" class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="categorySlug" name="slug" required>
                            <small class="text-muted">URL-friendly name (auto-generated from name)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoryStatus" class="form-label">Status *</label>
                            <select class="form-select" id="categoryStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-gradient" onclick="saveCategory()">Save Category</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let categoryModal;
        let categories = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
            
            loadCategories();
            
            // Search
            document.getElementById('searchCategories').addEventListener('input', filterCategories);
            
            // Auto-generate slug from name
            document.getElementById('categoryName').addEventListener('input', function() {
                const name = this.value;
                const slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                document.getElementById('categorySlug').value = slug;
            });
        });
        
        function loadCategories() {
            fetch('api/categories.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        categories = data.data || data.categories || [];
                        displayCategories(categories);
                    } else {
                        showError('Failed to load categories');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('An error occurred while loading categories');
                });
        }
        
        function displayCategories(categoriesToDisplay) {
            const container = document.getElementById('categoriesContainer');
            
            if (categoriesToDisplay.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No categories found</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            categoriesToDisplay.forEach(category => {
                const statusClass = category.status === 'active' ? 'success' : 'secondary';
                const postCount = category.post_count || 0;
                const date = new Date(category.created_at).toLocaleDateString();
                
                html += `
                    <div class="category-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-2">${escapeHtml(category.name)}</h5>
                                ${category.description ? `<p class="text-muted mb-2">${escapeHtml(category.description)}</p>` : ''}
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-${statusClass}">${category.status}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-newspaper me-1"></i>${postCount} post${postCount !== 1 ? 's' : ''}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-link me-1"></i>${category.slug}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>${date}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="editCategory(${category.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${category.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        function filterCategories() {
            const search = document.getElementById('searchCategories').value.toLowerCase();
            
            let filtered = categories.filter(category => {
                return !search || 
                    category.name.toLowerCase().includes(search) || 
                    (category.description && category.description.toLowerCase().includes(search)) ||
                    category.slug.toLowerCase().includes(search);
            });
            
            displayCategories(filtered);
        }
        
        function showAddCategoryModal() {
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryModalTitle').textContent = 'New Category';
            document.getElementById('categoryStatus').value = 'active';
            categoryModal.show();
        }
        
        function editCategory(id) {
            const category = categories.find(c => c.id === id);
            if (!category) return;
            
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categorySlug').value = category.slug;
            document.getElementById('categoryDescription').value = category.description || '';
            document.getElementById('categoryStatus').value = category.status;
            
            document.getElementById('categoryModalTitle').textContent = 'Edit Category';
            categoryModal.show();
        }
        
        function saveCategory() {
            const form = document.getElementById('categoryForm');
            const formData = new FormData(form);
            
            const categoryId = document.getElementById('categoryId').value;
            const method = categoryId ? 'PUT' : 'POST';
            const url = 'api/categories.php';
            
            // Convert FormData to JSON
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
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
                    categoryModal.hide();
                    loadCategories();
                    showSuccess(categoryId ? 'Category updated successfully' : 'Category created successfully');
                } else {
                    showError(data.message || 'Failed to save category');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while saving the category');
            });
        }
        
        function deleteCategory(id) {
            if (!confirm('Are you sure you want to delete this category?')) {
                return;
            }
            
            fetch('api/categories.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCategories();
                    showSuccess('Category deleted successfully');
                } else {
                    showError(data.message || 'Failed to delete category');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while deleting the category');
            });
        }
        
        function escapeHtml(text) {
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
