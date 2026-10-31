<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Get current logged-in user ID
$current_user_id = isset($_SESSION['adminid']) ? $_SESSION['adminid'] : 1;

// Retrieve admin or staff profile information
$sql = "SELECT s.*, u.email, u.role, u.account_status, u.created_at as joined_date 
        FROM staff s 
        JOIN users u ON s.user_id = u.user_id 
        WHERE u.user_id = $current_user_id";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
    echo "<div class='alert alert-danger m-4 rounded-4 shadow-sm'>Profile data not found!</div>";
    exit();
}
?>

<link rel="stylesheet" href="../css/admin.css">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">My Profile</h2>
        <p class="text-muted small mb-0">View your personal information and system role</p>
    </div>

    <!-- Edit Profile Button leading to settings page -->
    <a href="settings.php" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
        <i class="fa-solid fa-user-pen me-2"></i>Edit Profile
    </a>
</div>

<div class="premium-card mb-5">
    <!-- Profile Cover & Avatar -->
    <div class="profile-cover">
        <div class="profile-avatar-wrapper">
            <div class="profile-avatar">
                <div class="profile-initials">
                    <?php echo strtoupper(substr($admin['full_name'], 0, 1)); ?>
                </div>
            </div>
            <div class="mb-2">
                <h2 class="fw-bold text-white mb-1" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                    <?php echo htmlspecialchars($admin['full_name']); ?>
                </h2>
                <span class="badge bg-light text-dark bg-opacity-75 fw-bold px-3 py-1 rounded-pill shadow-sm">
                    <i class="fa-solid fa-briefcase me-1 text-primary"></i> <?php echo htmlspecialchars($admin['position']); ?>
                </span>
            </div>
        </div>

        <!-- Status Badge Top Right -->
        <div class="position-absolute top-0 end-0 p-3">
            <span class="badge bg-<?php echo ($admin['account_status'] == 'ACTIVE') ? 'success' : 'danger'; ?> fw-bold px-4 py-2 rounded-pill shadow-sm fs-6 border border-white">
                <i class="fa-solid <?php echo ($admin['account_status'] == 'ACTIVE') ? 'fa-check-circle' : 'fa-ban'; ?> me-1"></i> <?php echo htmlspecialchars($admin['account_status']); ?>
            </span>
        </div>
    </div>

    <!-- Profile Information Body -->
    <div class="profile-body">
        <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
            <i class="fa-regular fa-id-card text-primary me-2"></i> Contact & System Details
        </h5>

        <div class="row g-4">
            <!-- Email -->
            <div class="col-xl-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon icon-blue"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <span class="info-label">Email Address (Login ID)</span>
                        <span class="info-value"><?php echo htmlspecialchars($admin['email']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div class="col-xl-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon icon-green"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <span class="info-label">Phone Number</span>
                        <span class="info-value"><?php echo htmlspecialchars($admin['phone']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Role -->
            <div class="col-xl-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon icon-purple"><i class="fa-solid fa-shield-halved"></i></div>
                    <div>
                        <span class="info-label">System Privileges</span>
                        <span class="info-value text-uppercase"><?php echo htmlspecialchars($admin['role']); ?> Access</span>
                    </div>
                </div>
            </div>

            <!-- Joined Date -->
            <div class="col-xl-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon icon-orange"><i class="fa-solid fa-calendar-check"></i></div>
                    <div>
                        <span class="info-label">Member Since</span>
                        <span class="info-value"><?php echo date('F d, Y', strtotime($admin['joined_date'])); ?></span>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-5 p-4 bg-light rounded-4 border border-secondary-subtle d-flex align-items-start gap-3">
            <div class="text-primary mt-1 fs-5"><i class="fa-solid fa-circle-info"></i></div>
            <div>
                <h6 class="fw-bold text-dark mb-1">Need to update your information or password?</h6>
                <p class="text-muted small mb-0 fw-semibold">Click on the "Edit Profile" button at the top of the page to securely change your contact details or login password.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php"; ?>