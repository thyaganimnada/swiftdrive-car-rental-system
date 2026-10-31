<?php
// Include header template and authorize admin or staff access
include_once("header.php");
if ($_SESSION["role"] !== "ADMIN" && $_SESSION["role"] !== "STAFF") {
    header("location: ../index.php?error=you are not  admin");
    exit();
}

// Include database connection
include_once 'db/db.php';

// Initialize overview metrics
$total_vehicles = 0;
$active_bookings = 0;
$total_revenue = 0;
$pending_inquiries = 0;

// Query total registered vehicles count
$res1 = mysqli_query($conn, "SELECT COUNT(id) AS count FROM vehicles");
if ($row = mysqli_fetch_assoc($res1)) $total_vehicles = $row['count'];

// Query active bookings count
$res2 = mysqli_query($conn, "SELECT COUNT(id) AS count FROM bookings WHERE status IN ('Confirmed', 'Active Rental', 'Picked Up')");
if ($row = mysqli_fetch_assoc($res2)) $active_bookings = $row['count'];

// Query total completed revenue
$res3 = mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status = 'Completed'");
if ($row = mysqli_fetch_assoc($res3)) $total_revenue = $row['total'] ? $row['total'] : 0;

// Query total pending inquiries count
$res4 = mysqli_query($conn, "SELECT COUNT(id) AS count FROM inquiries");
if ($row = mysqli_fetch_assoc($res4)) $pending_inquiries = $row['count'];

// Retrieve top 5 recent bookings joined with vehicles
$recent_bookings_sql = "SELECT b.id, b.pickup_date, b.status, b.total_price, v.name as vehicle_name 
                        FROM bookings b 
                        JOIN vehicles v ON b.vehicle_id = v.id 
                        ORDER BY b.created_at DESC LIMIT 5";
$recent_bookings = mysqli_query($conn, $recent_bookings_sql);
?>

        <div class="row g-3 g-xl-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon icon-blue"><i class="fa-solid fa-car"></i></div>
                    <div class="stat-details">
                        <h6>Total Vehicles</h6>
                        <h3><?php echo $total_vehicles; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fa-solid fa-calendar-check"></i></div>
                    <div class="stat-details">
                        <h6>Active Bookings</h6>
                        <h3><?php echo $active_bookings; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon icon-purple"><i class="fa-solid fa-wallet"></i></div>
                    <div class="stat-details">
                        <h6>Total Revenue</h6>
                        <h3>Rs. <?php echo number_format($total_revenue, 0); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon icon-orange"><i class="fa-regular fa-envelope"></i></div>
                    <div class="stat-details">
                        <h6>Inquiries</h6>
                        <h3><?php echo $pending_inquiries; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="modern-table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 fs-5">Recent Bookings</h5>
                <a href="bookings.php" class="btn btn-sm btn-outline-primary fw-semibold">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Vehicle</th>
                            <th>Pickup Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recent_bookings) > 0): ?>
                            <?php while ($b = mysqli_fetch_assoc($recent_bookings)):
                                $statusClass = 'badge-soft-warning';
                                if (in_array($b['status'], ['Confirmed', 'Active Rental'])) $statusClass = 'badge-soft-primary';
                                if ($b['status'] == 'Completed') $statusClass = 'badge-soft-success';
                            ?>
                                <tr>
                                    <td class="fw-bold text-dark">#CRB-<?php echo str_pad($b['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($b['vehicle_name']); ?></td>
                                    <td><?php echo $b['pickup_date']; ?></td>
                                    <td class="fw-bold">Rs. <?php echo number_format($b['total_price'], 0); ?></td>
                                    <td><span class="badge <?php echo $statusClass; ?> px-2 py-1 rounded-pill"><?php echo $b['status']; ?></span></td>
                                    <td>
                                        <a href="view_booking.php?id=<?php echo $b['id']; ?>"><button class="btn btn-sm btn-light border"><i class="fa-solid fa-eye text-muted"></i></button></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No recent bookings found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include_once "footer.php"; ?>