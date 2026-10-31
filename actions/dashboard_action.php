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

// Handle booking cancellation and refund payment status
if (isset($_POST['cancel_booking'])) {
    $b_id = (int)$_POST['booking_id'];
    mysqli_query($conn, "UPDATE bookings SET status = 'Cancelled' WHERE id = $b_id AND user_id = $user_id");
    mysqli_query($conn, "UPDATE payments SET payment_status = 'Refunded' WHERE booking_id = $b_id");
    echo "<script>window.location.href='dashboard.php?msg=cancelled';</script>";
    exit();
}

// Retrieve customer or staff profile information by user ID
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

// Calculate total bookings and active rentals count
$bk_res = mysqli_query($conn, "SELECT status, COUNT(id) as count FROM bookings WHERE user_id = $user_id GROUP BY status");
$total_bookings = 0; $active_bookings = 0;
while($row = mysqli_fetch_assoc($bk_res)) {
    $total_bookings += $row['count'];
    if(in_array($row['status'], ['Active Rental', 'Confirmed', 'Pending Payment'])) $active_bookings += $row['count'];
}

// Calculate total amount spent on completed bookings
$spent_query = mysqli_query($conn, "SELECT SUM(p.amount) as actual_spent FROM payments p JOIN bookings b ON p.booking_id = b.id WHERE b.user_id = $user_id AND p.payment_status = 'Completed'");
$spent_data = mysqli_fetch_assoc($spent_query);
$total_spent = $spent_data['actual_spent'] ?? 0;

// Retrieve all customer bookings with associated vehicle details
$sql_bookings = "SELECT b.*, v.name as vehicle_name, v.image as vehicle_image FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id WHERE b.user_id = $user_id ORDER BY b.created_at DESC";
$bookings = mysqli_query($conn, $sql_bookings);
?>