<?php
// Include database connection and header template
include_once("db/db.php");
require_once "header.php"; 

// Authorize access for both ADMIN and STAFF roles
if (!isset($_SESSION["role"]) || ($_SESSION["role"] !== "ADMIN" && $_SESSION["role"] !== "STAFF")) {
    echo "<script>window.location.href='index.php?error=access_denied';</script>";
    exit();
}

// Handle approving a customer review
if (isset($_GET['approve'])) {
    $r_id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE reviews SET status = 'Approved' WHERE id = $r_id");
    echo "<script>window.location.href='reviews.php?msg=approved';</script>";
    exit();
}

// Handle hiding a customer review by setting status to Pending
if (isset($_GET['hide'])) {
    $r_id = (int)$_GET['hide'];
    mysqli_query($conn, "UPDATE reviews SET status = 'Pending' WHERE id = $r_id");
    echo "<script>window.location.href='reviews.php?msg=hidden';</script>";
    exit();
}

// Handle deleting a customer review from database
if (isset($_GET['delete'])) {
    $r_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM reviews WHERE id = $r_id");
    echo "<script>window.location.href='reviews.php?msg=deleted';</script>";
    exit();
}

// Retrieve all customer reviews joined with vehicle names
$sql = "SELECT r.*, v.name as vehicle_name 
        FROM reviews r 
        LEFT JOIN vehicles v ON r.vehicle_id = v.id 
        ORDER BY r.created_at DESC";
$reviews = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="../css/admin.css">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Customer Reviews</h2>
        <p class="text-muted small mb-0">Manage and approve ratings given by customers.</p>
    </div>
</div>

<!-- Alerts -->
<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'approved'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>Review Approved and will be visible on the site. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['msg'] == 'hidden'): ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-eye-slash me-2"></i>Review Hidden (Status changed to Pending). <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm"><i class="fa-solid fa-trash me-2"></i>Review permanently deleted. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
<?php endif; ?>

<div class="premium-card">
    <div class="table-responsive">
        <table class="table table-borderless table-modern mb-0">
            <thead class="border-bottom text-muted small text-uppercase fw-bold">
                <tr>
                    <th class="pb-3">Customer Info</th>
                    <th class="pb-3">Vehicle / Booking</th>
                    <th class="pb-3">Rating & Comment</th>
                    <th class="pb-3 text-center">Status</th>
                    <th class="pb-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($reviews) > 0): ?>
                    <?php while($r = mysqli_fetch_assoc($reviews)): ?>
                    <tr class="border-bottom border-light">
                        <td class="py-3">
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($r['customer_name']); ?></div>
                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> <?php echo date('M d, Y', strtotime($r['created_at'])); ?></small>
                        </td>
                        <td class="py-3">
                            <div class="fw-bold text-primary"><?php echo htmlspecialchars($r['vehicle_name']); ?></div>
                            <span class="badge bg-light text-dark border small">Booking #<?php echo $r['booking_id']; ?></span>
                        </td>
                        <td class="py-3">
                            <div class="star-rating mb-1">
                                <?php 
                                for($i=1; $i<=5; $i++){
                                    if($i <= $r['rating']) echo '<i class="fa-solid fa-star"></i>';
                                    else echo '<i class="fa-regular fa-star text-muted opacity-25"></i>';
                                }
                                ?>
                            </div>
                            <div class="review-comment fst-italic">
                                <?php echo !empty($r['comment']) ? '"'.htmlspecialchars($r['comment']).'"' : '<span class="text-muted opacity-50">No comment provided</span>'; ?>
                            </div>
                        </td>
                        <td class="py-3 text-center">
                            <?php if($r['status'] == 'Approved'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-check me-1"></i> Approved</span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-3 py-1 rounded-pill"><i class="fa-solid fa-hourglass-half me-1"></i> Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <?php if($r['status'] == 'Pending'): ?>
                                    <!-- Approve Button -->
                                    <a href="reviews.php?approve=<?php echo $r['id']; ?>" class="btn btn-sm btn-success fw-bold rounded-pill px-3 shadow-sm" title="Approve Review">Approve</a>
                                <?php else: ?>
                                    <!-- Hide Button -->
                                    <a href="reviews.php?hide=<?php echo $r['id']; ?>" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm" title="Hide from Public">Hide</a>
                                <?php endif; ?>
                                
                                <!-- Delete Button -->
                                <a href="reviews.php?delete=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this review?');" title="Delete Review">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted opacity-25 mb-3"><i class="fa-regular fa-star fa-3x"></i></div>
                            <h5 class="fw-bold text-dark">No Reviews Found</h5>
                            <p class="text-muted small">Customers haven't submitted any reviews yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>