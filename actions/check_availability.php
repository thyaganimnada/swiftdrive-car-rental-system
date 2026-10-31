<?php
// Include database connection
require_once "db/db.php";

// Check if AJAX availability request contains required parameters
if (isset($_POST['vehicle_id']) && isset($_POST['pickup_date']) && isset($_POST['return_date'])) {
    $vid = (int)$_POST['vehicle_id'];
    $p_date = $_POST['pickup_date'];
    $r_date = $_POST['return_date'];

    // Check for conflicting active bookings within the requested date range
    $sql = "SELECT id FROM bookings 
            WHERE vehicle_id = ? 
            AND status NOT IN ('Cancelled', 'Completed', 'Returned') 
            AND (pickup_date <= ? AND return_date >= ?)";
            
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {

        mysqli_stmt_bind_param($stmt, "iss", $vid, $r_date, $p_date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $conflicts = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        
        // Return JSON response indicating availability status
        if ($conflicts > 0) {
            echo json_encode(["available" => false, "message" => "Sorry! This vehicle is already booked for these dates."]);
        } else {
            echo json_encode(["available" => true, "message" => "Dates are available! You can proceed."]);
        }
    }
}
?>