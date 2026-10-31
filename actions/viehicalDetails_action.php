<?php 
// Include database connection
require_once "db/db.php";

// Validate vehicle ID from GET parameter
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    header("location: ../vehicles.php");
    exit();
}

// Retrieve vehicle details from database using prepared statement
$sql = "SELECT * FROM vehicles WHERE id = ?;";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../vehicles.php?error=stmtfailed");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultData = mysqli_stmt_get_result($stmt);

// Assign retrieved vehicle record or redirect if not found
if ($row = mysqli_fetch_assoc($resultData)) {
    $vehicle = $row;
} else {
    header("location: ../vehicles.php?error=vehiclenotfound");
    exit();
}
mysqli_stmt_close($stmt);

// Convert comma-separated features string into an array
$featuresString = !empty($vehicle['features']) ? $vehicle['features'] : 'Automatic, 5 Seats, Air Conditioning, Hybrid';
$featuresList = explode(',', $featuresString);
