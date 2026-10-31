<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Retrieve registered customers with booking count summary
$sql = "SELECT u.user_id, u.email, u.created_at, c.full_name, c.phone, COUNT(b.id) as total_bookings 
        FROM users u 
        JOIN customers c ON u.user_id = c.user_id 
        LEFT JOIN bookings b ON u.user_id = b.user_id 
        WHERE u.role = 'CUSTOMER' 
        GROUP BY u.user_id 
        ORDER BY u.created_at DESC";
        
$customers = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Registered Customers</h2>
</div>

<div class="modern-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Customer Name</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Total Bookings</th>
                    <th>Joined Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($customers && mysqli_num_rows($customers) > 0): ?>
                    <?php while($c = mysqli_fetch_assoc($customers)): ?>
                    <tr>
                        <td class="fw-bold d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:35px; height:35px; font-size:0.8rem;">
                                <?php echo strtoupper(substr($c['full_name'], 0, 1)); ?>
                            </div>
                            <?php echo htmlspecialchars($c['full_name']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo htmlspecialchars($c['phone']); ?></td>
                        <td>
                            <span class="badge bg-dark px-3 py-1 rounded-pill"><?php echo $c['total_bookings']; ?> Bookings</span>
                        </td>
                        <td class="text-muted small"><?php echo date('Y M d', strtotime($c['created_at'])); ?></td>
                        <td>
                            <a href="view_customer.php?id=<?php echo $c['user_id']; ?>" class="btn btn-sm btn-light border"><i class="fa-solid fa-eye text-primary"></i> View</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No customers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>