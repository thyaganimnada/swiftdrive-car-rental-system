<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Handle marking an inquiry as Read
if (isset($_POST['mark_read'])) {
    $inq_id = (int)$_POST['inquiry_id'];
    
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, "UPDATE inquiries SET status = 'Read' WHERE id = ?")) {
        mysqli_stmt_bind_param($stmt, "i", $inq_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='inquiries.php?msg=read';</script>";
    }
}

// Retrieve all customer inquiries ordered by creation date descending
$inquiries = mysqli_query($conn, "SELECT * FROM inquiries ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Customer Inquiries</h2>
</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'read'): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>Inquiry has been marked as <strong>Read</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="modern-card p-4 bg-white rounded-4 shadow-sm border" style="border-color:#f1f5f9;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Customer Details</th>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Subject & Message</th>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Date</th>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Status</th>
                    <th class="text-center text-uppercase small fw-bold text-muted pb-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($inquiries && mysqli_num_rows($inquiries) > 0): ?>
                    <?php while($inq = mysqli_fetch_assoc($inquiries)): 
                        // Default inquiry status to New if not set
                        $status = $inq['status'] ?? 'New';
                        
                        // Highlight unread new inquiries
                        $row_bg = ($status == 'New') ? 'bg-primary bg-opacity-10' : '';
                    ?>
                    <tr class="<?php echo $row_bg; ?> border-bottom border-light">
                        <td>
                            <div class="fw-bold <?php echo ($status == 'New') ? 'text-dark' : 'text-secondary'; ?>">
                                <?php echo htmlspecialchars($inq['name']); ?>
                            </div>
                            <div class="small fw-semibold text-muted"><i class="fa-solid fa-envelope me-1"></i><?php echo htmlspecialchars($inq['email']); ?></div>
                        </td>
                        
                        <td style="max-width: 300px;">
                            <div class="fw-bold <?php echo ($status == 'New') ? 'text-primary' : 'text-dark'; ?> mb-1">
                                <?php echo htmlspecialchars($inq['subject']); ?>
                            </div>
                            <p class="text-truncate small mb-0 <?php echo ($status == 'New') ? 'fw-semibold text-dark' : 'text-muted'; ?>" title="<?php echo htmlspecialchars($inq['message']); ?>">
                                <?php echo htmlspecialchars($inq['message']); ?>
                            </p>
                        </td>
                        
                        <td>
                            <div class="small fw-semibold <?php echo ($status == 'New') ? 'text-dark' : 'text-muted'; ?>">
                                <?php echo date('Y M d', strtotime($inq['created_at'])); ?><br>
                                <small><?php echo date('h:i A', strtotime($inq['created_at'])); ?></small>
                            </div>
                        </td>
                        
                        <td>
                            <?php if($status == 'New'): ?>
                                <span class="badge bg-danger rounded-pill px-3 py-1 shadow-sm"><i class="fa-solid fa-bell me-1"></i> NEW</span>
                            <?php else: ?>
                                <span class="badge bg-light text-success border border-success-subtle rounded-pill px-3 py-1"><i class="fa-solid fa-check-double me-1"></i> READ</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if($status == 'New'): ?>
                            <form action="inquiries.php" method="POST" class="m-0">
                                <input type="hidden" name="inquiry_id" value="<?php echo $inq['id']; ?>">
                                <button type="submit" name="mark_read" class="btn btn-sm btn-primary fw-bold rounded-pill px-3 shadow-sm">
                                    Mark as Read
                                </button>
                            </form>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light border fw-bold rounded-pill px-3 text-muted" disabled>
                                    Resolved
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted opacity-50 mb-3"><i class="fa-regular fa-envelope-open fa-3x"></i></div>
                            <h5 class="fw-bold text-dark">No Inquiries Found</h5>
                            <p class="text-muted small">You haven't received any messages from customers yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>