<?php
// Include database connection
require_once 'db/db.php';

// Validate blog ID from URL query parameters
if (!isset($_GET['id'])) { header("location: blog.php"); exit(); }
$blog_id = (int)$_GET['id'];

// Query approved blog post details with author information
$sql = "SELECT b.*, u.email AS author_email FROM blogs b JOIN users u ON b.user_id = u.user_id WHERE b.id = ? AND b.status = 'Approved'";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $blog_id);
mysqli_stmt_execute($stmt);
$blog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Redirect if blog post does not exist
if (!$blog) { header("location: blog.php"); exit(); }

// Extract author display name from email
$author_name = ucfirst(explode('@', $blog['author_email'])[0]);

// Include navigation header component
include_once "header.php";
?>

<main>
    <div class="blog-view">
        
        <?php if (!empty($blog['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" 
                 class="blog-cover" 
                 alt="<?php echo htmlspecialchars($blog['title']); ?>"
                 onerror="this.src='assets/images/default.jpg';">
        <?php endif; ?>
        
        <div class="blog-content">
            
            <a href="blog.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Blogs
            </a>
            
            <?php if (!empty($blog['category'])): ?>
                <div><span class="category-badge"><?php echo htmlspecialchars($blog['category']); ?></span></div>
            <?php endif; ?>
            
            <h1 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
            
            <div class="blog-meta">
                <span><i class="fa-solid fa-pen-nib"></i> By <?php echo htmlspecialchars($author_name); ?></span>
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                <?php if (!empty($blog['read_time'])): ?>
                    <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($blog['read_time']); ?> Min Read</span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($blog['excerpt'])): ?>
                <div class="blog-excerpt">
                    <?php echo htmlspecialchars($blog['excerpt']); ?>
                </div>
            <?php endif; ?>
            
            <div class="blog-text">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
            
        </div>
    </div>
</main>

<?php include_once "footer.php"; ?>