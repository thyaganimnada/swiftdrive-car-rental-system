<?php
// Include database connection and header template
require_once "db/db.php";
require_once "header.php"; 

// Handle booking status update and final balance payment processing
if (isset($_POST['update_status'])) {
    $b_id = (int)$_POST['booking_id'];
    $new_status = $_POST['status'];
    
    // Automatically record remaining balance payment when booking status is marked as Completed
    if ($new_status === 'Completed') {
        
        // Retrieve total price of booking
        $b_query = mysqli_query($conn, "SELECT total_price FROM bookings WHERE id = $b_id");
        $b_data = mysqli_fetch_assoc($b_query);
        $total_price = $b_data['total_price'];

        // Retrieve total deposit amount paid so far
        $p_query = mysqli_query($conn, "SELECT SUM(amount) as paid FROM payments WHERE booking_id = $b_id AND payment_status = 'Completed'");
        $p_data = mysqli_fetch_assoc($p_query);
        $paid_amount = $p_data['paid'] ? $p_data['paid'] : 0;

        // Calculate outstanding balance payment amount
        $balance = $total_price - $paid_amount;

        // Insert final balance payment record if balance exists
        if ($balance > 0) {
            $txn_ref = "TXN-FIN-" . strtoupper(substr(uniqid(), -5));
            
            $pay_sql = "INSERT INTO payments (booking_id, payment_type, amount, payment_method, transaction_ref, payment_status) VALUES (?, 'Final Balance', ?, 'Cash', ?, 'Completed')";
            $p_stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($p_stmt, $pay_sql)) {
                mysqli_stmt_bind_param($p_stmt, "ids", $b_id, $balance, $txn_ref);
                mysqli_stmt_execute($p_stmt);
                mysqli_stmt_close($p_stmt);
            }
        }
    }

    // Update booking status in database using prepared statement
    $sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $new_status, $b_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='bookings.php?msg=updated';</script>";
    }
}

// Fetch all bookings joined with vehicles and customer or staff full names
$sql_bookings = "SELECT b.*, v.name as v_name, 
                 COALESCE(c.full_name, s.full_name, u.email) as full_name 
                 FROM bookings b 
                 JOIN vehicles v ON b.vehicle_id = v.id 
                 JOIN users u ON b.user_id = u.user_id 
                 LEFT JOIN customers c ON u.user_id = c.user_id 
                 LEFT JOIN staff s ON u.user_id = s.user_id 
                 ORDER BY b.created_at DESC";
$bookings = mysqli_query($conn, $sql_bookings);

if (!$bookings) {
    // Terminate script with query error details if failed
    die("Database Query Error: " . mysqli_error($conn));
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Bookings</h2>
</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
    <div class="alert alert-success"><i class="fa-solid fa-circle-check me-2"></i>Booking status updated successfully.</div>
<?php endif; ?>

<div class="modern-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ref ID</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Dates</th>
                    <th>Total Value</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($b = mysqli_fetch_assoc($bookings)): 
                    // Determine status badge color scheme
                    $badge = 'warning';
                    if($b['status'] == 'Confirmed') $badge = 'primary';
                    if($b['status'] == 'Active Rental') $badge = 'info';
                    if($b['status'] == 'Completed') $badge = 'success';
                    if($b['status'] == 'Cancelled') $badge = 'danger';
                ?>
                <tr>
                    <td class="fw-bold text-dark">#CRB-<?php echo str_pad($b['id'], 4, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo htmlspecialchars($b['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['v_name']); ?></td>
                    <td class="small">
                        <div class="text-success"><i class="fa-solid fa-arrow-right"></i> <?php echo $b['pickup_date']; ?></div>
                        <div class="text-danger"><i class="fa-solid fa-arrow-left"></i> <?php echo $b['return_date']; ?></div>
                    </td>
                    <td class="fw-bold">Rs. <?php echo number_format($b['total_price'], 0); ?></td>
                    <td><span class="badge bg-<?php echo $badge; ?> px-2 py-1"><?php echo $b['status']; ?></span></td>
                    <td>
                        <?php if($b['status'] != 'Completed' && $b['status'] != 'Cancelled'): ?>
                        <form action="bookings.php" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                            <select name="status" class="form-select form-select-sm" style="width:140px;">
                                <option <?php if($b['status']=='Pending Payment') echo 'selected';?>>Pending Payment</option>
                                <option <?php if($b['status']=='Confirmed') echo 'selected';?>>Confirmed</option>
                                <option <?php if($b['status']=='Active Rental') echo 'selected';?>>Active Rental</option>
                                <option <?php if($b['status']=='Completed') echo 'selected';?>>Completed</option>
                                <option <?php if($b['status']=='Cancelled') echo 'selected';?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-dark">Save</button>
                        </form>
                        <?php else: ?>
                            <button class="btn btn-sm btn-light border w-100" disabled>
                                <?php echo ($b['status'] == 'Completed') ? '<i class="fa-solid fa-check text-success"></i> Finished' : '<i class="fa-solid fa-xmark text-danger"></i> Cancelled'; ?>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>