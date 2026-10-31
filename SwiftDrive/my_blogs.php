<?php
require_once "actions/myBlog_action.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blogs | SwiftDrive</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="customer-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="brand-logo"><img src="assets/logo.png" alt="SwiftDrive Logo" style="height: 40px; width: auto; margin-right: 8px;">SwiftDrive</a>
            <a href="index.php" class="btn btn-outline-primary fw-bold rounded-pill px-4"><i class="fa-solid fa-house me-2"></i>Home</a>
        </div>
    </nav>

    <div class="container mb-5 mt-4">

        <!-- Mobile Menu Button -->
        <div class="d-lg-none mb-3">
            <button class="btn btn-dark w-100 fw-bold rounded-pill py-2 shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#customerSidebar">
                <i class="fa-solid fa-bars me-2"></i> Open Dashboard Menu
            </button>
        </div>

        <div class="row g-4">
            
            <!-- Customer Navigation Sidebar -->
            <div class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start border-0 bg-transparent" id="customerSidebar" tabindex="-1">
                    <div class="offcanvas-header bg-white border-bottom rounded-top-4 d-lg-none mt-2 mx-2">
                        <h5 class="offcanvas-title fw-bold"><i class="fa-solid fa-car-side text-primary me-2"></i>Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#customerSidebar"></button>
                    </div>
                    <div class="offcanvas-body p-lg-0 p-2">
                        <div class="premium-card w-100">
                            <div class="profile-widget">
                                <div class="avatar-circle"><?php echo strtoupper(substr($customer['full_name'], 0, 1)); ?></div>
                                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($customer['full_name']); ?></h5>
                                <p class="text-muted small mb-0"><i class="fa-solid fa-envelope me-1"></i> <?php echo htmlspecialchars($customer['email']); ?></p>
                            </div>
                            <ul class="menu-list">
                                <li><a href="dashboard.php" class="menu-link"><i class="fa-solid fa-table-columns"></i> Dashboard</a></li>
                                <li><a href="my_bookings.php" class="menu-link"><i class="fa-solid fa-calendar-check"></i> My Bookings</a></li>
                                <li><a href="my_reviews.php" class="menu-link"><i class="fa-solid fa-star"></i> My Reviews</a></li>
                                <li><a href="my_blogs.php" class="menu-link active"><i class="fa-solid fa-pen-nib"></i> My Blogs</a></li>
                                <li><a href="my_profile.php" class="menu-link"><i class="fa-solid fa-user"></i> My Profile</a></li>
                                <li><a href="actions/logout.php" class="menu-link text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                
                <?php if(isset($_GET['msg'])): ?>
                    <div class="alert alert-success fw-bold rounded-pill shadow-sm mb-4">
                        <i class="fa-solid fa-circle-check me-2"></i> 
                        <?php 
                            if($_GET['msg'] == 'added') echo "Your blog has been submitted and is pending approval!";
                            if($_GET['msg'] == 'updated') echo "Your blog has been updated successfully!";
                            if($_GET['msg'] == 'deleted') echo "Blog post removed successfully!";
                        ?>
                    </div>
                <?php endif; ?>

                <div class="premium-card p-4">
                    
                    <!-- 🔴 1. LIST BLOGS VIEW -->
                    <?php if($action == 'list'): ?>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-newspaper text-primary me-2"></i>My Travel Stories</h4>
                            <a href="my_blogs.php?action=add" class="btn btn-primary fw-bold rounded-pill px-4">
                                <i class="fa-solid fa-plus me-2"></i> Write New
                            </a>
                        </div>

                        <?php if(mysqli_num_rows($blogs) == 0): ?>
                            <div class="text-center py-5">
                                <div class="text-muted opacity-50 mb-3"><i class="fa-solid fa-pen-to-square fa-3x"></i></div>
                                <h5 class="fw-bold text-dark">No Blogs Found</h5>
                                <p class="text-muted mb-4">You haven't shared any travel stories yet.</p>
                                <a href="my_blogs.php?action=add" class="btn btn-outline-primary px-4 py-2 fw-bold rounded-pill">Start Writing Now</a>
                            </div>
                        <?php else: ?>
                            <?php while($blog = mysqli_fetch_assoc($blogs)): ?>
                                <div class="my-blog-card">
                                    <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" class="b-img" alt="Blog Image" onerror="this.src='assets/images/default.jpg';">
                                    <div class="b-content">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($blog['category']); ?></span>
                                                <?php if($blog['status'] == 'Pending'): ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-clock me-1"></i> Pending Approval</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> Published</span>
                                                <?php endif; ?>
                                            </div>
                                            <h4 class="fw-bold mb-2 text-dark"><?php echo htmlspecialchars($blog['title']); ?></h4>
                                            <p class="text-muted small mb-0"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                        </div>
                                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                            <a href="my_blogs.php?action=edit&id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-primary fw-bold px-3 rounded-pill"><i class="fa-solid fa-pen me-1"></i> Edit</a>
                                            <a href="actions/delete_blog.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-danger fw-bold px-3 rounded-pill" onclick="return confirm('Delete this blog permanently?');"><i class="fa-solid fa-trash me-1"></i> Delete</a>
                                            
                                            <?php if($blog['status'] == 'Approved'): ?>
                                                <a href="blog_view.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-dark fw-bold px-3 rounded-pill ms-auto">View Live</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>

                    <!-- 🔴 2. ADD BLOG VIEW -->
                    <?php elseif($action == 'add'): ?>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-pen-nib text-primary me-2"></i>Write a New Story</h4>
                                <p class="text-muted mb-0 small">Share your experiences with the SwiftDrive community.</p>
                            </div>
                            <a href="my_blogs.php?action=list" class="btn btn-light border fw-bold rounded-pill px-4">Cancel</a>
                        </div>

                        <form action="actions/submit_blog.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">Blog Title</label>
                                <input type="text" name="title" class="form-control custom-input" required>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Category</label>
                                    <select name="category" class="form-select custom-input" required>
                                        <option value="Road Trip">Road Trip</option>
                                        <option value="Travel Tips">Travel Tips</option>
                                        <option value="Vehicle Review">Vehicle Review</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Cover Image</label>
                                    <input type="file" name="blog_image" class="form-control custom-input" accept="image/*" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">Short Excerpt</label>
                                <textarea name="excerpt" class="form-control custom-input" rows="2" placeholder="Brief summary of your post..." required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">Full Content</label>
                                <textarea name="content" class="form-control custom-input" rows="8" placeholder="Write your full story here..." required></textarea>
                            </div>
                            <button type="submit" name="submit_blog" class="btn btn-primary py-3 w-100 fw-bold fs-6 rounded-pill">
                                Submit for Approval <i class="fa-solid fa-paper-plane ms-2"></i>
                            </button>
                        </form>

                    <!-- 🔴 3. EDIT BLOG VIEW -->
                    <?php elseif($action == 'edit' && $edit_blog): ?>

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Blog Post</h4>
                                <p class="text-muted mb-0 small">Make changes to your existing story.</p>
                            </div>
                            <a href="my_blogs.php?action=list" class="btn btn-light border fw-bold rounded-pill px-4">Cancel</a>
                        </div>

                        <form action="actions/update_blog.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="blog_id" value="<?php echo $edit_blog['id']; ?>">
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">Blog Title</label>
                                <input type="text" name="title" class="form-control custom-input" value="<?php echo htmlspecialchars($edit_blog['title']); ?>" required>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Category</label>
                                    <select name="category" class="form-select custom-input" required>
                                        <option value="Road Trip" <?php if($edit_blog['category'] == 'Road Trip') echo 'selected'; ?>>Road Trip</option>
                                        <option value="Travel Tips" <?php if($edit_blog['category'] == 'Travel Tips') echo 'selected'; ?>>Travel Tips</option>
                                        <option value="Vehicle Review" <?php if($edit_blog['category'] == 'Vehicle Review') echo 'selected'; ?>>Vehicle Review</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Update Image (Optional)</label>
                                    <input type="file" name="blog_image" class="form-control custom-input" accept="image/*">
                                    <small class="text-muted mt-1 d-block">Leave blank to keep the current image.</small>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">Short Excerpt</label>
                                <textarea name="excerpt" class="form-control custom-input" rows="2" required><?php echo htmlspecialchars($edit_blog['excerpt']); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">Full Content</label>
                                <textarea name="content" class="form-control custom-input" rows="8" required><?php echo htmlspecialchars($edit_blog['content']); ?></textarea>
                            </div>
                            <button type="submit" name="update_blog" class="btn btn-success py-3 w-100 fw-bold fs-6 rounded-pill">
                                Save Changes <i class="fa-solid fa-check ms-2"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>