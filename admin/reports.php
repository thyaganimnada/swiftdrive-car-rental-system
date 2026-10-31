<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Restrict access strictly to ADMIN role
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "ADMIN") {
    header("../index.php");
    echo "<script>window.location.href='index.php?error=access_denied';</script>";
    exit();
}

// Calculate total completed revenue from payments table
$rev_res = mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status = 'Completed'");
$total_revenue = mysqli_fetch_assoc($rev_res)['total'] ?? 0;

// Calculate booking statistics grouped by status
$bk_res = mysqli_query($conn, "SELECT status, COUNT(id) as count FROM bookings GROUP BY status");
$completed_bk = 0; $cancelled_bk = 0; $active_bk = 0;
while($row = mysqli_fetch_assoc($bk_res)) {
    if($row['status'] == 'Completed') $completed_bk = $row['count'];
    if($row['status'] == 'Cancelled') $cancelled_bk = $row['count'];
    if(in_array($row['status'], ['Active Rental', 'Confirmed'])) $active_bk += $row['count'];
}

// Calculate vehicle fleet distribution grouped by category
$cat_res = mysqli_query($conn, "SELECT category, COUNT(id) as count FROM vehicles GROUP BY category");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Business Reports & Analytics</h2>
        <p class="text-muted small mb-0">Overview of system revenue, bookings, and fleet status.</p>
    </div>
    <button class="btn-print" onclick="window.print()">
        <i class="fa-solid fa-print me-2"></i> Print Report
    </button>
</div>

<div class="row g-4 mb-5">
    <!-- 1. Revenue Overview -->
    <div class="col-xl-4 col-lg-5">
        <div class="revenue-widget">
            <i class="fa-solid fa-chart-line bg-icon"></i>
            <h6 class="fw-bold mb-4 opacity-75 text-uppercase" style="letter-spacing: 1px;">
                <i class="fa-solid fa-wallet me-2"></i> Total Generated Revenue
            </h6>
            <h1 class="display-4 fw-bold mb-2">
                <span class="fs-4 opacity-75">Rs.</span> <?php echo number_format($total_revenue, 0); ?>
            </h1>
            <div class="mt-auto pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                <p class="mb-0 small opacity-75 fw-semibold"><i class="fa-solid fa-circle-info me-1"></i> Includes all completed security deposits and final payments.</p>
            </div>
        </div>
    </div>
    
    <!-- 2. Booking Metrics -->
    <div class="col-xl-8 col-lg-7">
        <div class="premium-report-card">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-3">
                <i class="fa-solid fa-chart-pie text-primary me-2"></i> Booking Analytics
            </h5>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="icon-wrapper icon-success"><i class="fa-solid fa-check-double"></i></div>
                        <h2 class="text-success fw-bold mb-1"><?php echo $completed_bk; ?></h2>
                        <span class="text-muted small text-uppercase fw-bold">Completed Trips</span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="icon-wrapper icon-primary"><i class="fa-solid fa-car-on"></i></div>
                        <h2 class="text-primary fw-bold mb-1"><?php echo $active_bk; ?></h2>
                        <span class="text-muted small text-uppercase fw-bold">Active & Confirmed</span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="icon-wrapper icon-danger"><i class="fa-solid fa-calendar-xmark"></i></div>
                        <h2 class="text-danger fw-bold mb-1"><?php echo $cancelled_bk; ?></h2>
                        <span class="text-muted small text-uppercase fw-bold">Cancelled Bookings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Fleet Distribution -->
<div class="premium-report-card">
    <h5 class="fw-bold mb-4 text-dark border-bottom pb-3">
        <i class="fa-solid fa-car text-primary me-2"></i> Fleet Distribution by Category
    </h5>
    
    <div class="row g-3">
        <?php if(mysqli_num_rows($cat_res) > 0): ?>
            <?php while($cat = mysqli_fetch_assoc($cat_res)): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="fleet-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="fa-solid fa-car-side small"></i>
                        </div>
                        <span class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($cat['category']); ?></span>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6 shadow-sm"><?php echo $cat['count']; ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-4 text-muted">No vehicles registered in the system yet.</div>
        <?php endif; ?>
    </div>
</div>

<br>

<?php require_once "footer.php"; ?>