<?php
require_once 'db/db.php';
// 1. Approve Blog Post
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE blogs SET status = 'Approved' WHERE id = $id");
    header("location: blog.php?success=approved");
    exit();
}

// 2. Delete / Reject Blog Post
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM blogs WHERE id = $id");
    header("location: blog.php?success=deleted");
    exit();
}

include 'header.php';
if ($_SESSION["role"] !== "ADMIN" && $_SESSION["role"] !== "STAFF") {
    header("location: ../login.php");
    exit();
}


// Get blogs with author names using JOIN
$blogs_sql = "SELECT b.*, u.email AS author_name FROM blogs b JOIN users u ON b.user_id = u.user_id ORDER BY b.created_at DESC";
$blogs = mysqli_query($conn, $blogs_sql);
?>


<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Blog Post Approvals</h4>
            <p class="text-muted mb-0">Review, approve or remove customer blog posts.</p>
        </div>
    </div>

    <!-- Success Message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] === 'approved'): ?>
                <i class="fa-solid fa-circle-check me-2"></i>Blog post approved successfully.
            <?php elseif ($_GET['success'] === 'deleted'): ?>
                <i class="fa-solid fa-circle-check me-2"></i>Blog post deleted successfully.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Blog Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title & Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($blogs && mysqli_num_rows($blogs) > 0): ?>
                            <?php while ($blog = mysqli_fetch_assoc($blogs)): ?>
                                <tr>
                                    <td>
                                        <img src="../<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-img-thumb" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" onerror="this.src='../assets/images/default.jpg';">
                                    </td>

                                    <td>
                                        <strong class="d-block text-dark"><?php echo htmlspecialchars($blog['title']); ?></strong>
                                        <span class="small text-muted">By: <?php echo htmlspecialchars($blog['author_name']); ?></span>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($blog['category']); ?></span>
                                    </td>

                                    <td>
                                        <?php if ($blog['status'] === 'Pending'): ?>
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Approved</span>
                                        <?php endif; ?>
                                    </td>

                                    

                                    <td class="text-end">
                                        <a href="blog_view.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-info fw-bold rounded-pill me-1"><i class="fa-solid fa-eye"></i> View</a>
                                        <?php if ($blog['status'] === 'Pending'): ?>
                                            <a href="blog.php?approve=<?php echo (int)$blog['id']; ?>" class="btn btn-sm btn-success fw-bold" onclick="return confirm('Are you sure you want to approve this blog?');"><i class="fa-solid fa-check me-1"></i>Approve</a>
                                        <?php endif; ?>

                                        <a href="blog.php?delete=<?php echo (int)$blog['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this blog?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5"><i class="fa-solid fa-newspaper fa-2x mb-3 d-block"></i>No blog posts found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    const closeBtn = document.getElementById("closeSidebarBtn");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function() {
            sidebar.classList.add("show");
            overlay.classList.add("show");
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", function() {
            sidebar.classList.remove("show");
            overlay.classList.remove("show");
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function() {
            sidebar.classList.remove("show");
            overlay.classList.remove("show");
        });
    }
});
</script>

</body>
</html>