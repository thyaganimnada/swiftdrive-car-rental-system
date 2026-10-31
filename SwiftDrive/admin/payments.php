<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Retrieve payment transactions joined with bookings, users, and customer profiles
$sql = "SELECT p.*, b.pickup_date, c.full_name, u.email 
        FROM payments p 
        JOIN bookings b ON p.booking_id = b.id 
        JOIN users u ON b.user_id = u.user_id 
        JOIN customers c ON u.user_id = c.user_id 
        ORDER BY p.created_at DESC";
        
$payments = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Payment Transactions</h2>

</div>

<div class="modern-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Transaction Ref</th>
                    <th>Customer Name</th>
                    <th>Booking ID</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if($payments && mysqli_num_rows($payments) > 0): ?>
                    <?php while($p = mysqli_fetch_assoc($payments)): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($p['transaction_ref']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($p['full_name']); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($p['email']); ?></small>
                        </td>
                        <td class="fw-bold text-dark">#CRB-<?php echo str_pad($p['booking_id'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($p['payment_type']); ?></td>
                        <td class="fw-bold">Rs. <?php echo number_format($p['amount'], 0); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($p['payment_status']=='Completed')?'success':'warning'; ?> px-3 py-1 rounded-pill">
                                <?php echo htmlspecialchars($p['payment_status']); ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?php echo date('Y M d, h:i A', strtotime($p['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No payments recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>