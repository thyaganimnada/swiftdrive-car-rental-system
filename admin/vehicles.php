<?php
// Include database connection and header template
include_once "db/db.php";
include_once "header.php"; 

// Handle vehicle deletion by ID
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
       
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, "DELETE FROM vehicles WHERE id = ?")) {
        mysqli_stmt_bind_param($stmt, "i", $del_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='vehicles.php?msg=deleted';</script>";
    }
}

// Handle adding a new vehicle to fleet
if (isset($_POST['add_vehicle'])) {
    
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $sub_category = trim($_POST['sub_category']);
    $year = (int)$_POST['year'];
    $seats = (int)$_POST['seats'];
    $transmission = $_POST['transmission'];
    $fuel = $_POST['fuel'];
    $mileage = trim($_POST['mileage']);
    $luggage_capacity = (int)$_POST['luggage_capacity'];
    $daily_rate = (float)$_POST['daily_rate'];
    $weekly_rate = (float)$_POST['weekly_rate'];
    $deposit = (float)$_POST['deposit'];
    $features = trim($_POST['features']);
    $description = trim($_POST['description']);
    
    $image_saved_path = "assets/vehicles/";

    // Handle vehicle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        
        // Extract file extension and validate against allowed formats
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            // Generate unique filename to avoid overwriting existing images
            $new_file_name = uniqid("veh_") . "." . $file_ext;
            
            // Define destination folder path
            $upload_dir = "../assets/images/vehicles/";
            
            // Create target folder if not existing
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Move uploaded image file to target directory
            $destination = $upload_dir . $new_file_name;
            if (move_uploaded_file($file_tmp, $destination)) {
                // Store relative image path for database record
                $image_saved_path = "assets/images/vehicles/" . $new_file_name;
            }
        }
    } else {
        // Assign fallback default vehicle image
        $image_saved_path = "assets/images/default-car.jpg";
    }

    // Insert new vehicle record into database using prepared statement
    $sql = "INSERT INTO vehicles (name, category, sub_category, image, year, seats, transmission, fuel, mileage, luggage_capacity, daily_rate, weekly_rate, deposit, features, description, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Available')";
            
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql)) {
        // Bind 15 parameters to prepared statement
        mysqli_stmt_bind_param($stmt, "ssssiisssiiddds", 
            $name, $category, $sub_category, $image_saved_path, $year, $seats, $transmission, $fuel, $mileage, $luggage_capacity, $daily_rate, $weekly_rate, $deposit, $features, $description
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='vehicles.php?msg=added';</script>";
    }
}

// Retrieve list of all vehicles in descending order
$vehicles = mysqli_query($conn, "SELECT * FROM vehicles ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Vehicles</h2>
    <!-- Button to trigger Add Vehicle Modal -->
    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
        <i class="fa-solid fa-plus me-2"></i>Add New Vehicle
    </button>
</div>

<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'added'): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check me-2"></i>Vehicle added and image uploaded successfully.</div>
    <?php elseif($_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-trash me-2"></i>Vehicle deleted successfully.</div>
    <?php endif; ?>
<?php endif; ?>

<!-- Vehicles Table -->
<div class="modern-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Vehicle Details</th>
                    <th>Specs</th>
                    <th>Pricing (Daily)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($v = mysqli_fetch_assoc($vehicles)): ?>
                <tr>
                    <!-- Display vehicle thumbnail image with relative path -->
                    <td><img src="../<?php echo htmlspecialchars($v['image']); ?>" width="80" height="50" style="object-fit: cover; border-radius: 8px;" onerror="this.src='../assets/images/default-car.jpg'"></td>
                    <td>
                        <div class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($v['name']); ?></div>
                        <small class="text-muted"><?php echo htmlspecialchars($v['category']); ?> - <?php echo htmlspecialchars($v['sub_category']); ?></small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border me-1" title="Transmission"><i class="fa-solid fa-gear text-primary"></i> <?php echo $v['transmission']; ?></span>
                        <span class="badge bg-light text-dark border me-1" title="Fuel"><i class="fa-solid fa-gas-pump text-primary"></i> <?php echo $v['fuel']; ?></span>
                        <span class="badge bg-light text-dark border" title="Seats"><i class="fa-solid fa-users text-primary"></i> <?php echo $v['seats']; ?></span>
                    </td>
                    <td class="fw-bold text-primary">Rs. <?php echo number_format($v['daily_rate'], 0); ?></td>
                    <td>
                        <span class="badge bg-<?php echo ($v['status']=='Available')?'success':'warning'; ?> rounded-pill px-3">
                            <?php echo $v['status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="vehicles.php?delete_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this vehicle?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Dialog for adding a new vehicle -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 15px;">
      
      <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
        <h5 class="modal-title fw-bold"><i class="fa-solid fa-car me-2"></i>Add New Vehicle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form with multipart enctype for handling file uploads -->
      <form action="vehicles.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body p-4">
              
              <h6 class="fw-bold text-uppercase small text-muted mb-3 border-bottom pb-2">Basic Information</h6>
              <div class="row g-3 mb-4">
                  <div class="col-md-6">
                      <label class="form-label small fw-bold">Vehicle Name (e.g. Toyota Axio)</label>
                      <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label small fw-bold">Upload Image (.jpg, .png)</label>
                      <input type="file" name="image" class="form-control" accept="image/*" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label small fw-bold">Category</label>
                      <select name="category" class="form-select" required>
                          <option value="Economy">Economy</option>
                          <option value="SUV">SUV</option>
                          <option value="Van">Van / Minivan</option>
                          <option value="Luxury">Luxury</option>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label small fw-bold">Sub Category (e.g. Premium SUV)</label>
                      <input type="text" name="sub_category" class="form-control" required>
                  </div>
              </div>

              <h6 class="fw-bold text-uppercase small text-muted mb-3 border-bottom pb-2">Specifications</h6>
              <div class="row g-3 mb-4">
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Manufacture Year</label>
                      <input type="number" name="year" class="form-control" placeholder="2020" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">No. of Seats</label>
                      <input type="number" name="seats" class="form-control" placeholder="4" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Luggage Capacity (Bags)</label>
                      <input type="number" name="luggage_capacity" class="form-control" placeholder="2" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Transmission</label>
                      <select name="transmission" class="form-select" required>
                          <option value="Automatic">Automatic</option>
                          <option value="Manual">Manual</option>
                      </select>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Fuel Type</label>
                      <select name="fuel" class="form-select" required>
                          <option value="Petrol">Petrol</option>
                          <option value="Diesel">Diesel</option>
                          <option value="Hybrid">Hybrid</option>
                          <option value="Electric">Electric</option>
                      </select>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Fuel Efficiency / Mileage</label>
                      <input type="text" name="mileage" class="form-control" placeholder="e.g. 15 km/l" required>
                  </div>
              </div>

              <h6 class="fw-bold text-uppercase small text-muted mb-3 border-bottom pb-2">Pricing & Features</h6>
              <div class="row g-3">
                  <div class="col-md-4">
                      <label class="form-label small fw-bold text-primary">Daily Rate (Rs.)</label>
                      <input type="number" name="daily_rate" class="form-control border-primary" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Weekly Rate (Rs.)</label>
                      <input type="number" name="weekly_rate" class="form-control" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label small fw-bold">Security Deposit (Rs.)</label>
                      <input type="number" name="deposit" class="form-control" required>
                  </div>
                  <div class="col-12">
                      <label class="form-label small fw-bold">Key Features (Comma Separated)</label>
                      <input type="text" name="features" class="form-control" placeholder="e.g. AC, Bluetooth, Reverse Camera, Push Start" required>
                  </div>
                  <div class="col-12">
                      <label class="form-label small fw-bold">Full Description</label>
                      <textarea name="description" class="form-control" rows="3" placeholder="Write a short description about this vehicle..." required></textarea>
                  </div>
              </div>

          </div>
          
          <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" name="add_vehicle" class="btn btn-primary px-4 fw-bold">Save Vehicle to Database</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once "footer.php"; ?>