<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Validate that customer user ID is passed via GET parameter
if (!isset($_GET['id'])) {
    echo "<script>window.location.href='customers.php';</script>";
    exit();
}

$user_id = (int)$_GET['id'];

// Handle toggling customer account status between ACTIVE and SUSPENDED
if (isset($_POST['toggle_status'])) {
    $current_status = $_POST['current_status'];
    $new_status = ($current_status == 'ACTIVE') ? 'SUSPENDED' : 'ACTIVE';
    
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, "UPDATE users SET account_status = ? WHERE user_id = ?")) {
        mysqli_stmt_bind_param($stmt, "si", $new_status, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        // Refresh page with status update confirmation
        echo "<script>window.location.href='view_customer.php?id=".$user_id."&msg=status_updated';</script>";
    }
}

// Retrieve customer details and user account information
$sql_cust = "SELECT c.*, u.email, u.account_status, u.created_at as joined_date 
             FROM customers c 
             JOIN users u ON c.user_id = u.user_id 
             WHERE c.user_id = $user_id";
$cust_res = mysqli_query($conn, $sql_cust);
$customer = mysqli_fetch_assoc($cust_res);

if (!$customer) {
    echo "<div class='alert alert-danger m-4 rounded-4 shadow-sm'>Customer record not found!</div>";
    exit();
}

// Retrieve all bookings created by this customer
$sql_bookings = "SELECT b.*, v.name as vehicle_name, v.image 
                 FROM bookings b 
                 JOIN vehicles v ON b.vehicle_id = v.id 
                 WHERE b.user_id = $user_id 
                 ORDER BY b.created_at DESC";
$bookings_res = mysqli_query($conn, $sql_bookings);
?>

<!-- Customer Profile Header Card -->
<div class="premium-card mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="customers.php" class="btn btn-sm btn-light border fw-bold text-muted" style="border-radius: 8px;"><i class="fa-solid fa-arrow-left me-2"></i>Back to Customers List</a>
    
    <!-- Account status action toggle button -->
    <div class="d-flex align-items-center gap-2">
        <form action="view_customer.php?id=<?php echo $user_id; ?>" method="POST" class="m-0">
            <input type="hidden" name="current_status" value="<?php echo $customer['account_status']; ?>">
            
            <?php if($customer['account_status'] == 'ACTIVE'): ?>
                <button type="submit" name="toggle_status" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3 shadow-sm" onclick="return confirm('Suspend this customer\'s account? They won\'t be able to log in.');">
                    <i class="fa-solid fa-ban me-1"></i> Suspend Account
                </button>
            <?php else: ?>
                <button type="submit" name="toggle_status" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 shadow-sm" onclick="return confirm('Re-activate this customer\'s account?');">
                    <i class="fa-solid fa-check me-1"></i> Activate Account
                </button>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'status_updated'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> Customer account status has been updated successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Customer Profile Header Card -->
<div class="premium-card mb-5">
    <div class="profile-cover">
        <div class="profile-avatar-wrapper">
            <div class="profile-avatar">
                <div class="profile-initials"><?php echo strtoupper(substr($customer['full_name'], 0, 1)); ?></div>
            </div>
            <div class="mb-2">
                <h3 class="fw-bold text-white mb-1" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);"><?php echo htmlspecialchars($customer['full_name']); ?></h3>
            </div>
        </div>
        
        <div class="position-absolute top-0 end-0 p-3">
            <span class="badge bg-<?php echo ($customer['account_status']=='ACTIVE')?'success':'danger'; ?> fw-bold px-3 py-2 rounded-pill shadow-sm">
                <i class="fa-solid <?php echo ($customer['account_status']=='ACTIVE')?'fa-check-circle':'fa-ban'; ?> me-1"></i> <?php echo htmlspecialchars($customer['account_status']); ?>
            </span>
        </div>
    </div>

    <div class="profile-body">
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="info-box">
                    <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <span class="info-label">Email Address</span>
                        <span class="info-value"><?php echo htmlspecialchars($customer['email']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="info-box">
                    <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <span class="info-label">Phone Number</span>
                        <span class="info-value"><?php echo htmlspecialchars($customer['phone']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="info-box">
                    <div class="info-icon bg-opacity-10 text-success" style="background: rgba(16, 185, 129, 0.1);"><i class="fa-solid fa-id-card"></i></div>
                    <div>
                        <span class="info-label">Driving License</span>
                        <span class="info-value"><?php echo htmlspecialchars($customer['driving_license_no'] ?? 'Not Provided'); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="info-box">
                    <div class="info-icon bg-opacity-10 text-warning" style="background: rgba(245, 158, 11, 0.1);"><i class="fa-solid fa-calendar-plus"></i></div>
                    <div>
                        <span class="info-label">Joined Date</span>
                        <span class="info-value"><?php echo date('Y M d', strtotime($customer['joined_date'])); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="info-box">
                    <div class="info-icon text-muted" style="background: rgba(100, 116, 139, 0.1);"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div>
                        <span class="info-label">Physical Address</span>
                        <span class="info-value text-secondary fw-semibold"><?php echo htmlspecialchars($customer['address'] ?? 'Address not updated yet.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking History -->
<div class="d-flex align-items-center gap-3 mb-3">
    <div class="icon-box icon-box-blue rounded-circle" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center; background:rgba(13,110,253,0.1); color:#0d6efd;"><i class="fa-solid fa-clock-rotate-left"></i></div>
    <h4 class="fw-bold mb-0 text-dark">Rental History</h4>
</div>

<div class="table-responsive px-1">
    <table class="table table-borderless table-modern w-100">
        <thead>
            <tr>
                <th>Booking Ref</th>
                <th>Vehicle Model</th>
                <th>Schedule Dates</th>
                <th>Amount</th>
                <th>Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($bookings_res) > 0): ?>
                <?php while ($b = mysqli_fetch_assoc($bookings_res)):
                    $badge = 'warning';
                    if ($b['status'] == 'Confirmed') $badge = 'primary';
                    if ($b['status'] == 'Active Rental') $badge = 'info';
                    if ($b['status'] == 'Completed') $badge = 'success';
                    if ($b['status'] == 'Cancelled') $badge = 'danger';
                ?>
                    <tr>
                        <td class="fw-bold text-dark">#CRB-<?php echo str_pad($b['id'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="../<?php echo htmlspecialchars($b['image']); ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" onerror="this.src='../assets/images/default-car.jpg'">
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($b['vehicle_name']); ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="small fw-bold text-success mb-1"><i class="fa-solid fa-arrow-right me-1"></i><?php echo $b['pickup_date']; ?></span>
                                <span class="small fw-bold text-danger"><i class="fa-solid fa-arrow-left me-1"></i><?php echo $b['return_date']; ?></span>
                            </div>
                        </td>
                        <td class="fw-bold text-primary fs-6">Rs. <?php echo number_format($b['total_price'], 0); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $badge; ?> bg-opacity-10 text-<?php echo $badge; ?> border border-<?php echo $badge; ?>-subtle px-3 py-2 rounded-pill fw-bold shadow-sm">
                                <?php echo $b['status']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="view_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-light border fw-bold text-primary" style="border-radius: 8px;">
                                View Details <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-5 bg-white rounded-4 border border-light">
                        <div class="text-muted opacity-50 mb-3"><i class="fa-solid fa-car-side fa-3x"></i></div>
                        <h5 class="fw-bold text-dark">No Bookings Yet</h5>
                        <p class="text-muted small">This customer hasn't made any reservations yet.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<br>

<?php require_once "footer.php"; ?>