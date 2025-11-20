<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();

// Get list of editable pages
$pages = [
    'index.php' => 'Home Page',
    'about.php' => 'About Page',
    'mandate.php' => 'Mandate Page',
    'organogram.php' => 'Organogram Page',
];

// Scan departments directory
$departmentsDir = '../departments';
if (is_dir($departmentsDir)) {
    $deptFiles = glob($departmentsDir . '/*.php');
    foreach ($deptFiles as $file) {
        $filename = basename($file);
        $name = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $filename));
        $pages['departments/' . $filename] = $name;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Editor - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
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
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px;
        }
        
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .CodeMirror {
            height: 600px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            font-size: 14px;
        }
        
        .editor-toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
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
                <a href="pages.php" class="nav-link active">
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
            <h3 class="mb-0">Page Editor</h3>
            <small class="text-muted">Edit website pages with syntax highlighting</small>
        </div>

        <div class="content-card">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Tip:</strong> Use <kbd>Ctrl+S</kbd> to save. Changes are automatically backed up before saving.
            </div>

            <div class="mb-4">
                <label for="pageSelect" class="form-label">Select Page to Edit</label>
                <select class="form-select" id="pageSelect" onchange="loadPage()">
                    <option value="">-- Choose a page --</option>
                    <?php foreach ($pages as $path => $name): ?>
                        <option value="<?php echo htmlspecialchars($path); ?>">
                            <?php echo htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="editorSection" style="display: none;">
                <div class="editor-toolbar">
                    <button class="btn btn-gradient btn-sm" onclick="savePage()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="reloadPage()">
                        <i class="fas fa-sync me-2"></i>Reload
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="previewPage()">
                        <i class="fas fa-eye me-2"></i>Preview
                    </button>
                    <div class="ms-auto">
                        <small class="text-muted" id="editorStatus"></small>
                    </div>
                </div>

                <textarea id="codeEditor"></textarea>
            </div>

            <div id="noPageSelected" class="text-center py-5">
                <i class="fas fa-file-code fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Select a page to start editing</h5>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
    <script>
        let editor;
        let currentPage = '';
        let originalContent = '';
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CodeMirror
            editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
                mode: 'application/x-httpd-php',
                theme: 'dracula',
                lineNumbers: true,
                lineWrapping: true,
                indentUnit: 4,
                tabSize: 4,
                indentWithTabs: true,
                matchBrackets: true,
                autoCloseBrackets: true,
                extraKeys: {
                    "Ctrl-S": function(cm) {
                        savePage();
                        return false;
                    }
                }
            });
            
            // Track changes
            editor.on('change', function() {
                updateStatus('Modified');
            });
        });
        
        function loadPage() {
            const select = document.getElementById('pageSelect');
            const path = select.value;
            
            if (!path) {
                document.getElementById('editorSection').style.display = 'none';
                document.getElementById('noPageSelected').style.display = 'block';
                return;
            }
            
            currentPage = path;
            updateStatus('Loading...');
            
            fetch(`../api/page.php?path=${encodeURIComponent(path)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        originalContent = data.content;
                        editor.setValue(data.content);
                        document.getElementById('editorSection').style.display = 'block';
                        document.getElementById('noPageSelected').style.display = 'none';
                        updateStatus('Ready');
                    } else {
                        showError(data.message || 'Failed to load page');
                        updateStatus('Error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load page');
                    updateStatus('Error');
                });
        }
        
        function savePage() {
            if (!currentPage) {
                showError('No page selected');
                return;
            }
            
            updateStatus('Saving...');
            
            const content = editor.getValue();
            
            fetch('../api/page.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    path: currentPage,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    originalContent = content;
                    showSuccess('Page saved successfully');
                    updateStatus('Saved');
                } else {
                    showError(data.message || 'Failed to save page');
                    updateStatus('Save failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to save page');
                updateStatus('Save failed');
            });
        }
        
        function reloadPage() {
            if (!currentPage) return;
            
            if (editor.getValue() !== originalContent) {
                if (!confirm('You have unsaved changes. Are you sure you want to reload?')) {
                    return;
                }
            }
            
            loadPage();
        }
        
        function previewPage() {
            if (!currentPage) {
                showError('No page selected');
                return;
            }
            
            const previewUrl = '../' + currentPage;
            window.open(previewUrl, '_blank');
        }
        
        function updateStatus(status) {
            document.getElementById('editorStatus').textContent = status;
        }
        
        function showSuccess(message) {
            alert(message);
        }
        
        function showError(message) {
            alert('Error: ' + message);
        }
        
        // Prevent accidental page close with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (editor && editor.getValue() !== originalContent) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>
</html>
