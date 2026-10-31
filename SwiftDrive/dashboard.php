<?php 
// Include customer dashboard backend action logic
require_once 'actions/dashboard_action.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document meta configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | SwiftDrive</title>
    <!-- Favicon and CSS stylesheets -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>

<body>

    <!-- Customer navigation bar -->
    <nav class="customer-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="brand-logo"><img src="assets/logo.png" alt="SwiftDrive Logo" style="height: 40px; width: auto; margin-right: 8px;">SwiftDrive</a>
            <a href="index.php" class="btn btn-outline-primary fw-bold rounded-pill px-4"><i class="fa-solid fa-house me-2"></i>Home</a>
        </div>
    </nav>

    <div class="container mb-5">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'cancelled'): ?>
            <div class="alert alert-success rounded-pill shadow-sm"><i class="fa-solid fa-ban me-2"></i>Booking cancelled and refund processed.</div>
        <?php endif; ?>

        <!-- Mobile Menu Button -->
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
                        <div class="premium-card w-100">
                            <div class="profile-widget">
                                <div class="avatar-circle"><?php echo strtoupper(substr($customer['full_name'], 0, 1)); ?></div>
                                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($customer['full_name']); ?></h5>
                                <p class="text-muted small mb-0"><i class="fa-solid fa-envelope me-1"></i> <?php echo htmlspecialchars($customer['email']); ?></p>
                            </div>
                            <ul class="menu-list">
                                <li><a href="dashboard.php" class="menu-link active"><i class="fa-solid fa-table-columns"></i> Dashboard</a></li>
                                <li><a href="my_bookings.php" class="menu-link"><i class="fa-solid fa-calendar-check"></i> My Bookings</a></li>
                                <li><a href="my_reviews.php" class="menu-link"><i class="fa-solid fa-star"></i> My Reviews</a></li>
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
                <h3 class="fw-bold text-dark mb-4">Welcome back, <?php echo explode(' ', $customer['full_name'])[0]; ?>! 👋</h3>

                <!-- Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-car"></i></div>
                            <div>
                                <h3 class="fw-bold mb-0 text-dark"><?php echo $total_bookings; ?></h3><span class="text-muted small fw-bold text-uppercase">Total Bookings</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-clock"></i></div>
                            <div>
                                <h3 class="fw-bold mb-0 text-dark"><?php echo $active_bookings; ?></h3><span class="text-muted small fw-bold text-uppercase">Active Rentals</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-wallet"></i></div>
                            <div>
                                <h3 class="fw-bold mb-0 text-dark">Rs. <?php echo number_format($total_spent, 0); ?></h3><span class="text-muted small fw-bold text-uppercase">Total Spent</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="premium-card p-4">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-calendar-check text-primary me-2"></i>My Booking History</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="pb-3">Vehicle</th>
                                    <th class="pb-3">Dates</th>
                                    <th class="pb-3">Amount</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($bookings) > 0): ?>
                                    <?php while ($b = mysqli_fetch_assoc($bookings)):
                                        $badge = 'warning';
                                        if ($b['status'] == 'Confirmed') $badge = 'primary';
                                        if ($b['status'] == 'Active Rental') $badge = 'info';
                                        if ($b['status'] == 'Completed') $badge = 'success';
                                        if ($b['status'] == 'Cancelled') $badge = 'danger';
                                    ?>
                                        <tr>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?php echo htmlspecialchars($b['vehicle_image']); ?>" class="vehicle-img" onerror="this.src='assets/images/default-car.jpg'">
                                                    <div>
                                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($b['vehicle_name']); ?></div><small class="text-muted">#CRB-<?php echo str_pad($b['id'], 4, '0', STR_PAD_LEFT); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="small fw-bold text-success"><i class="fa-solid fa-arrow-right me-1"></i><?php echo date('M d', strtotime($b['pickup_date'])); ?></div>
                                                <div class="small fw-bold text-danger"><i class="fa-solid fa-arrow-left me-1"></i><?php echo date('M d', strtotime($b['return_date'])); ?></div>
                                            </td>
                                            <td class="py-3 fw-bold text-dark">Rs. <?php echo number_format($b['total_price'], 0); ?></td>
                                            <td class="py-3"><span class="badge bg-<?php echo $badge; ?> bg-opacity-10 text-<?php echo $badge; ?> border border-<?php echo $badge; ?>-subtle px-3 py-1 rounded-pill"><?php echo $b['status']; ?></span></td>
                                            <td class="py-3 text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="view_my_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">View</a>
                                                    <?php if ($b['status'] == 'Pending Payment' || $b['status'] == 'Confirmed'): ?>
                                                        <form action="dashboard.php" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to cancel this booking? Refunds will be processed.');">
                                                            <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                                            <button type="submit" name="cancel_booking" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">Cancel</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted opacity-50 mb-3"><i class="fa-solid fa-car-side fa-3x"></i></div>
                                            <h5 class="fw-bold text-dark">No Bookings Found</h5><a href="index.php" class="btn btn-primary fw-bold rounded-pill px-4 mt-2">Browse Vehicles</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>