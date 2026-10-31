<?php
// Start session and verify user authentication
session_start();
if (!isset($_SESSION["userid"])) {
    header("location: login.php");
    exit();
}

// Include database connection
require_once 'db/db.php';
$user_id = $_SESSION["userid"];

// Validate booking ID parameter in GET request
if (!isset($_GET['id'])) {
    header("location: dashboard.php");
    exit();
}
$booking_id = (int)$_GET['id'];

// Retrieve customer profile details
$user_sql = "SELECT u.email, u.role, 
             COALESCE(c.full_name, s.full_name) AS full_name, 
             COALESCE(c.phone, s.phone) AS phone
             FROM users u 
             LEFT JOIN customers c ON u.user_id = c.user_id 
             LEFT JOIN staff s ON u.user_id = s.user_id 
             WHERE u.user_id = ?";
             
$u_stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($u_stmt, $user_sql)) {
    mysqli_stmt_bind_param($u_stmt, "i", $user_id);
    mysqli_stmt_execute($u_stmt);
    $customer = mysqli_fetch_assoc(mysqli_stmt_get_result($u_stmt));
} else {
    die("Database error: " . mysqli_error($conn));
}

// Retrieve booking details joined with vehicle information ensuring user ownership
$sql = "SELECT b.*, v.name as vehicle_name, v.image as vehicle_image, v.daily_rate 
        FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id 
        WHERE b.id = $booking_id AND b.user_id = $user_id";
$result = mysqli_query($conn, $sql);
$b = mysqli_fetch_assoc($result);

// Prevent unauthorized access if booking does not match user
if (!$b) {
    echo "<h3>Booking not found or unauthorized access!</h3>";
    exit();
}