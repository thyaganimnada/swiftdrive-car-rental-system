<?php
// Include database connection and header template
require_once "db/db.php";
require_once "header.php"; 

// Restrict access strictly to ADMIN role
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "ADMIN") {
    header("../index.php");
    echo "<script>window.location.href='index.php?error=access_denied';</script>";
    exit();
}

// Handle toggling user account status between ACTIVE and SUSPENDED
if (isset($_POST['toggle_status'])) {
    $u_id = (int)$_POST['user_id'];
    $current_status =$_POST['current_status'];
    $new_status = ($current_status == 'ACTIVE') ? 'SUSPENDED' : 'ACTIVE';
    
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, "UPDATE users SET account_status = ? WHERE user_id = ?")) {
        mysqli_stmt_bind_param($stmt, "si", $new_status,$u_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='admin_users.php?msg=status_updated';</script>";
        exit();
    }
}

// Handle adding a new staff or admin user
if (isset($_POST['add_staff'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $position = trim($_POST['position']);
    $role =$_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verify whether email is already registered
    $check_email = mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>window.location.href='admin_users.php?error=email_exists';</script>";
        exit();
    } else {
        // Step 1: Insert login credentials into users table
        $sql_user = "INSERT INTO users (email, password, role, account_status) VALUES (?, ?, ?, 'ACTIVE')";
        $stmt1 = mysqli_stmt_init($conn);
        
        if (mysqli_stmt_prepare($stmt1,$sql_user)) {
            mysqli_stmt_bind_param($stmt1, "sss", $email, $password,$role);
            if (mysqli_stmt_execute($stmt1)) {
                
                // Retrieve auto-generated user ID
                $new_user_id = mysqli_insert_id($conn); 
                mysqli_stmt_close($stmt1);

                // Step 2: Insert staff profile details into staff table
                $sql_staff = "INSERT INTO staff (user_id, full_name, phone, position) VALUES (?, ?, ?, ?)";
                $stmt2 = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt2,$sql_staff)) {
                    mysqli_stmt_bind_param($stmt2, "isss", $new_user_id,$full_name, $phone,$position);
                    mysqli_stmt_execute($stmt2);
                    mysqli_stmt_close($stmt2);
                }
                echo "<script>window.location.href='admin_users.php?msg=added';</script>";
                exit();
            }
        }
    }
}

// Retrieve list of system administrators and staff members
$sql = "SELECT s.*, u.email, u.role, u.account_status, u.created_at 
        FROM staff s 
        JOIN users u ON s.user_id = u.user_id 
        WHERE u.role IN ('ADMIN', 'STAFF') 
        ORDER BY u.created_at DESC";
$staff_members = mysqli_query($conn,$sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">System Users</h2>
        <p class="text-muted small mb-0">Manage Administrators and Staff members</p>
    </div>
    <!-- Button to open add user modal -->
    <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal" style="border-radius: 10px;">
        <i class="fa-solid fa-user-plus me-2"></i>Add New User
    </button>
</div>

<!-- Alerts -->
<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>New staff member added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif($_GET['msg'] == 'status_updated'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="fa-solid fa-power-off me-2"></i>Account status updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($_GET['error']) &&$_GET['error'] == 'email_exists'): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>This Email Address is already registered! Please use a different email.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="modern-card p-4 bg-white rounded-4 shadow-sm border" style="border-color:#f1f5f9;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Name & Position</th>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Contact Info</th>
                    <th class="text-uppercase small fw-bold text-muted pb-3">System Role</th>
                    <th class="text-uppercase small fw-bold text-muted pb-3">Status</th>
                    <th class="text-center text-uppercase small fw-bold text-muted pb-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($staff_members && mysqli_num_rows($staff_members) > 0): ?>
                    <?php while($s = mysqli_fetch_assoc($staff_members)): ?>
                    <tr style="border-bottom: 1px dashed #e2e8f0;">
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:40px; height:40px;">
                                    <?php echo strtoupper(substr($s['full_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($s['full_name']); ?></div>
                                    <small class="text-muted"><i class="fa-solid fa-briefcase me-1"></i> <?php echo htmlspecialchars($s['position']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="small fw-semibold text-dark"><i class="fa-solid fa-envelope text-primary me-2"></i><?php echo htmlspecialchars($s['email']); ?></div>
                            <div class="small text-muted"><i class="fa-solid fa-phone me-2"></i><?php echo htmlspecialchars($s['phone']); ?></div>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-<?php echo ($s['role']=='ADMIN')?'dark':'info text-dark'; ?> px-3 py-1 rounded-pill border">
                                <?php echo htmlspecialchars($s['role']); ?>
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-<?php echo ($s['account_status']=='ACTIVE')?'success':'danger'; ?> bg-opacity-25 text-<?php echo ($s['account_status']=='ACTIVE')?'success':'danger'; ?> border border-<?php echo ($s['account_status']=='ACTIVE')?'success':'danger'; ?>-subtle px-3 py-1 rounded-pill shadow-sm">
                                <i class="fa-solid <?php echo ($s['account_status']=='ACTIVE')?'fa-check':'fa-ban'; ?> me-1"></i> <?php echo htmlspecialchars($s['account_status']); ?>
                            </span>
                        </td>
                        <td class="text-center py-3">
                            <form action="admin_users.php" method="POST" class="m-0">
                                <input type="hidden" name="user_id" value="<?php echo $s['user_id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $s['account_status']; ?>">
                                <?php if($s['account_status'] == 'ACTIVE'): ?>
                                    <button type="submit" name="toggle_status" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="return confirm('Suspend this user? They will not be able to login.');">Suspend</button>
                                <?php else: ?>
                                    <button type="submit" name="toggle_status" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3" onclick="return confirm('Activate this user?');">Activate</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted opacity-50 mb-3"><i class="fa-solid fa-users fa-3x"></i></div>
                            <h5 class="fw-bold text-dark">No Staff Members Found</h5>
                            <p class="text-muted small">Click the 'Add New User' button to create an admin or staff account.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal form for adding staff or admin user -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
      <div class="modal-header bg-dark text-white p-4" style="border-radius: 15px 15px 0 0;">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-shield me-2 text-primary"></i>Add New System User</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <!-- Submission form targeting current script -->
      <form action="admin_users.php" method="POST">
          <div class="modal-body p-4 p-md-5">
              <div class="row g-4">
                  <!-- Full Name -->
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                      <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0" placeholder="e.g. Kamal Perera" required style="border-radius: 10px;">
                  </div>
                  <!-- Email -->
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-primary text-uppercase">Email Address (Login ID)</label>
                      <input type="email" name="email" class="form-control form-control-lg border-primary" placeholder="e.g. kamal@swiftdrive.com" required style="border-radius: 10px;">
                  </div>
                  <!-- Phone -->
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-muted text-uppercase">Phone Number</label>
                      <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" placeholder="e.g. 0712345678" required style="border-radius: 10px;">
                  </div>
                  <!-- Job Position -->
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-muted text-uppercase">Job Position</label>
                      <input type="text" name="position" class="form-control form-control-lg bg-light border-0" placeholder="e.g. Fleet Manager" required style="border-radius: 10px;">
                  </div>
                  <!-- Role -->
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-muted text-uppercase">System Role</label>
                      <select name="role" class="form-select form-select-lg bg-light border-0" required style="border-radius: 10px;">
                          <option value="STAFF">STAFF (Limited Access)</option>
                          <option value="ADMIN">ADMIN (Full Access)</option>
                      </select>
                  </div>
                  <!-- Password -->
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-primary text-uppercase">Temporary Password</label>
                      <input type="password" name="password" class="form-control form-control-lg border-primary" placeholder="Create a password" required style="border-radius: 10px;">
                  </div>
              </div>
          </div>
          <div class="modal-footer p-4 border-top-0 bg-light" style="border-radius: 0 0 15px 15px;">
              <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
              
              <!-- Form submit button -->
              <button type="submit" name="add_staff" class="btn btn-dark px-5 py-2 fw-bold shadow" style="border-radius: 10px;">
                  <i class="fa-solid fa-check me-2"></i> Create Account
              </button>
          </div>
      </form>

    </div>
  </div>
</div>

<?php require_once "footer.php"; ?>