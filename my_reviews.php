<?php require_once 'actions/reviews_actions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews | SwiftDrive</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="customer-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="brand-logo"><i class="fa-solid fa-car-side text-primary me-2"></i>SwiftDrive</a>
            <a href="index.php" class="btn btn-outline-primary fw-bold rounded-pill px-4"><i class="fa-solid fa-house me-2"></i>Home</a>
        </div>
    </nav>

    <div class="container mb-5">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'reviewed'): ?>
            <div class="alert alert-success rounded-pill shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>Thank you! Your review has been submitted successfully.</div>
        <?php endif; ?>

        <!-- Mobile Menu Button -->
        <div class="d-lg-none mb-3">
            <button class="btn btn-dark w-100 fw-bold rounded-pill py-2 shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#customerSidebar">
                <i class="fa-solid fa-bars me-2"></i> Open Menu
            </button>
        </div>

        <div class="row g-4">
            <!-- Sidebar -->
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
                                <li><a href="my_reviews.php" class="menu-link active"><i class="fa-solid fa-star"></i> My Reviews</a></li>
                                <li><a href="my_blogs.php?" class="menu-link"><i class="fa-solid fa-pen-nib"></i> My Blogs</a></li>
                                <li><a href="my_profile.php" class="menu-link"><i class="fa-solid fa-user"></i> My Profile</a></li>
                                <li><a href="actions/logout.php" class="menu-link text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <h3 class="fw-bold text-dark mb-4">My Reviews & Feedback</h3>

                <!-- 1. Pending Reviews -->
                <div class="premium-card-rating p-4 mb-4">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-comment-dots text-primary me-2"></i>Waiting for your review</h5>

                    <?php if (mysqli_num_rows($pending_reviews) > 0): ?>
                        <div class="row g-3">
                            <?php while ($pr = mysqli_fetch_assoc($pending_reviews)): ?>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo htmlspecialchars($pr['image']); ?>" class="vehicle-img" onerror="this.src='assets/images/default-car.jpg'">
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($pr['vehicle_name']); ?></div>
                                                <small class="text-muted">Trip Ended: <?php echo date('M d, Y', strtotime($pr['return_date'])); ?></small>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#reviewModal<?php echo $pr['booking_id']; ?>">
                                            Rate It
                                        </button>
                                    </div>
                                </div>

                                <!-- Review Modal -->
                                <div class="modal fade" id="reviewModal<?php echo $pr['booking_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                            <div class="modal-header bg-dark text-white p-4" style="border-radius: 15px 15px 0 0;">
                                                <h5 class="modal-title fw-bold"><i class="fa-solid fa-star text-warning me-2"></i>Rate your trip</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="my_reviews.php" method="POST">
                                                <div class="modal-body p-4 text-center">
                                                    <input type="hidden" name="booking_id" value="<?php echo $pr['booking_id']; ?>">
                                                    <input type="hidden" name="vehicle_id" value="<?php echo $pr['vehicle_id']; ?>">

                                                    <h6 class="fw-bold mb-3">How was your experience with <?php echo htmlspecialchars($pr['vehicle_name']); ?>?</h6>

                                                    <div class="mb-4 text-start">
                                                        <label class="form-label small fw-bold text-muted text-uppercase">Select Rating</label>
                                                        <select name="rating" class="form-select form-select-lg bg-light border-0" style="border-radius:10px;" required>
                                                            <option value="5">⭐⭐⭐⭐⭐ (5/5) - Excellent!</option>
                                                            <option value="4">⭐⭐⭐⭐ (4/5) - Very Good</option>
                                                            <option value="3">⭐⭐⭐ (3/5) - Average</option>
                                                            <option value="2">⭐⭐ (2/5) - Poor</option>
                                                            <option value="1">⭐ (1/5) - Terrible</option>
                                                        </select>
                                                    </div>

                                                    <div class="text-start">
                                                        <label class="form-label small fw-bold text-muted text-uppercase">Your Review (Optional)</label>
                                                        <textarea name="comment" class="form-control bg-light border-0" rows="4" placeholder="Tell us about the vehicle and our service..." style="border-radius:10px;"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer p-3 bg-light" style="border-radius: 0 0 15px 15px;">
                                                    <button type="button" class="btn btn-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="submit_review" class="btn btn-primary fw-bold rounded-pill px-4">Submit Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <div class="text-muted opacity-50 mb-3"><i class="fa-regular fa-face-smile fa-3x"></i></div>
                            <p class="text-muted small fw-bold">You don't have any pending reviews right now.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. Submitted Reviews List -->
                <div class="premium-card p-4">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-star text-warning me-2"></i>My Past Reviews</h5>

                    <?php if (mysqli_num_rows($my_reviews) > 0): ?>
                        <div class="row g-4">
                            <?php while ($mr = mysqli_fetch_assoc($my_reviews)): ?>
                                <div class="col-12 border-bottom pb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo htmlspecialchars($mr['image']); ?>" style="width: 50px; height: 35px; border-radius: 5px; object-fit: cover;" onerror="this.src='assets/images/default-car.jpg'">
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($mr['vehicle_name']); ?></h6>
                                                <div class="star-rating">
                                                    <?php
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $mr['rating']) echo '<i class="fa-solid fa-star"></i>';
                                                        else echo '<i class="fa-regular fa-star text-muted opacity-25"></i>';
                                                    }
                                                    ?>
                                                    <span class="small text-muted ms-2"><?php echo date('M d, Y', strtotime($mr['created_at'])); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Display review moderation status badge -->
                                        <span class="badge bg-light text-secondary border rounded-pill px-3 py-1">
                                            <i class="fa-solid fa-circle-info me-1"></i> <?php echo htmlspecialchars($mr['status']); ?>
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-0 fst-italic mt-2">
                                        <?php echo !empty($mr['comment']) ? '"' . htmlspecialchars($mr['comment']) . '"' : 'No written feedback provided.'; ?>
                                    </p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="text-muted opacity-25 mb-3"><i class="fa-regular fa-star fa-3x"></i></div>
                            <p class="text-muted small">You haven't submitted any reviews yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>