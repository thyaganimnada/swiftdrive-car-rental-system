<?php 
// Include customer profile management backend action logic
require_once 'actions/profile_action.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document meta configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | SwiftDrive</title>
    <!-- Favicon and CSS stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="assets/logo.png">
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

        <!-- Alerts -->
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'profile_updated'): ?><div class="alert alert-success rounded-pill shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>Profile details updated successfully!</div><?php endif; ?>
            <?php if ($_GET['msg'] == 'password_updated'): ?><div class="alert alert-success rounded-pill shadow-sm"><i class="fa-solid fa-shield-check me-2"></i>Password changed securely!</div><?php endif; ?>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'wrong_password'): ?><div class="alert alert-danger rounded-pill shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>Current password is incorrect!</div><?php endif; ?>
            <?php if ($_GET['error'] == 'password_mismatch'): ?><div class="alert alert-danger rounded-pill shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>New passwords do not match!</div><?php endif; ?>
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
                                <li><a href="my_blogs.php" class="menu-link"><i class="fa-solid fa-pen-nib"></i> My Blogs</a></li>
                                <li><a href="my_reviews.php" class="menu-link"><i class="fa-solid fa-star"></i> My Reviews</a></li>
                                <li><a href="my_profile.php" class="menu-link active"><i class="fa-solid fa-user"></i> My Profile</a></li>
                                <li><a href="actions/logout.php" class="menu-link text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area (Profile & Password Settings) -->
            <div class="col-lg-9">
                <h3 class="fw-bold text-dark mb-4">My Profile Settings</h3>

                <div class="row g-4">
                    <!-- Edit Profile Card -->
                    <div class="col-xl-7">
                        <div class="premium-card p-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-user-pen text-primary me-2"></i>Personal Information</h5>
                            <form action="my_profile.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                                    <input type="text" name="full_name" class="form-control form-control-modern" value="<?php echo htmlspecialchars($customer['full_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Email Address (Login ID)</label>
                                    <input type="email" class="form-control form-control-modern bg-light" value="<?php echo htmlspecialchars($customer['email']); ?>" disabled>
                                    <small class="text-muted">Email cannot be changed.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Phone Number</label>
                                    <input type="text" name="phone" class="form-control form-control-modern" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Driving License Number</label>
                                    <input type="text" name="driving_license_no" class="form-control form-control-modern" value="<?php echo htmlspecialchars($customer['driving_license_no'] ?? ''); ?>">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Residential Address</label>
                                    <textarea name="address" class="form-control form-control-modern" rows="3"><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-primary fw-bold rounded-pill px-4 w-100 shadow-sm"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password Card -->
                    <div class="col-xl-5">
                        <div class="premium-card p-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-lock text-warning me-2"></i>Change Password</h5>
                            <form action="my_profile.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Current Password</label>
                                    <input type="password" name="current_password" class="form-control form-control-modern" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-danger text-uppercase">New Password</label>
                                    <input type="password" name="new_password" class="form-control form-control-modern border-danger-subtle" required minlength="6">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-danger text-uppercase">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control form-control-modern border-danger-subtle" required minlength="6">
                                </div>
                                <button type="submit" name="change_password" class="btn btn-warning text-dark fw-bold rounded-pill px-4 w-100 shadow-sm"><i class="fa-solid fa-key me-2"></i>Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>