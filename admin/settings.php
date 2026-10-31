<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Get current logged-in user ID or default
$current_user_id = isset($_SESSION['adminid']) ? $_SESSION['adminid'] : 1; 

// Handle profile details update for staff or admin
if (isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $position = trim($_POST['position']);
    
    // Check if new email is already in use by another user
    $check_email = mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$email' AND user_id != $current_user_id");
    if(mysqli_num_rows($check_email) > 0){
        echo "<script>window.location.href='settings.php?error=email_exists';</script>";
        exit();
    }
    
    // Update email address in users table
    $stmt1 = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt1, "UPDATE users SET email = ? WHERE user_id = ?")) {
        mysqli_stmt_bind_param($stmt1, "si", $email, $current_user_id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);
    }
    
    // Update profile details in staff table
    $stmt2 = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt2, "UPDATE staff SET full_name = ?, phone = ?, position = ? WHERE user_id = ?")) {
        mysqli_stmt_bind_param($stmt2, "sssi", $full_name, $phone, $position, $current_user_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    }
    echo "<script>window.location.href='settings.php?msg=profile_updated';</script>";
    exit();
}

// Handle password change with hashing
if (isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt3 = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt3, "UPDATE users SET password = ? WHERE user_id = ?")) {
            mysqli_stmt_bind_param($stmt3, "si", $hashed_password, $current_user_id);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_close($stmt3);
            echo "<script>window.location.href='settings.php?msg=password_updated';</script>";
            exit();
        }
    } else {
        echo "<script>window.location.href='settings.php?error=password_mismatch';</script>";
        exit();
    }
}

// Retrieve current user profile details from database
$sql = "SELECT s.*, u.email, u.role 
        FROM staff s 
        JOIN users u ON s.user_id = u.user_id 
        WHERE u.user_id = $current_user_id";
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);

if(!$user_data) {
    // Show warning notice if admin record is not found
    echo "<div class='alert alert-warning m-4'>Admin Data not found! Please check if user_id $current_user_id exists in staff table.</div>";
}
?>

<style>
    </style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Account Settings</h2>
        <p class="text-muted small mb-0">Update your personal profile details and security settings.</p>
    </div>
</div>

<!-- Alerts -->
<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'profile_updated'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>Profile details updated successfully! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['msg'] == 'password_updated'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-shield-check me-2"></i>Password has been changed successfully! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <?php if($_GET['error'] == 'email_exists'): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-circle-exclamation me-2"></i>This Email is already in use by another account. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['error'] == 'password_mismatch'): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>New passwords do not match. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
<?php endif; ?>

<div class="row g-4 mb-5">
    
    <!-- 1. Edit Profile Form -->
    <div class="col-xl-7 col-lg-6">
        <div class="premium-card">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary-subtle">
                <div class="settings-icon-box bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-user-pen"></i></div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Profile Information</h5>
                    <small class="text-muted">Manage your public information</small>
                </div>
            </div>
            
            <?php if($user_data): ?>
            <form action="settings.php" method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                        <input type="text" name="full_name" class="form-control form-control-modern" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-modern" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-modern" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Job Position</label>
                        <input type="text" name="position" class="form-control form-control-modern" value="<?php echo htmlspecialchars($user_data['position']); ?>" required>
                    </div>
                    <div class="col-12 mt-4 pt-3 border-top border-secondary-subtle d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-dark border px-3 py-2 fw-bold"><i class="fa-solid fa-id-badge text-primary me-2"></i> Role: <?php echo $user_data['role']; ?></span>
                        <button type="submit" name="update_profile" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- 2. Change Password Form -->
    <div class="col-xl-5 col-lg-6">
        <div class="premium-card">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary-subtle">
                <div class="settings-icon-box bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-lock"></i></div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Security Settings</h5>
                    <small class="text-muted">Update your login password</small>
                </div>
            </div>
            
            <form action="settings.php" method="POST">
                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-danger text-uppercase">New Password</label>
                        <input type="password" name="new_password" class="form-control form-control-modern border-danger-subtle" placeholder="Enter new password" required minlength="6">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-danger text-uppercase">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control form-control-modern border-danger-subtle" placeholder="Re-type new password" required minlength="6">
                    </div>
                    <div class="col-12 mt-4 pt-3 border-top border-secondary-subtle text-end">
                        <button type="submit" name="update_password" class="btn btn-dark fw-bold px-4 rounded-pill shadow-sm"><i class="fa-solid fa-key me-2"></i>Update Password</button>
                    </div>
                </div>
            </form>
            
            <div class="mt-4 p-3 bg-light rounded-3 border border-warning-subtle d-flex gap-3 align-items-start">
                <i class="fa-solid fa-circle-info text-warning mt-1"></i>
                <small class="text-muted fw-semibold">For your security, make sure to use a strong password combining letters, numbers, and symbols.</small>
            </div>
        </div>
    </div>

</div>

<?php include_once("footer.php"); ?>