<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Validate that booking ID parameter exists in URL
if (!isset($_GET['id'])) {
    echo "<script>window.location.href='bookings.php';</script>";
    exit();
}

// Sanitize booking ID
$booking_id = (int)$_GET['id'];

// Retrieve booking details joined with customer, user, and vehicle records
$sql = "SELECT b.*, 
        COALESCE(c.full_name, s.full_name, 'Unknown User') as full_name, 
        COALESCE(c.phone, s.phone, 'N/A') as phone, 
        c.driving_license_no, 
        c.address, 
        u.email,
        v.name as vehicle_name, v.image as vehicle_image, v.daily_rate 
        FROM bookings b 
        JOIN users u ON b.user_id = u.user_id 
        LEFT JOIN customers c ON u.user_id = c.user_id 
        LEFT JOIN staff s ON u.user_id = s.user_id 
        JOIN vehicles v ON b.vehicle_id = v.id 
        WHERE b.id = $booking_id";
        
$result = mysqli_query($conn, $sql);

if (!$result) {
    // Terminate script if query execution fails
    die("<div class='alert alert-danger m-4'>Query Error: " . mysqli_error($conn) . "</div>");
}

$b = mysqli_fetch_assoc($result);

if (!$b) {
    echo "<div class='alert alert-danger m-4 rounded-4 shadow-sm'>Booking not found in the system!</div>";
    exit();
}

// Retrieve payment records associated with this booking
$payments = mysqli_query($conn, "SELECT * FROM payments WHERE booking_id = $booking_id ORDER BY created_at ASC");
?>


<link rel="stylesheet" href="../css/admin.css">
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <a href="bookings.php" class="btn btn-sm btn-light border fw-bold text-muted mb-2" style="border-radius: 8px;"><i class="fa-solid fa-arrow-left me-2"></i>Back to Bookings</a>
        <h2 class="fw-bold mb-0 text-dark d-flex align-items-center gap-3">
            Booking #CRB-<?php echo str_pad($b['id'], 4, '0', STR_PAD_LEFT); ?>
        </h2>
    </div>
    <div>
        <?php 
            $statusBadge = 'bg-warning text-dark';
            if($b['status'] == 'Confirmed') $statusBadge = 'bg-primary';
            if($b['status'] == 'Active Rental') $statusBadge = 'bg-info text-dark';
            if($b['status'] == 'Completed') $statusBadge = 'bg-success';
            if($b['status'] == 'Cancelled') $statusBadge = 'bg-danger';
        ?>
        <span class="badge <?php echo $statusBadge; ?> fs-6 px-4 py-2 rounded-pill shadow-sm border border-white">
            <i class="fa-solid fa-circle-dot me-2" style="font-size: 0.6rem; vertical-align: middle;"></i><?php echo htmlspecialchars($b['status']); ?>
        </span>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- 1. Customer Info -->
    <div class="col-xl-4 col-lg-6">
        <div class="premium-card">
            <div class="card-icon-header">
                <div class="icon-box icon-box-blue"><i class="fa-solid fa-user"></i></div>
                <h5 class="fw-bold mb-0 text-dark">Customer Details</h5>
            </div>
            <span class="data-label">Full Name</span>
            <span class="data-value"><?php echo htmlspecialchars($b['full_name']); ?></span>
            
            <div class="row">
                <div class="col-6">
                    <span class="data-label">Phone</span>
                    <span class="data-value"><i class="fa-solid fa-phone text-muted me-1 fs-6"></i> <?php echo htmlspecialchars($b['phone']); ?></span>
                </div>
                <div class="col-6">
                    <span class="data-label">License No</span>
                    <span class="data-value"><i class="fa-solid fa-id-card text-muted me-1 fs-6"></i> <?php echo htmlspecialchars($b['driving_license_no'] ?? 'N/A'); ?></span>
                </div>
            </div>

            <span class="data-label">Email Address</span>
            <span class="data-value"><i class="fa-solid fa-envelope text-muted me-1 fs-6"></i> <?php echo htmlspecialchars($b['email']); ?></span>
            
            <span class="data-label">Address</span>
            <span class="data-value mb-0 fw-semibold text-secondary" style="font-size: 0.95rem; line-height: 1.5;"><i class="fa-solid fa-map-location-dot text-muted me-1 fs-6"></i> <?php echo htmlspecialchars($b['address'] ?? 'Not Provided'); ?></span>
        </div>
    </div>

    <!-- 2. Rental Schedule -->
    <div class="col-xl-4 col-lg-6">
        <div class="premium-card">
            <div class="card-icon-header">
                <div class="icon-box icon-box-green"><i class="fa-regular fa-calendar-days"></i></div>
                <h5 class="fw-bold mb-0 text-dark">Rental Schedule</h5>
            </div>
            
            <div class="date-box pickup">
                <span class="text-success fw-bold small text-uppercase d-block mb-1"><i class="fa-solid fa-arrow-right me-1"></i> Pickup</span>
                <div class="fs-5 fw-bold text-dark mb-1"><?php echo $b['pickup_date']; ?></div>
                <div class="small fw-semibold text-muted"><i class="fa-solid fa-location-dot me-1 text-success"></i> <?php echo htmlspecialchars($b['pickup_location']); ?></div>
            </div>
            
            <div class="date-box return mb-4">
                <span class="text-danger fw-bold small text-uppercase d-block mb-1"><i class="fa-solid fa-arrow-left me-1"></i> Return</span>
                <div class="fs-5 fw-bold text-dark mb-1"><?php echo $b['return_date']; ?></div>
                <div class="small fw-semibold text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo htmlspecialchars($b['return_location']); ?></div>
            </div>

            <div class="d-flex justify-content-between align-items-center px-2">
                <span class="fw-bold text-muted">Total Duration:</span>
                <span class="badge bg-dark rounded-pill px-3 py-2 fs-6 shadow-sm"><?php echo $b['total_days']; ?> Days</span>
            </div>
        </div>
    </div>

    <!-- 3. Vehicle & Billing -->
    <div class="col-xl-4 col-lg-12">
        <div class="premium-card">
            <div class="card-icon-header">
                <div class="icon-box icon-box-purple"><i class="fa-solid fa-car"></i></div>
                <h5 class="fw-bold mb-0 text-dark">Vehicle & Billing</h5>
            </div>
            
            <div class="d-flex align-items-center gap-3 mb-4 p-2 rounded-3 border border-light">
                <img src="../<?php echo htmlspecialchars($b['vehicle_image']); ?>" class="rounded-3 shadow-sm" style="width: 100px; height: 70px; object-fit: cover;" onerror="this.src='../assets/images/default-car.jpg'">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($b['vehicle_name']); ?></h5>
                    <span class="badge bg-light text-primary border border-primary-subtle fw-bold">Rate: Rs. <?php echo number_format($b['daily_rate'], 0); ?> / day</span>
                </div>
            </div>
            
            <div class="bg-light p-4 rounded-4 border border-secondary-subtle position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted fw-bold">Total Rental Cost</span>
                        <span class="fs-4 fw-bold ">Rs. <?php echo number_format($b['total_price'], 0); ?></span>
                    </div>
                    <div class="text-muted small fw-semibold mb-3 border-bottom border-secondary-subtle pb-3">
                        Calculated: <?php echo $b['total_days']; ?> Days × Rs. <?php echo number_format($b['daily_rate'], 0); ?>
                    </div>
                    
                    <div>
                        <span class="d-block fw-bold text-dark mb-1 small text-uppercase">Special Requests:</span>
                        <p class="mb-0 small text-muted fw-semibold fst-italic">
                            <?php echo !empty($b['special_requests']) ? '"'.htmlspecialchars($b['special_requests']).'"' : 'None provided.'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4. Payment Timeline -->
<div class="premium-card p-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="icon-box icon-box-blue bg-opacity-10"><i class="fa-solid fa-credit-card"></i></div>
        <h5 class="fw-bold mb-0 text-dark">Payment Transactions</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0">
            <thead class="border-bottom border-secondary-subtle">
                <tr>
                    <th class="text-muted fw-bold text-uppercase small pb-3">Ref No</th>
                    <th class="text-muted fw-bold text-uppercase small pb-3">Date</th>
                    <th class="text-muted fw-bold text-uppercase small pb-3">Type</th>
                    <th class="text-muted fw-bold text-uppercase small pb-3">Method</th>
                    <th class="text-muted fw-bold text-uppercase small pb-3">Status</th>
                    <th class="text-end text-muted fw-bold text-uppercase small pb-3">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_paid = 0;
                if(mysqli_num_rows($payments) > 0): 
                    while($pay = mysqli_fetch_assoc($payments)): 
                        if($pay['payment_status'] == 'Completed') { $total_paid += $pay['amount']; }
                ?>
                <tr style="border-bottom: 1px dashed #e2e8f0;">
                    <td class="py-3 fw-bold text-primary"><?php echo htmlspecialchars($pay['transaction_ref']); ?></td>
                    <td class="py-3 text-muted fw-semibold small"><?php echo date('Y M d, h:i A', strtotime($pay['created_at'])); ?></td>
                    <td class="py-3 fw-bold text-dark"><?php echo htmlspecialchars($pay['payment_type']); ?></td>
                    <td class="py-3"><span class="badge bg-light text-secondary border fw-bold"><i class="fa-solid fa-building-columns me-1"></i> <?php echo htmlspecialchars($pay['payment_method']); ?></span></td>
                    <td class="py-3"><span class="badge bg-<?php echo ($pay['payment_status']=='Completed')?'success':'warning'; ?> bg-opacity-25 text-<?php echo ($pay['payment_status']=='Completed')?'success':'warning'; ?> border border-<?php echo ($pay['payment_status']=='Completed')?'success':'warning'; ?>-subtle rounded-pill px-3"><?php echo htmlspecialchars($pay['payment_status']); ?></span></td>
                    <td class="py-3 text-end fw-bold fs-6 text-dark">Rs. <?php echo number_format($pay['amount'], 0); ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center py-5 text-muted fw-semibold">No payments recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end pt-4 fw-bold text-muted text-uppercase">Total Amount Paid</td>
                    <td class="text-end pt-4 fs-4 fw-bold text-success">Rs. <?php echo number_format($total_paid, 0); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<br><br>

<?php require_once "footer.php"; ?>