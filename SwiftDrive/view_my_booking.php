<?php require_once("actions/view_boking.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Status | SwiftDrive</title>
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
        
        <div class="d-lg-none mb-3">
            <button class="btn btn-dark w-100 fw-bold rounded-pill py-2 shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#customerSidebar">
                <i class="fa-solid fa-bars me-2"></i> Open Dashboard Menu
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
                        <div class="premium-card w-100 p-0">
                            <div class="profile-widget">
                                <div class="avatar-circle"><?php echo strtoupper(substr($customer['full_name'], 0, 1)); ?></div>
                                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($customer['full_name']); ?></h5>
                                <p class="text-muted small mb-0"><i class="fa-solid fa-envelope me-1"></i> <?php echo htmlspecialchars($customer['email']); ?></p>
                            </div>
                            <ul class="menu-list">
                                <li><a href="dashboard.php" class="menu-link"><i class="fa-solid fa-table-columns"></i> Dashboard</a></li>
                                <li><a href="my_bookings.php" class="menu-link active"><i class="fa-solid fa-calendar-check"></i> My Bookings</a></li>
                                <li><a href="my_reviews.php" class="menu-link"><i class="fa-solid fa-star"></i> My Reviews</a></li>
                                <li><a href="my_blogs.php" class="menu-link"><i class="fa-solid fa-pen-nib"></i> My Blogs</a></li>
                                <li><a href="my_profile.php" class="menu-link"><i class="fa-solid fa-user"></i> My Profile</a></li>
                                <li><a href="actions/logout.php" class="menu-link text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area (Rental Status Tracker) -->
            <div class="col-lg-9">
                <a href="dashboard.php" class="btn btn-light border fw-bold mb-4 rounded-pill px-4 shadow-sm"><i class="fa-solid fa-arrow-left me-2"></i>Back to My Bookings</a>
                
                <div class="premium-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-4 gap-3">
                        <h4 class="fw-bold mb-0 text-dark">Rental Status</h4>
                        <?php 
                            $badge = 'secondary';
                            if($b['status'] == 'Pending Payment' || $b['status'] == 'Pending') $badge = 'warning';
                            if($b['status'] == 'Confirmed') $badge = 'primary';
                            if($b['status'] == 'Active Rental') $badge = 'info';
                            if($b['status'] == 'Completed') $badge = 'success';
                            if($b['status'] == 'Cancelled') $badge = 'danger';
                        ?>
                        <span class="badge bg-<?php echo $badge; ?> fs-6 px-4 py-2 rounded-pill shadow-sm text-uppercase fw-bold">
                            <?php echo $b['status']; ?>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-4 mb-5 bg-light p-3 rounded-4 border">
                        <img src="<?php echo htmlspecialchars($b['vehicle_image']); ?>" style="width: 140px; height: 90px; object-fit: cover; border-radius: 10px;" onerror="this.src='assets/images/default-car.jpg'">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($b['vehicle_name']); ?></h5>
                            <div class="text-muted small fw-bold">Booking Ref: #CRB-<?php echo str_pad($b['id'], 4, '0', STR_PAD_LEFT); ?></div>
                            <div class="text-primary fw-bold mt-1 fs-5">Total Cost: Rs. <?php echo number_format($b['total_price'], 0); ?></div>
                        </div>
                        
                        <!-- Show Pay Now button only if booking status is Pending Payment -->
                        <?php if($b['status'] == 'Pending Payment' || $b['status'] == 'Pending'): ?>
                        <div class="ms-auto text-end">
                            <p class="text-danger small fw-bold mb-2"><i class="fa-solid fa-circle-exclamation me-1"></i> Payment Required</p>
                            <a href="payment.php?booking_id=<?php echo $b['id']; ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                                <i class="fa-solid fa-credit-card me-2"></i> Pay Now
                            </a>
                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- Dynamic process tracker timeline -->
                    <h5 class="fw-bold text-dark mt-5 border-bottom pb-2">Trip Progress Tracker</h5>
                    <div class="status-timeline">
                        
                        <?php if($b['status'] == 'Cancelled'): ?>
                            <div class="timeline-item cancelled">
                                <h6 class="fw-bold mb-0 text-danger">Booking Cancelled</h6>
                                <small class="text-muted">This booking was cancelled and applicable refunds have been processed.</small>
                            </div>
                        <?php else: ?>
                            
                            <!-- Step 1: Booking Placed -->
                            <div class="timeline-item completed">
                                <h6 class="fw-bold mb-0 text-success">Booking Placed</h6>
                                <small class="text-muted">We have received your reservation request.</small>
                            </div>
                            
                            <!-- Step 2: Payment and Confirmation -->
                            <?php if($b['status'] == 'Pending Payment' || $b['status'] == 'Pending'): ?>
                                <div class="timeline-item active">
                                    <h6 class="fw-bold mb-0 text-warning">Payment Pending</h6>
                                    <small class="text-muted">Please complete your security deposit payment to confirm the reservation.</small>
                                    <div class="mt-2">
                                        <a href="payment.php?booking_id=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill px-3">Proceed to Payment</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="timeline-item <?php echo (in_array($b['status'], ['Confirmed', 'Active Rental', 'Completed'])) ? 'completed' : 'active'; ?>">
                                    <h6 class="fw-bold mb-0 <?php echo (in_array($b['status'], ['Confirmed', 'Active Rental', 'Completed'])) ? 'text-success' : 'text-primary'; ?>">Payment & Confirmation</h6>
                                    <small class="text-muted">Security deposit processed and vehicle is reserved.</small>
                                </div>
                            <?php endif; ?>
                            
                            <!-- 3. Active Rental -->
                            <div class="timeline-item <?php echo (in_array($b['status'], ['Active Rental', 'Completed'])) ? 'completed' : ''; ?> <?php echo ($b['status'] == 'Confirmed') ? 'active' : ''; ?>">
                                <h6 class="fw-bold mb-0 <?php echo (in_array($b['status'], ['Active Rental', 'Completed'])) ? 'text-success' : ''; ?>">Active Rental</h6>
                                <small class="text-muted">Vehicle picked up. Enjoy your safe journey!</small>
                            </div>
                            
                            <!-- 4. Trip Completed -->
                            <div class="timeline-item <?php echo ($b['status'] == 'Completed') ? 'completed' : ''; ?> <?php echo ($b['status'] == 'Active Rental') ? 'active' : ''; ?>">
                                <h6 class="fw-bold mb-0 <?php echo ($b['status'] == 'Completed') ? 'text-success' : ''; ?>">Trip Completed</h6>
                                <small class="text-muted">Vehicle returned and final settlement completed.</small>
                            </div>
                            
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>