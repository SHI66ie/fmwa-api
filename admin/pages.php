<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();

// Organize pages into categories
$pageCategories = [];

// Main Pages
$pageCategories['Main Pages'] = [
    'index.php' => ['name' => 'Home Page', 'icon' => 'fa-home', 'description' => 'Main landing page'],
    'about.php' => ['name' => 'About Us', 'icon' => 'fa-info-circle', 'description' => 'Ministry information'],
    'mandate.php' => ['name' => 'Mandate', 'icon' => 'fa-file-alt', 'description' => 'Ministry mandate'],
    'organogram.php' => ['name' => 'Organogram', 'icon' => 'fa-sitemap', 'description' => 'Organizational structure'],
];

// Departments
$departmentsDir = '../departments';
$pageCategories['Departments'] = [];
if (is_dir($departmentsDir)) {
    $deptFiles = glob($departmentsDir . '/*.php');
    foreach ($deptFiles as $file) {
        $filename = basename($file);
        $name = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $filename));
        $pageCategories['Departments']['departments/' . $filename] = [
            'name' => $name,
            'icon' => 'fa-building',
            'description' => 'Department page'
        ];
    }
}

// Services
$servicesDir = '../services';
$pageCategories['Services'] = [];
if (is_dir($servicesDir)) {
    $serviceFiles = glob($servicesDir . '/*.php');
    foreach ($serviceFiles as $file) {
        $filename = basename($file);
        $name = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $filename));
        $pageCategories['Services']['services/' . $filename] = [
            'name' => $name,
            'icon' => 'fa-hands-helping',
            'description' => 'Service page'
        ];
    }
}

// Special Pages
$pageCategories['Special Pages'] = [
    'press-release-actu-inauguration.php' => ['name' => 'Press Release - ACTU Inauguration', 'icon' => 'fa-newspaper', 'description' => 'Press release'],
];

// Components (includes)
$includesDir = '../includes';
$pageCategories['Components'] = [];
if (is_dir($includesDir)) {
    $includeFiles = glob($includesDir . '/*.php');
    foreach ($includeFiles as $file) {
        $filename = basename($file);
        $name = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $filename));
        $pageCategories['Components']['includes/' . $filename] = [
            'name' => $name,
            'icon' => 'fa-puzzle-piece',
            'description' => 'Reusable component'
        ];
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
        
        .category-section {
            margin-bottom: 30px;
        }
        
        .category-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .page-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .page-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .page-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        
        .page-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        
        .page-card-icon {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .page-card-name {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .page-card-description {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .tab-navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 10px 20px;
            border: 2px solid #e9ecef;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .tab-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }
        
        .tab-btn.active {
            background: var(--primary-gradient);
            border-color: #667eea;
            color: white;
        }
        
        .search-box {
            margin-bottom: 20px;
        }
        
        .CodeMirror {
            height: 600px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            font-size: 14px;
            color: #f8f8f2;
            transition: box-shadow 0.2s ease;
        }

        .CodeMirror pre,
        .CodeMirror span {
            color: #f8f8f2;
        }

        .CodeMirror.editor-highlight {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.7);
        }

        /* Highlight for specific section lines in the editor */
        .cm-section-highlight {
            background-color: rgba(255, 235, 59, 0.18);
        }
        
        .preview-panel {
            height: 100%;
        }

        #pagePreview {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 10px;
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

            <!-- Search Box -->
            <div class="search-box">
                <input type="text" class="form-control" id="searchPages" placeholder="Search pages..." onkeyup="filterPages()">
            </div>
            
            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <?php $first = true; foreach ($pageCategories as $category => $pages): ?>
                    <?php if (!empty($pages)): ?>
                        <button class="tab-btn <?php echo $first ? 'active' : ''; ?>" onclick="showCategory('<?php echo strtolower(str_replace(' ', '-', $category)); ?>')">
                            <?php echo $category; ?> (<?php echo count($pages); ?>)
                        </button>
                        <?php $first = false; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Page Selection Panels -->
            <div id="pagePanels">
                <?php $firstCat = true; foreach ($pageCategories as $category => $pages): ?>
                    <?php if (!empty($pages)): ?>
                        <div class="category-section" id="cat-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" style="display: <?php echo $firstCat ? 'block' : 'none'; ?>;">
                            <h5 class="category-title">
                                <i class="fas fa-folder-open me-2"></i><?php echo $category; ?>
                            </h5>
                            <div class="page-grid">
                                <?php foreach ($pages as $path => $info): ?>
                                    <div class="page-card" onclick="selectPage('<?php echo htmlspecialchars($path); ?>')" data-path="<?php echo htmlspecialchars($path); ?>" data-name="<?php echo htmlspecialchars($info['name']); ?>">
                                        <div class="page-card-icon">
                                            <i class="fas <?php echo $info['icon']; ?>"></i>
                                        </div>
                                        <div class="page-card-name"><?php echo htmlspecialchars($info['name']); ?></div>
                                        <div class="page-card-description"><?php echo htmlspecialchars($info['description']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $firstCat = false; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div id="selectedPageInfo" style="display: none;" class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Selected:</strong> <span id="selectedPageName"></span>
                <button class="btn btn-sm btn-outline-success float-end" onclick="loadSelectedPage()">
                    <i class="fas fa-edit me-1"></i>Edit This Page
                </button>
            </div>

            <div id="editorSection" style="display: none;">
                <div class="editor-toolbar">
                    <button class="btn btn-outline-secondary btn-sm" onclick="backToSelection()">
                        <i class="fas fa-arrow-left me-2"></i>Back to Selection
                    </button>
                    <button class="btn btn-gradient btn-sm" onclick="savePage()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="reloadPage()">
                        <i class="fas fa-sync me-2"></i>Reload
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="previewPage()">
                        <i class="fas fa-eye me-2"></i>Preview
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="triggerImageUpload()">
                        <i class="fas fa-image me-2"></i>Upload Image
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="triggerVideoUpload()">
                        <i class="fas fa-video me-2"></i>Upload Video
                    </button>
                    <button class="btn btn-outline-primary btn-sm" id="toggleSectionMapBtn" type="button" onclick="toggleSectionMapping()" title="Toggle section hover sync">
                        <i class="fas fa-mouse-pointer"></i>
                    </button>
                    <div class="ms-auto">
                        <small class="text-muted" id="editorStatus"></small>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-lg-7 col-md-12">
                        <textarea id="codeEditor"></textarea>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <div class="card preview-panel">
                            <div class="card-header">
                                <strong>Live Preview</strong>
                            </div>
                            <div class="card-body p-0">
                                <iframe id="pagePreview" title="Page preview"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="file" id="imageUploadInput" accept="image/*" style="display: none;">
                <input type="file" id="videoUploadInput" accept="video/*" style="display: none;">
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
        let selectedPath = '';
        let selectedName = '';
        let sectionMappingEnabled = false;
        let currentSectionMarker = null;
        let imageUploadInput = null;
        let videoUploadInput = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CodeMirror
            editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
                // Use HTML mode instead of PHP mode to avoid php.min.js tokenizer crashes
                mode: 'text/html',
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

            imageUploadInput = document.getElementById('imageUploadInput');
            videoUploadInput = document.getElementById('videoUploadInput');

            if (imageUploadInput) {
                imageUploadInput.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file) {
                        handleMediaUpload(file, 'image');
                    }
                    e.target.value = '';
                });
            }

            if (videoUploadInput) {
                videoUploadInput.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file) {
                        handleMediaUpload(file, 'video');
                    }
                    e.target.value = '';
                });
            }

            const previewFrame = document.getElementById('pagePreview');
            if (previewFrame) {
                if (editor && typeof editor.getWrapperElement === 'function') {
                    const wrapper = editor.getWrapperElement();
                    if (wrapper) {
                        previewFrame.addEventListener('mouseenter', function() {
                            wrapper.classList.add('editor-highlight');
                        });
                        previewFrame.addEventListener('mouseleave', function() {
                            wrapper.classList.remove('editor-highlight');
                        });
                    }
                }

                // When the preview reloads, (re)attach section-level listeners if mapping is enabled
                previewFrame.addEventListener('load', function() {
                    if (sectionMappingEnabled) {
                        setupPreviewSectionListeners();
                    }
                });
            }
        });
        
        function toggleSectionMapping() {
            sectionMappingEnabled = !sectionMappingEnabled;
            const btn = document.getElementById('toggleSectionMapBtn');
            if (btn) {
                if (sectionMappingEnabled) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            }

            const previewFrame = document.getElementById('pagePreview');
            if (sectionMappingEnabled && previewFrame) {
                setPreviewInteractiveDisabled(true);
                setupPreviewSectionListeners();
            } else {
                setPreviewInteractiveDisabled(false);
                clearCodeSectionHighlight();
            }
        }

        function setupPreviewSectionListeners() {
            const previewFrame = document.getElementById('pagePreview');
            if (!previewFrame || !previewFrame.contentWindow) {
                return;
            }

            let doc;
            try {
                doc = previewFrame.contentDocument || previewFrame.contentWindow.document;
            } catch (e) {
                console.warn('Unable to access preview frame document', e);
                return;
            }

            if (!doc) return;

            // When mapping is enabled, make embedded media non-interactive so clicks are used for selection
            if (sectionMappingEnabled) {
                setPreviewInteractiveDisabled(true);
            }

            // Inject a small style block once for hover outlines in the preview
            if (!doc.getElementById('fmwa-editor-preview-style')) {
                const style = doc.createElement('style');
                style.id = 'fmwa-editor-preview-style';
                style.textContent = '\n                    .fmwa-section-hover {\n                        outline: 2px solid rgba(102, 126, 234, 0.9);\n                        outline-offset: 2px;\n                        cursor: pointer !important;\n                    }\n                ';
                if (doc.head) {
                    doc.head.appendChild(style);
                }
            }

            // Allow clicking "any" element by resolving the nearest logical section
            const candidates = doc.querySelectorAll('body *');
            candidates.forEach(function(el) {
                let sectionEl = el.closest('[data-editor-target], section[id], div[id], article[id]');
                let identifier = null;

                if (sectionEl) {
                    const idOrTarget = sectionEl.getAttribute('data-editor-target') || sectionEl.id;
                    if (idOrTarget) {
                        identifier = 'id::' + idOrTarget;
                    }
                }

                // Fallbacks for elements without a useful ancestor id/data-editor-target
                if (!identifier) {
                    // Direct link (e.g. Read More)
                    if (el.tagName === 'A' && el.getAttribute('href')) {
                        sectionEl = el;
                        identifier = 'href::' + el.getAttribute('href');
                    } else if ((el.tagName === 'IFRAME' || el.tagName === 'VIDEO' || el.tagName === 'IMG') && el.getAttribute('src')) {
                        // Direct media element
                        sectionEl = el;
                        identifier = 'src::' + el.getAttribute('src');
                    } else {
                        // Look upwards or downwards for a link or media element we can anchor to
                        const link = el.closest('a[href]');
                        if (link && link.getAttribute('href')) {
                            sectionEl = link;
                            identifier = 'href::' + link.getAttribute('href');
                        } else {
                            let media = el.closest('iframe[src], video[src], img[src]');
                            if (!media) {
                                media = el.querySelector('iframe[src], video[src], img[src]');
                            }
                            if (media && media.getAttribute('src')) {
                                sectionEl = media;
                                identifier = 'src::' + media.getAttribute('src');
                            }
                        }
                    }
                }

                if (!sectionEl || !identifier) return;

                el.addEventListener('mouseenter', function() {
                    if (!sectionMappingEnabled) return;
                    sectionEl.classList.add('fmwa-section-hover');
                    try {
                        highlightCodeForIdentifier(identifier, false);
                    } catch (err) {
                        console.warn('Highlight error (hover):', err);
                    }
                });

                el.addEventListener('mouseleave', function() {
                    if (!sectionMappingEnabled) return;
                    sectionEl.classList.remove('fmwa-section-hover');
                    clearCodeSectionHighlight();
                });

                el.addEventListener('click', function(e) {
                    if (!sectionMappingEnabled) return;
                    e.preventDefault();
                    e.stopPropagation();
                    try {
                        highlightCodeForIdentifier(identifier, true);
                    } catch (err) {
                        console.warn('Highlight error (click):', err);
                    }
                });
            });
        }

        function setPreviewInteractiveDisabled(disabled) {
            const previewFrame = document.getElementById('pagePreview');
            if (!previewFrame || !previewFrame.contentWindow) {
                return;
            }

            let doc;
            try {
                doc = previewFrame.contentDocument || previewFrame.contentWindow.document;
            } catch (e) {
                console.warn('Unable to access preview frame document for pointer toggle', e);
                return;
            }

            if (!doc) return;

            const mediaNodes = doc.querySelectorAll('iframe, video, audio');
            mediaNodes.forEach(function(node) {
                if (disabled) {
                    if (!node.hasAttribute('data-fmwa-pointer-original')) {
                        node.setAttribute('data-fmwa-pointer-original', node.style.pointerEvents || '');
                    }
                    node.style.pointerEvents = 'none';
                } else {
                    if (node.hasAttribute('data-fmwa-pointer-original')) {
                        node.style.pointerEvents = node.getAttribute('data-fmwa-pointer-original');
                        node.removeAttribute('data-fmwa-pointer-original');
                    }
                }
            });
        }

        function highlightCodeForIdentifier(identifier, scrollIntoView) {
            if (!editor || !identifier) {
                return;
            }

            const range = findCodeRangeForIdentifier(identifier);
            if (!range) {
                clearCodeSectionHighlight();
                return;
            }

            clearCodeSectionHighlight();

            const totalLines = editor.lineCount ? editor.lineCount() : 0;
            if (!totalLines) return;

            const startLine = Math.max(0, Math.min(range.startLine, totalLines - 1));
            const endLine = Math.max(startLine, Math.min(range.endLine, totalLines - 1));
            const from = { line: startLine, ch: 0 };
            const endText = editor.getLine(endLine) || '';
            const to = { line: endLine, ch: endText.length };

            currentSectionMarker = editor.markText(from, to, { className: 'cm-section-highlight' });

            if (scrollIntoView) {
                editor.scrollIntoView(from, 100);
                editor.setCursor(from);
            }
        }

        function clearCodeSectionHighlight() {
            if (currentSectionMarker) {
                currentSectionMarker.clear();
                currentSectionMarker = null;
            }
        }

        function findCodeRangeForIdentifier(identifier) {
            if (!editor) return null;

            let mode = 'id';
            let value = identifier;
            if (identifier.indexOf('id::') === 0) {
                mode = 'id';
                value = identifier.substring(4);
            } else if (identifier.indexOf('href::') === 0) {
                mode = 'href';
                value = identifier.substring(6);
            } else if (identifier.indexOf('src::') === 0) {
                mode = 'src';
                value = identifier.substring(5);
            }

            const lineCount = editor.lineCount ? editor.lineCount() : 0;
            let patterns;
            if (mode === 'href') {
                patterns = [
                    'href="' + value + '"',
                    "href='" + value + "'"
                ];
            } else if (mode === 'src') {
                patterns = [
                    'src="' + value + '"',
                    "src='" + value + "'"
                ];
            } else {
                // default id / data-editor-target lookup
                patterns = [
                    'id="' + value + '"',
                    "id='" + value + "'",
                    'data-editor-target="' + value + '"',
                    "data-editor-target='" + value + "'"
                ];
            }

            let startLine = -1;
            for (let i = 0; i < lineCount; i++) {
                const lineText = editor.getLine(i);
                for (let j = 0; j < patterns.length; j++) {
                    if (lineText.indexOf(patterns[j]) !== -1) {
                        startLine = i;
                        break;
                    }
                }
                if (startLine !== -1) break;
            }

            if (startLine === -1) return null;

            let endLine = Math.min(startLine + 20, lineCount - 1);

            for (let k = startLine + 1; k <= endLine; k++) {
                const txt = editor.getLine(k);
                if (txt.indexOf('</section>') !== -1 || txt.indexOf('</div>') !== -1 || txt.indexOf('</article>') !== -1) {
                    endLine = k;
                    break;
                }
            }

            return { startLine: startLine, endLine: endLine };
        }

        // Select a page card
        function selectPage(path) {
            // Remove selection from all cards
            document.querySelectorAll('.page-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked card
            const clickedCard = document.querySelector(`[data-path="${path}"]`);
            if (clickedCard) {
                clickedCard.classList.add('selected');
                selectedPath = path;
                selectedName = clickedCard.getAttribute('data-name');
                
                // Show selected page info
                document.getElementById('selectedPageName').textContent = selectedName;
                document.getElementById('selectedPageInfo').style.display = 'block';
            }
        }
        
        // Load the selected page into editor
        function loadSelectedPage() {
            if (!selectedPath) {
                showError('No page selected');
                return;
            }
            
            loadPage(selectedPath);
            
            // Scroll to editor
            document.getElementById('editorSection').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Filter pages by search
        function filterPages() {
            const searchTerm = document.getElementById('searchPages').value.toLowerCase();
            
            document.querySelectorAll('.page-card').forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const path = card.getAttribute('data-path').toLowerCase();
                
                if (name.includes(searchTerm) || path.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        function loadPage(path) {
            if (!path) {
                showError('No page path provided');
                return;
            }
            
            currentPage = path;
            updateStatus('Loading...');
            
            // Hide page panels and show editor
            document.getElementById('pagePanels').style.display = 'none';
            document.getElementById('selectedPageInfo').style.display = 'none';
            document.querySelector('.tab-navigation').style.display = 'none';
            document.querySelector('.search-box').style.display = 'none';
            
            fetch(`/api/page.php?path=${encodeURIComponent(path)}`)
                .then(response => response.text())
                .then(text => {
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse page API response:', e, text);
                        showError('Failed to load page');
                        updateStatus('Error');
                        // Show panels again
                        document.getElementById('pagePanels').style.display = 'block';
                        document.querySelector('.tab-navigation').style.display = 'flex';
                        document.querySelector('.search-box').style.display = 'block';
                        return;
                    }

                    if (data && data.success) {
                        originalContent = data.content || '';
                        document.getElementById('editorSection').style.display = 'block';
                        document.getElementById('noPageSelected').style.display = 'none';
                        editor.setValue(originalContent);
                        // Refresh CodeMirror after showing the editor to fix blank display when initialized hidden
                        if (editor && typeof editor.refresh === 'function') {
                            editor.refresh();
                        }
                        // Update live preview iframe to show the current page
                        const previewFrame = document.getElementById('pagePreview');
                        if (previewFrame) {
                            previewFrame.src = '../' + currentPage;
                        }
                        updateStatus('Ready');
                    } else {
                        showError((data && data.message) || 'Failed to load page');
                        updateStatus('Error');
                        // Show panels again
                        document.getElementById('pagePanels').style.display = 'block';
                        document.querySelector('.tab-navigation').style.display = 'flex';
                        document.querySelector('.search-box').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error calling page API:', error);
                    showError('Failed to load page');
                    updateStatus('Error');
                    // Show panels again
                    document.getElementById('pagePanels').style.display = 'block';
                    document.querySelector('.tab-navigation').style.display = 'flex';
                    document.querySelector('.search-box').style.display = 'block';
                });
        }
        
        function savePage() {
            if (!currentPage) {
                showError('No page selected');
                return;
            }
            
            updateStatus('Saving...');
            
            const content = editor.getValue();

            const formData = new FormData();
            formData.append('path', currentPage);
            formData.append('content', content);
            
            fetch('/api/page.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    originalContent = content;
                    showSuccess('Page saved successfully');
                    updateStatus('Saved');
                    // Refresh live preview so changes are visible immediately
                    const previewFrame = document.getElementById('pagePreview');
                    if (previewFrame && currentPage) {
                        let url = '../' + currentPage;
                        const cacheBust = 't=' + Date.now();
                        url += (url.indexOf('?') === -1 ? '?' : '&') + cacheBust;
                        previewFrame.src = url;
                    }
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
            
            loadPage(currentPage);
        }
        
        function backToSelection() {
            if (editor.getValue() !== originalContent) {
                if (!confirm('You have unsaved changes. Are you sure you want to go back?')) {
                    return;
                }
            }
            
            // Hide editor and show panels
            document.getElementById('editorSection').style.display = 'none';
            document.getElementById('pagePanels').style.display = 'block';
            document.getElementById('selectedPageInfo').style.display = 'none';
            document.querySelector('.tab-navigation').style.display = 'flex';
            document.querySelector('.search-box').style.display = 'block';
            document.getElementById('noPageSelected').style.display = 'block';
            
            // Clear selection
            document.querySelectorAll('.page-card').forEach(card => {
                card.classList.remove('selected');
            });
            selectedPath = '';
            selectedName = '';
            currentPage = '';
        }
        
        function previewPage() {
            if (!currentPage) {
                showError('No page selected');
                return;
            }
            
            const previewFrame = document.getElementById('pagePreview');
            if (previewFrame) {
                previewFrame.src = '../' + currentPage;
            } else {
                const previewUrl = '../' + currentPage;
                window.open(previewUrl, '_blank');
            }
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
