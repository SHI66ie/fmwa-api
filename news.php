<?php
require_once __DIR__ . '/includes/Post.php';

$postModel = new Post();
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$isDetailView = $slug !== '';
$currentPost = null;
$recentPosts = [];

try {
    if ($isDetailView) {
        $currentPost = $postModel->getBySlug($slug);
        if ($currentPost && isset($currentPost->id)) {
            $postModel->incrementViews($currentPost->id);
        }
        // Recent posts sidebar/list
        $recentPosts = $postModel->getRecent(5);
    } else {
        // List view: show up to 20 latest posts
        $recentPosts = $postModel->getPublished(20, 0);
    }
} catch (Exception $e) {
    // On any DB error, fail gracefully
    $recentPosts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News &amp; Updates - Federal Ministry of Women Affairs</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/2025_07_14_13_42_IMG_2808.PNG">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/department-navigation.css">
    <link rel="stylesheet" href="css/logo-position-fix.css?v=1.1">
    <link rel="stylesheet" href="css/welcome-banner.css">
    <link rel="stylesheet" href="css/visitor-counter.css">
    
    <!-- Include components -->
    <script src="js/include-components.js" defer></script>
    
    <style>
        body {
            padding-top: 120px;
        }

        .page-header {
            padding: 60px 0 30px 0;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .page-header h1 {
            color: #013a04;
            font-weight: 700;
        }

        .news-list-section {
            padding: 40px 0 60px 0;
        }

        .news-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .news-card img {
            max-height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .news-card-body {
            padding: 20px 24px;
        }

        .news-meta {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .news-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #013a04;
            margin-bottom: 8px;
        }

        .news-excerpt {
            font-size: 0.95rem;
            color: #555;
        }

        .news-read-more {
            margin-top: 12px;
        }

        .news-read-more a {
            text-decoration: none;
        }

        .news-read-more a:hover {
            text-decoration: underline;
        }

        .news-sidebar-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #013a04;
        }

        .news-sidebar-list li {
            margin-bottom: 10px;
        }

        .news-sidebar-list a {
            text-decoration: none;
            color: #333;
        }

        .news-sidebar-list a:hover {
            text-decoration: underline;
            color: #013a04;
        }

        .news-detail-title {
            font-weight: 700;
            color: #013a04;
            margin-bottom: 10px;
        }

        .news-detail-meta {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .news-detail-content {
            font-size: 1rem;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation will be inserted here by js/include-components.js -->

    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">News &amp; Updates</h1>
                    <p class="text-muted mb-0">Latest information, press releases, and updates from the Federal Ministry of Women Affairs.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="index.php#about-section" class="btn btn-outline-success btn-sm me-2">About the Ministry</a>
                    <a href="departments/women-development.php" class="btn btn-success btn-sm">Our Programs</a>
                </div>
            </div>
        </div>
    </header>

    <section class="news-list-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <?php if ($isDetailView && $currentPost): ?>
                        <?php
                            $image = !empty($currentPost->featured_image) ? $currentPost->featured_image : 'latest-news/IMG-20250731-WA0019.jpg';
                            $dateSource = !empty($currentPost->published_at) ? $currentPost->published_at : ($currentPost->created_at ?? '');
                            $dateFormatted = $dateSource ? date('F j, Y', strtotime($dateSource)) : '';
                        ?>
                        <article class="news-card">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($currentPost->title); ?>">
                            <div class="news-card-body">
                                <h2 class="news-detail-title"><?php echo htmlspecialchars($currentPost->title); ?></h2>
                                <?php if ($dateFormatted): ?>
                                    <div class="news-detail-meta">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <?php echo htmlspecialchars($dateFormatted); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="news-detail-content">
                                    <?php echo $currentPost->content; ?>
                                </div>
                                <div class="mt-4">
                                    <a href="news.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Back to News List
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php else: ?>
                        <?php if (!empty($recentPosts)): ?>
                            <?php foreach ($recentPosts as $post): ?>
                                <?php
                                    $image = !empty($post->featured_image) ? $post->featured_image : 'latest-news/IMG-20250731-WA0019.jpg';
                                    $dateSource = !empty($post->published_at) ? $post->published_at : ($post->created_at ?? '');
                                    $dateFormatted = $dateSource ? date('F j, Y', strtotime($dateSource)) : '';
                                    $rawText = strip_tags($post->excerpt ?: ($post->content ?? ''));
                                    $rawText = trim($rawText);
                                    $excerpt = strlen($rawText) > 160 ? substr($rawText, 0, 160) . '...' : $rawText;
                                ?>
                                <article class="news-card">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($post->title); ?>">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="news-card-body">
                                                <?php if ($dateFormatted): ?>
                                                    <div class="news-meta">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        <?php echo htmlspecialchars($dateFormatted); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <h3 class="news-card-title"><?php echo htmlspecialchars($post->title); ?></h3>
                                                <?php if ($excerpt): ?>
                                                    <p class="news-excerpt"><?php echo htmlspecialchars($excerpt); ?></p>
                                                <?php endif; ?>
                                                <div class="news-read-more">
                                                    <?php if (!empty($post->slug)): ?>
                                                        <a href="news.php?slug=<?php echo urlencode($post->slug); ?>" class="text-success fw-semibold">
                                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                There are no published news items yet. Please check back later.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <div class="mb-4">
                        <h5 class="news-sidebar-title">Recent News</h5>
                        <ul class="list-unstyled news-sidebar-list">
                            <?php if (!empty($recentPosts)): ?>
                                <?php foreach ($recentPosts as $post): ?>
                                    <li>
                                        <?php if (!empty($post->slug)): ?>
                                            <a href="news.php?slug=<?php echo urlencode($post->slug); ?>">
                                                <?php echo htmlspecialchars($post->title); ?>
                                            </a>
                                        <?php else: ?>
                                            <span><?php echo htmlspecialchars($post->title); ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="text-muted">No news items available.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="bg-light p-3 rounded">
                        <h6 class="mb-2">Stay Informed</h6>
                        <p class="small text-muted mb-2">Visit this page regularly for official updates, press releases, and announcements.</p>
                        <a href="index.php" class="btn btn-outline-success btn-sm">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php /* Reuse the same footer styling as other pages via header/footer CSS */ ?>
    <footer class="footer bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">Federal Ministry of Women Affairs</h5>
                    <p class="text-light">Empowering women and promoting gender equality across Nigeria through comprehensive policies, programs, and initiatives that advance the welfare of women and children.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="mandate.php" class="text-muted text-decoration-none">Our Mandate</a></li>
                        <li class="mb-2"><a href="organogram.php" class="text-muted text-decoration-none">Organogram</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">News &amp; Updates</h5>
                    <p class="text-muted small">Official information and announcements from the Federal Ministry of Women Affairs.</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i>Federal Secretariat Complex, Shehu Shagari Way, Abuja</p>
                    <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i>info@womenaffairs.gov.ng</p>
                    <p class="text-muted mb-0"><i class="fas fa-clock me-2"></i>Mon - Fri: 8:00 AM - 4:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom border-top border-secondary pt-4 mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> Federal Ministry of Women Affairs. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
