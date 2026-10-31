<?php
include_once "header.php";
require_once 'db/db.php';
$sql = "SELECT b.*, u.email AS author_email 
        FROM blogs b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.status = 'Approved' 
        ORDER BY b.created_at DESC";
$result = mysqli_query($conn, $sql);
$blog_count = mysqli_num_rows($result);
?>

<main class="pb-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <h1 class="h2 fw-bold text-dark mb-1">SwiftDrive Travel Blog</h1>
                <p class="text-muted small mb-0">Discover the best travel experiences and tips from our community.</p>
            </div>
            <div>
                <span class="badge bg-dark text-white px-3 py-2 rounded-pill fw-bold">
                    <?php echo $blog_count; ?> Articles Found
                </span>
            </div>
        </div>
        <div class="row g-4">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($blog = mysqli_fetch_assoc($result)):
                    // Extract username part of author email to display as author name
                    $author_name = ucfirst(explode('@', $blog['author_email'])[0]);
                ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="blog-card">
                            <div class="blog-img-wrapper">
                                <span class="category-badge"><?php echo htmlspecialchars($blog['category']); ?></span>
                                <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" alt="Blog Image" onerror="this.src='assets/images/default.jpg';">
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between text-muted small fw-bold mb-3">
                                    <span><i class="fa-solid fa-user text-primary me-1"></i> <?php echo $author_name; ?></span>
                                    <span><i class="fa-regular fa-calendar text-primary me-1"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                                </div>
                                <h4 class="fw-bold mb-3 text-dark" style="line-height: 1.4;">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </h4>
                                <p class="text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo htmlspecialchars($blog['excerpt']); ?>
                                </p>

                                <!-- Read More Button -->
                                <div class="mt-auto pt-3 border-top">
                                    <a href="blog_view.php?id=<?php echo $blog['id']; ?>" class="fw-bold text-decoration-none text-primary d-flex align-items-center justify-content-between">
                                        <span>Read Full Guide</span>
                                        <i class="fa-solid fa-arrow-right bg-primary bg-opacity-10 p-2 rounded-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fa-solid fa-newspaper fa-3x text-muted opacity-50 mb-3"></i>
                    <h4 class="fw-bold text-dark">No Articles Yet</h4>
                    <p class="text-muted">Our community hasn't published any stories yet. Check back soon!</p>
                    <a href="dashboard.php" class="btn btn-primary rounded-pill px-4 fw-bold">Write a Blog</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include_once "footer.php"; ?>