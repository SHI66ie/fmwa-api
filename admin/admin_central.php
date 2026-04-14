<?php
require_once '../config.php';
require_once 'auth.php';
require_once '../includes/helpers.php';

$auth = new Auth($pdo);
$auth->requireLogin();

$user = $auth->getCurrentUser();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings_to_save = [
        'minister_name', 'minister_title', 'minister_description',
        'perm_sec_name', 'perm_sec_title', 'perm_sec_description',
        'our_mandate', 'our_vision', 'our_mission', 'maintenance_mode',
        'contact_email', 'contact_phone', 'contact_address', 'contact_hours',
        'social_facebook', 'social_twitter', 'social_instagram', 'social_youtube',
        'footer_about_text', 'site_name', 'site_description', 'site_logo'
    ];
    
    // Explicitly handle image paths if no new image is uploaded
    $image_settings = ['minister_image', 'perm_sec_image'];
    
    try {
        $pdo->beginTransaction();
        
        // 1. Save Text Settings
        foreach ($settings_to_save as $key) {
            $value = $_POST[$key] ?? '';
            if ($key === 'maintenance_mode') {
                $value = isset($_POST[$key]) ? 'true' : 'false';
            }
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$value, $key]);
            if ($stmt->rowCount() === 0) {
                $stmt = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_type) VALUES (?, ?, 'string')");
                $stmt->execute([$key, $value]);
            }
        }

        // 2. Handle File Uploads
        $upload_dir = '../images/leadership/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $files_to_process = [
            'minister_photo' => 'minister_image',
            'perm_sec_photo' => 'perm_sec_image',
            'site_logo_file' => 'site_logo'
        ];

        foreach ($files_to_process as $input_name => $setting_key) {
            if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES[$input_name]['tmp_name'];
                $file_ext = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
                $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($file_ext, $allowed_exts)) {
                    $new_filename = $setting_key . '_' . time() . '.' . $file_ext;
                    $target_path = $upload_dir . $new_filename;
                    $db_path = 'images/leadership/' . $new_filename;
                    
                    if (move_uploaded_file($file_tmp, $target_path)) {
                        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                        $stmt->execute([$db_path, $setting_key]);
                        if ($stmt->rowCount() === 0) {
                            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, setting_type) VALUES (?, ?, 'string')");
                            $stmt->execute([$setting_key, $db_path]);
                        }
                    }
                }
            } else if (isset($_POST[$setting_key])) {
                // Keep existing path if manually edited in text field or selected via library
                $value = $_POST[$setting_key];
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $setting_key]);
                if ($stmt->rowCount() === 0) {
                    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, setting_type) VALUES (?, ?, 'string')");
                    $stmt->execute([$setting_key, $value]);
                }
            }
        }

        $pdo->commit();
        $message = "Settings and photos updated successfully!";
        
        // Log activity
        $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, description, created_at) VALUES (?, 'update_settings', 'Updated site dynamic settings & photos via Admin Central', NOW())");
        $stmt->execute([$user['id']]);
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollback();
        $error = "Error updating settings: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Central - FMWA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-width: 260px;
        }
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width); background: var(--primary-gradient); color: white; z-index: 1000; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .nav-item { margin: 5px 15px; }
        .nav-link { color: rgba(255, 255, 255, 0.8); padding: 12px 20px; border-radius: 10px; text-decoration: none; display: flex; align-items: center; }
        .nav-link:hover, .nav-link.active { background: rgba(255, 255, 255, 0.1); color: white; }
        .nav-link i { width: 20px; margin-right: 10px; }
        .main-content { margin-left: var(--sidebar-width); padding: 30px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .card-header { background: white; border-bottom: 1px solid #eee; padding: 20px 30px; border-radius: 15px 15px 0 0 !important; font-weight: 600; }
        .btn-primary { background: var(--primary-gradient); border: none; padding: 10px 25px; border-radius: 8px; }
        .form-label { font-weight: 500; color: #444; }
        .alert-git { background-color: #fff3cd; border-left: 5px solid #ffc107; color: #856404; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">FMWA Admin</h4>
            <small class="text-white-50">Content Management</small>
        </div>
        <nav class="sidebar-nav mt-4">
            <div class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></div>
            <div class="nav-item"><a href="posts.php" class="nav-link"><i class="fas fa-newspaper"></i> Posts & News</a></div>
            <div class="nav-item"><a href="admin_central.php" class="nav-link active"><i class="fas fa-bolt"></i> Admin Central</a></div>
            <div class="nav-item"><a href="pages.php" class="nav-link"><i class="fas fa-file-code"></i> Page Editor</a></div>
            <div class="nav-item"><a href="settings.php" class="nav-link"><i class="fas fa-cog"></i> Settings</a></div>
            <div class="nav-item"><a href="../index.php" class="nav-link" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a></div>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Admin Central <span class="badge bg-warning text-dark" style="font-size: 0.5em; vertical-align: middle;">ABSOLUTE POWER</span></h2>
            <div>
                <span class="text-muted">Logged in as: <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Git deployment warning -->
        <div class="card alert-git p-4 mb-4">
            <div class="d-flex">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">Important Note on "Reverting" Changes</h5>
                    <p class="mb-0">This site uses <strong>Git Auto-Deployment</strong>. If you use the "Page Editor" to edit files directly on the server, those changes WILL be overwritten the next time a developer pushes code. Use this <strong>Admin Central</strong> page to change content safely in the database, as database changes are never overwritten by Git.</p>
                </div>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Leadership Section -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-user-tie me-2"></i> Honourable Minister</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="minister_name" class="form-control" value="<?php echo htmlspecialchars(get_setting('minister_name')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="minister_title" class="form-control" value="<?php echo htmlspecialchars(get_setting('minister_title')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Minister Photo</label>
                                <?php $m_img = get_setting('minister_image'); ?>
                                <?php if ($m_img): ?>
                                    <div class="mb-2">
                                        <?php 
                                            $m_img_src = (strpos($m_img, 'http') === 0) ? $m_img : '../' . ltrim($m_img, '/');
                                        ?>
                                        <img src="<?php echo htmlspecialchars($m_img_src); ?>" alt="Minister" style="width: 100px; height: 120px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex gap-2 mb-2">
                                    <input type="file" name="minister_photo" class="form-control" accept="image/*">
                                    <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('minister_image', 'minister_preview')">
                                        <i class="fas fa-images"></i> Library
                                    </button>
                                </div>
                                <input type="text" name="minister_image" id="minister_image" class="form-control text-muted mb-1" style="font-size: 0.8em;" value="<?php echo htmlspecialchars($m_img); ?>">
                                <small class="text-muted">Upload new, pick from library, or paste a URL.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Short Biography</label>
                                <textarea name="minister_description" class="form-control" rows="4"><?php echo htmlspecialchars(get_setting('minister_description')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-user-shield me-2"></i> Permanent Secretary</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="perm_sec_name" class="form-control" value="<?php echo htmlspecialchars(get_setting('perm_sec_name')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="perm_sec_title" class="form-control" value="<?php echo htmlspecialchars(get_setting('perm_sec_title')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Perm Sec Photo</label>
                                <?php $ps_img = get_setting('perm_sec_image'); ?>
                                <?php if ($ps_img): ?>
                                    <div class="mb-2">
                                        <?php 
                                            $ps_img_src = (strpos($ps_img, 'http') === 0) ? $ps_img : '../' . ltrim($ps_img, '/');
                                        ?>
                                        <img src="<?php echo htmlspecialchars($ps_img_src); ?>" alt="Perm Sec" style="width: 100px; height: 120px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex gap-2 mb-2">
                                    <input type="file" name="perm_sec_photo" class="form-control" accept="image/*">
                                    <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('perm_sec_image', 'perm_sec_preview')">
                                        <i class="fas fa-images"></i> Library
                                    </button>
                                </div>
                                <input type="text" name="perm_sec_image" id="perm_sec_image" class="form-control text-muted mb-1" style="font-size: 0.8em;" value="<?php echo htmlspecialchars($ps_img); ?>">
                                <small class="text-muted">Upload new, pick from library, or paste a URL.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Short Biography</label>
                                <textarea name="perm_sec_description" class="form-control" rows="4"><?php echo htmlspecialchars(get_setting('perm_sec_description')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission & Vision -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-bullseye me-2"></i> Mandate, Vision & Mission</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Our Mandate</label>
                                <textarea name="our_mandate" class="form-control" rows="3"><?php echo htmlspecialchars(get_setting('our_mandate')); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Our Vision</label>
                                <textarea name="our_vision" class="form-control" rows="3"><?php echo htmlspecialchars(get_setting('our_vision')); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Our Mission</label>
                                <textarea name="our_mission" class="form-control" rows="3"><?php echo htmlspecialchars(get_setting('our_mission')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Controls -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-cogs me-2"></i> System Controls</div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="maintenance_mode" id="mMode" <?php echo get_setting('maintenance_mode') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="mMode">Maintenance Mode (Shows a notice on the homepage)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <button type="submit" name="save_settings" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i> Save All Dynamic Content
                </button>
            </div>

            <div class="row">
                <!-- Global Site Identity -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white"><i class="fas fa-globe me-2"></i> Global Site Identity</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Main Site Name</label>
                                    <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars(get_setting('site_name', 'Federal Ministry of Women Affairs')); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Site Meta Description</label>
                                    <input type="text" name="site_description" class="form-control" value="<?php echo htmlspecialchars(get_setting('site_description')); ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Site Logo</label>
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <?php $logo = get_setting('site_logo', 'images/2025_07_14_13_42_IMG_2808.PNG'); ?>
                                            <?php 
                                                $logo_src = (strpos($logo, 'http') === 0) ? $logo : '../' . ltrim($logo, '/');
                                            ?>
                                            <img src="<?php echo htmlspecialchars($logo_src); ?>" alt="Logo" style="height: 60px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                        </div>
                                        <div class="col">
                                            <div class="d-flex gap-2 mb-2">
                                                <input type="file" name="site_logo_file" class="form-control" accept="image/*">
                                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaPicker('site_logo', 'logo_preview')">
                                                    <i class="fas fa-images"></i> Library
                                                </button>
                                            </div>
                                            <input type="text" name="site_logo" id="site_logo" class="form-control text-muted" style="font-size: 0.8em;" value="<?php echo htmlspecialchars($logo); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Footer About Text</label>
                                    <textarea name="footer_about_text" class="form-control" rows="2"><?php echo htmlspecialchars(get_setting('footer_about_text')); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-address-book me-2"></i> Contact Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars(get_setting('contact_email', 'info@fmwa.gov.ng')); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars(get_setting('contact_phone', '+234-9-461-0000')); ?>">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Office Address</label>
                                    <textarea name="contact_address" class="form-control" rows="2"><?php echo htmlspecialchars(get_setting('contact_address')); ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Working Hours</label>
                                    <input type="text" name="contact_hours" class="form-control" value="<?php echo htmlspecialchars(get_setting('contact_hours', 'Mon - Fri: 8:00 AM - 4:00 PM')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-share-alt me-2"></i> Social Media Links</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-facebook text-primary me-2"></i> Facebook</label>
                                <input type="url" name="social_facebook" class="form-control" value="<?php echo htmlspecialchars(get_setting('social_facebook')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-twitter text-info me-2"></i> Twitter / X</label>
                                <input type="url" name="social_twitter" class="form-control" value="<?php echo htmlspecialchars(get_setting('social_twitter')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-instagram text-danger me-2"></i> Instagram</label>
                                <input type="url" name="social_instagram" class="form-control" value="<?php echo htmlspecialchars(get_setting('social_instagram')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-youtube text-danger me-2"></i> YouTube</label>
                                <input type="url" name="social_youtube" class="form-control" value="<?php echo htmlspecialchars(get_setting('social_youtube')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Media Picker Modal -->
    <div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-images me-2"></i>Select Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="mediaSearch" class="form-control" placeholder="Search media files...">
                    </div>
                    <div id="mediaPickerGrid" class="row g-3">
                        <!-- Media items will be loaded here -->
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Loading your media library...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .picker-item { cursor: pointer; transition: all 0.2s; border: 2px solid transparent; border-radius: 8px; overflow: hidden; height: 100%; position: relative; }
        .picker-item:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #667eea; }
        .picker-img { width: 100%; height: 150px; object-fit: cover; }
        .picker-info { padding: 8px; background: #fff; font-size: 0.75rem; border-top: 1px solid #eee; }
        .picker-badge { position: absolute; top: 5px; right: 5px; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let targetInputId = '';
        let targetPreviewId = '';
        let mediaModal;
        let allMedia = [];

        document.addEventListener('DOMContentLoaded', function() {
            mediaModal = new bootstrap.Modal(document.getElementById('mediaPickerModal'));
            
            document.getElementById('mediaSearch').addEventListener('input', function(e) {
                renderMedia(e.target.value);
            });
        });

        function openMediaPicker(inputId, previewId) {
            targetInputId = inputId;
            targetPreviewId = previewId;
            mediaModal.show();
            fetchMedia();
        }

        async function fetchMedia() {
            try {
                const response = await fetch('api/media.php');
                const result = await response.json();
                if (result.success) {
                    allMedia = Array.isArray(result.media) ? result.media : (Array.isArray(result.data) ? result.data : []);
                    renderMedia();
                } else {
                    document.getElementById('mediaPickerGrid').innerHTML = '<div class="col-12 text-center text-danger">Failed to load media: ' + result.message + '</div>';
                }
            } catch (err) {
                document.getElementById('mediaPickerGrid').innerHTML = '<div class="col-12 text-center text-danger">Error connecting to media API.</div>';
            }
        }

        function renderMedia(search = '') {
            const grid = document.getElementById('mediaPickerGrid');
            const filtered = allMedia.filter(m => 
                (m.title || m.filename || '').toLowerCase().includes(search.toLowerCase())
            );

            if (filtered.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5">No matching media found.</div>';
                return;
            }

            let html = '';
            filtered.forEach(m => {
                const isImg = m.mime_type && m.mime_type.startsWith('image/');
                const isVid = m.mime_type && m.mime_type.startsWith('video/');
                const thumb = isImg ? `../${m.file_url}` : (isVid ? 'https://via.placeholder.com/150x100?text=VIDEO' : 'https://via.placeholder.com/150x100?text=FILE');
                
                html += `
                    <div class="col-md-3 col-6">
                        <div class="picker-item card" onclick="selectMedia('${m.file_url}')">
                            <img src="${thumb}" class="picker-img">
                            <div class="picker-info">
                                <div class="text-truncate fw-bold">${m.title || m.filename}</div>
                                <div class="text-muted">${m.mime_type}</div>
                            </div>
                            <span class="badge bg-primary picker-badge">${isImg ? 'IMG' : (isVid ? 'VID' : 'FILE')}</span>
                        </div>
                    </div>
                `;
            });
            grid.innerHTML = html;
        }

        function selectMedia(url) {
            document.getElementById(targetInputId).value = url;
            // Best effort preview update
            const previews = document.querySelectorAll('img');
            for(let img of previews) {
                if (img.src.includes(url.split('/').pop())) {
                     // likely found the preview, but better to refresh the whole page or use IDs
                }
            }
            mediaModal.hide();
            alert("Media selected! Don't forget to click 'Save All Dynamic Content' to apply changes.");
        }
    </script>
</body>
</html>
