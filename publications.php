<?php
require_once 'config.php';

// Fetch all downloads
try {
    $stmt = $pdo->query("SELECT * FROM downloads ORDER BY created_at DESC");
    $downloads = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $downloads = [];
}

// Function to get file icon
function getFileIcon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'pdf': return 'fa-file-pdf text-danger';
        case 'doc':
        case 'docx': return 'fa-file-word text-primary';
        case 'xls':
        case 'xlsx': return 'fa-file-excel text-success';
        case 'ppt':
        case 'pptx': return 'fa-file-powerpoint text-warning';
        case 'zip':
        case 'rar':
        case '7z': return 'fa-file-archive text-secondary';
        case 'txt':
        case 'csv': return 'fa-file-alt text-info';
        default: return 'fa-file-alt text-muted';
    }
}

// Function to format file size
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications & Downloads - Federal Ministry of Women Affairs</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/2025_07_14_13_42_IMG_2808.PNG">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/logo-position-fix.css?v=1.1">
    
    <!-- Include components -->
    <script src="js/include-components.js" defer></script>
    
    <style>
        body {
            padding-top: 120px;
            background-color: #f8f9fa;
        }
        
        .page-header {
            background: linear-gradient(135deg, #013a04 0%, #025a06 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        
        .download-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 5px solid #013a04;
        }
        
        .download-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }
        
        .file-icon {
            font-size: 2.5rem;
            margin-right: 20px;
        }
        
        .download-info h5 {
            color: #013a04;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .download-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .btn-download {
            background-color: #ffc107;
            color: #013a04;
            font-weight: 600;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
        }
        
        .btn-download:hover {
            background-color: #e0ac00;
            color: #013a04;
        }
        
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <!-- Header will be inserted here -->

    <header class="page-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Publications & Downloads</h1>
            <p class="lead">Official documents, reports, and publications from the Federal Ministry of Women Affairs.</p>
        </div>
    </header>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-12">
                <!-- Search and Filter -->
                <div class="search-container">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" id="fileSearch" class="form-control border-start-0" placeholder="Search for documents by title or description...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="typeFilter" class="form-select">
                                <option value="all">All File Types</option>
                                <option value="pdf">PDF Documents</option>
                                <option value="doc">Word Documents</option>
                                <option value="xls">Excel Sheets</option>
                                <option value="zip">Archive Files (ZIP/RAR)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="downloadsList">
                    <?php if (empty($downloads)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h3>No downloads found</h3>
                            <p class="text-muted">Please check back later for new publications.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($downloads as $file): ?>
                            <div class="download-card d-flex align-items-center justify-content-between flex-wrap" 
                                 data-title="<?php echo strtolower($file['title']); ?>" 
                                 data-ext="<?php echo strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION)); ?>">
                                <div class="d-flex align-items-center">
                                    <div class="file-icon">
                                        <i class="fas <?php echo getFileIcon($file['file_name']); ?>"></i>
                                    </div>
                                    <div class="download-info">
                                        <h5><?php echo htmlspecialchars($file['title']); ?></h5>
                                        <?php if ($file['description']): ?>
                                            <p class="mb-1 small"><?php echo htmlspecialchars($file['description']); ?></p>
                                        <?php endif; ?>
                                        <div class="download-meta">
                                            <span><i class="far fa-calendar-alt me-1"></i> <?php echo date('F j, Y', strtotime($file['created_at'])); ?></span>
                                            <span class="ms-3"><i class="fas fa-hdd me-1"></i> <?php echo formatBytes($file['file_size']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <a href="<?php echo htmlspecialchars($file['file_path']); ?>" class="btn btn-download px-4" download>
                                        <i class="fas fa-download me-2"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <?php $site_name = get_setting('site_name', 'Federal Ministry of Women Affairs'); ?>
                    <h5 class="mb-3"><?php echo htmlspecialchars($site_name); ?></h5>
                    <p class="text-light"><?php echo htmlspecialchars(get_setting('footer_about_text', 'Empowering women and promoting gender equality...')); ?></p>
                    <div class="social-links mt-3">
                        <?php if ($fb = get_setting('social_facebook')): ?>
                            <a href="<?php echo htmlspecialchars($fb); ?>" class="text-light me-3" title="Facebook" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($tw = get_setting('social_twitter')): ?>
                            <a href="<?php echo htmlspecialchars($tw); ?>" class="text-light me-3" title="X (Twitter)" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if ($ig = get_setting('social_instagram')): ?>
                            <a href="<?php echo htmlspecialchars($ig); ?>" class="text-light me-3" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($yt = get_setting('social_youtube')): ?>
                            <a href="<?php echo htmlspecialchars($yt); ?>" class="text-light" title="YouTube" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="mandate.php" class="text-muted text-decoration-none">Our Mandate</a></li>
                        <li class="mb-2"><a href="organogram.php" class="text-muted text-decoration-none">Organogram</a></li>
                    </ul>
                </div>
                
                <!-- Publications Section -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Publications</h5>
                    <p class="text-muted small">Latest documents and reports from the Ministry.</p>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <div class="contact-info">
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                            <?php echo nl2br(htmlspecialchars(get_setting('contact_address', 'Federal Secretariat Complex, Abuja'))); ?>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_phone', '+234-9-461-0000')); ?>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_email', 'info@fmwa.gov.ng')); ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2 text-warning"></i>
                            <?php echo htmlspecialchars(get_setting('contact_hours', 'Mon - Fri: 8:00 AM - 4:00 PM')); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom border-top border-secondary pt-4 mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_name); ?>. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('fileSearch');
            const typeFilter = document.getElementById('typeFilter');
            const downloadCards = document.querySelectorAll('.download-card');

            function filterFiles() {
                const query = searchInput.value.toLowerCase();
                const type = typeFilter.value;

                downloadCards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    const ext = card.getAttribute('data-ext');
                    
                    let show = title.includes(query);
                    
                    if (type !== 'all') {
                        if (type === 'zip') {
                            show = show && ['zip', 'rar', '7z'].includes(ext);
                        } else if (type === 'doc') {
                            show = show && ['doc', 'docx'].includes(ext);
                        } else if (type === 'xls') {
                            show = show && ['xls', 'xlsx'].includes(ext);
                        } else {
                            show = show && ext === type;
                        }
                    }

                    card.style.display = show ? 'flex' : 'none';
                });
            }

            searchInput.addEventListener('input', filterFiles);
            typeFilter.addEventListener('change', filterFiles);
        });
    </script>
</body>
</html>
