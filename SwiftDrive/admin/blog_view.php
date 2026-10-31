<?php
include_once("header.php");
require_once 'db/db.php';

// Check Admin Login
if ($_SESSION["role"] !== "ADMIN" && $_SESSION["role"] !== "STAFF") {
    header("location: ../index.php?error=you are not admin");
    exit();
}

if (!isset($_GET['id'])) {
    header("location: blog.php");
    exit();
}

$blog_id = (int)$_GET['id'];

// Get Blog Details
$sql = "SELECT b.*, u.email AS author_email FROM blogs b JOIN users u ON b.user_id = u.user_id WHERE b.id = ?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $blog_id);
mysqli_stmt_execute($stmt);
$blog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$blog) {
    header("location: blog.php");
    exit();
}

$author_name = explode('@', $blog['author_email'])[0];
?>

<!-- Top Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="blog.php" class="btn btn-light border fw-bold rounded-pill"><i class="fa-solid fa-arrow-left me-2"></i>Back to List</a>

    <div>
        <?php if ($blog['status'] === 'Pending'): ?>
            <a href="blog.php?approve=<?php echo (int)$blog['id']; ?>" class="btn btn-success fw-bold rounded-pill px-4 me-2" onclick="return confirm('Are you sure you want to approve this blog?');"><i class="fa-solid fa-check me-2"></i>Approve Blog</a>
        <?php endif; ?>

        <a href="blog.php?delete=<?php echo (int)$blog['id']; ?>" class="btn btn-danger fw-bold rounded-pill px-4" onclick="return confirm('Are you sure you want to delete this blog?');"><i class="fa-solid fa-trash me-2"></i>Delete</a>
    </div>
</div>

<!-- Blog Review Card -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">

        <!-- Blog Header -->
        <div class="mb-4">
            <!-- Category -->
            <span class="badge bg-secondary px-3 py-2 rounded-pill mb-2"><?php echo htmlspecialchars($blog['category']); ?></span>

            <!-- Status -->
            <span class="badge <?php echo $blog['status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-success'; ?> px-3 py-2 rounded-pill mb-2 ms-2">Status: <?php echo htmlspecialchars($blog['status']); ?></span>

            <!-- Title -->
            <h2 class="fw-bold text-dark mt-2"><?php echo htmlspecialchars($blog['title']); ?></h2>

            <!-- Author + Date -->
            <div class="text-muted fw-bold small mt-3">
                <span class="me-4"><i class="fa-solid fa-user me-2"></i>Author: <?php echo htmlspecialchars($blog['author_email']); ?></span>
                <span><i class="fa-regular fa-calendar me-2"></i>Submitted on: <?php echo date('M d, Y h:i A', strtotime($blog['created_at'])); ?></span>
            </div>
        </div>

        <!-- Blog Cover Image -->
        <img src="../<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="img-fluid w-100 rounded-4 mb-4" style="height:350px;object-fit:cover;" onerror="this.src='../assets/images/default.jpg';">

        <!-- Excerpt -->
        <div class="bg-light p-4 rounded-3 mb-4 border">
            <h6 class="fw-bold text-uppercase text-muted small mb-2">Excerpt (Summary)</h6>
            <p class="mb-0 fw-semibold text-dark"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
        </div>

        <!-- Full Content -->
        <div>
            <h6 class="fw-bold text-uppercase text-muted small mb-3">Full Content</h6>
            <div style="font-size:1.1rem;line-height:1.8;color:#334155;">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
        </div>

    </div>
</div>