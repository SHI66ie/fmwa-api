<?php
/**
 * Video File Checker
 * Interactive tool to check and fix missing video files
 */

require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->requireLogin();

// Handle actions
$action = $_GET['action'] ?? 'check';
$videoId = $_GET['id'] ?? null;

if ($action === 'delete' && $videoId) {
    // Delete video record for missing file
    $stmt = $pdo->prepare("DELETE FROM media WHERE id = ? AND file_type = 'video'");
    $stmt->execute([$videoId]);
    
    header('Location: video-file-checker.php?deleted=1');
    exit;
}

// Get all videos
$stmt = $pdo->query("SELECT * FROM media WHERE file_type = 'video' ORDER BY created_at DESC");
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalVideos = count($videos);
$missingVideos = [];
$foundVideos = [];

foreach ($videos as $video) {
    $videoPath = '../' . $video['file_url'];
    $fileExists = file_exists($videoPath);
    
    if ($fileExists) {
        $foundVideos[] = $video;
    } else {
        $missingVideos[] = $video;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video File Checker - FMWA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #1a1a1a; color: #fff; }
        .navbar { background: #2d2d2d; border-bottom: 1px solid #444; }
        .card { background: #2d2d2d; border: 1px solid #444; }
        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .badge { font-size: 0.8em; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="videos.php">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Videos
            </a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="fas fa-video me-2"></i>
                    Video File Checker
                </h2>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check me-2"></i>
                        Video record deleted successfully!
                    </div>
                <?php endif; ?>

                <!-- Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $totalVideos; ?></h3>
                                <p class="mb-0">Total Videos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo count($foundVideos); ?></h3>
                                <p class="mb-0">Files Found</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-danger"><?php echo count($missingVideos); ?></h3>
                                <p class="mb-0">Files Missing</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Missing Files -->
                <?php if (!empty($missingVideos)): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Missing Video Files
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Filename</th>
                                            <th>Expected Path</th>
                                            <th>Size</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($missingVideos as $video): ?>
                                            <tr>
                                                <td><?php echo $video['id']; ?></td>
                                                <td><?php echo htmlspecialchars($video['original_filename']); ?></td>
                                                <td>
                                                    <code><?php echo htmlspecialchars($video['file_url']); ?></code>
                                                    <br><small class="text-muted">Server: <?php echo htmlspecialchars('../' . $video['file_url']); ?></small>
                                                </td>
                                                <td><?php echo number_format($video['file_size'] / 1024 / 1024, 2); ?> MB</td>
                                                <td><?php echo date('M j, Y', strtotime($video['created_at'])); ?></td>
                                                <td>
                                                    <a href="video-file-checker.php?action=delete&id=<?php echo $video['id']; ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Delete this video record? The file is missing so this will only remove the database entry.')">
                                                        <i class="fas fa-trash me-1"></i>Delete Record
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Recommended Actions:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Delete the database records for missing files (using Delete Record button)</li>
                                    <li>Re-upload the missing video files through the Media Library</li>
                                    <li>Check if files were moved to a different directory</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Found Files -->
                <?php if (!empty($foundVideos)): ?>
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check me-2"></i>
                                Valid Video Files
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Filename</th>
                                            <th>Path</th>
                                            <th>Size</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($foundVideos as $video): ?>
                                            <tr>
                                                <td><?php echo $video['id']; ?></td>
                                                <td><?php echo htmlspecialchars($video['original_filename']); ?></td>
                                                <td><code><?php echo htmlspecialchars($video['file_url']); ?></code></td>
                                                <td><?php echo number_format($video['file_size'] / 1024 / 1024, 2); ?> MB</td>
                                                <td><?php echo date('M j, Y', strtotime($video['created_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- No Videos -->
                <?php if (empty($videos)): ?>
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-video fa-4x text-muted mb-3"></i>
                            <h5>No Videos Found</h5>
                            <p class="text-muted">There are no videos in the database to check.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
