<?php
// Start session and check authentication
session_start();
if (!isset($_SESSION["userid"])){
    header("location: login.php");
    exit();
}

// Include database connection
require_once 'db/db.php';
$user_id = $_SESSION["userid"];

// Handle customer booking cancellation and refund status update
if (isset($_POST['cancel_booking'])) {
    $b_id = (int)$_POST['booking_id'];
    mysqli_query($conn, "UPDATE bookings SET status = 'Cancelled' WHERE id = $b_id AND user_id = $user_id");
    mysqli_query($conn, "UPDATE payments SET payment_status = 'Refunded' WHERE booking_id = $b_id");
    echo "<script>window.location.href='my_bookings.php?msg=cancelled';</script>";
    exit();
}

// Fetch customer or staff profile information
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

// Retrieve user bookings history joined with vehicle details
$sql_bookings = "SELECT b.*, v.name as vehicle_name, v.image as vehicle_image FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id WHERE b.user_id = $user_id ORDER BY b.created_at DESC";
$bookings = mysqli_query($conn, $sql_bookings);