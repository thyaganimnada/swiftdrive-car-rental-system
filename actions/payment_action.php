<?php 
// Start session and include database connection
session_start();
require_once "db/db.php";

// Verify user authentication and booking ID parameter
if (!isset($_SESSION["userid"]) || !isset($_GET['booking_id'])) {
    header("location: index.php");
    exit();
}

// Retrieve sanitized booking ID and user ID
$booking_id = (int)$_GET['booking_id'];
$user_id = $_SESSION["userid"];

// Query booking details and vehicle security deposit from database
$sql = "SELECT b.*, v.name as vehicle_name, v.deposit as security_deposit, v.image 
        FROM bookings b 
        JOIN vehicles v ON b.vehicle_id = v.id 
        WHERE b.id = ? AND b.user_id = ?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

// Redirect to bookings list if record not found
if (!$booking) {
    header("location: my_bookings.php");
    exit();
}

// Calculate rental amount, security deposit, and total payable
$rental_amount = $booking['total_price'];
$security_deposit = $booking['security_deposit'];
$total_payable = $rental_amount + $security_deposit;