<?php
// Start session and verify user authentication status
session_start();
require_once "db/db.php";
if (!isset($_SESSION["userid"])) {
    header("location: login.php?error=loginrequired");
    exit();
}

// Ensure vehicle ID parameter is present in URL
if (!isset($_GET['vehicle_id'])) {
    header("location: vehicles.php");
    exit();
}

// Sanitize vehicle ID from GET request
$vehicle_id = (int)$_GET['vehicle_id'];

// Query vehicle information from database using prepared statement
$sql = "SELECT * FROM vehicles WHERE id = ?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $vehicle_id);
mysqli_stmt_execute($stmt);
$vehicle = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Redirect if requested vehicle does not exist
if (!$vehicle) {
    header("location: vehicles.php");
    exit();
}

// Set default pickup and return dates if not provided
$pre_pickup = isset($_GET['pickup']) ? $_GET['pickup'] : date('Y-m-d', strtotime('+1 day'));
$pre_return = isset($_GET['return']) ? $_GET['return'] : date('Y-m-d', strtotime('+4 days'));

// Fetch reserved date ranges for this vehicle to disable in calendar
$v_id = (int)$vehicle['id'];
$booked_query = mysqli_query($conn, "SELECT pickup_date, return_date FROM bookings WHERE vehicle_id = $v_id AND status NOT IN ('Cancelled', 'Completed')");

// Build array of disabled date ranges for datepicker
$disabled_dates = [];
if($booked_query){
    while($row = mysqli_fetch_assoc($booked_query)) {
        $disabled_dates[] = [
            "from" => $row['pickup_date'],
            "to" => $row['return_date']
        ];
    }
}
// Encode disabled date ranges into JSON for frontend calendar
$disabled_dates_json = json_encode($disabled_dates);