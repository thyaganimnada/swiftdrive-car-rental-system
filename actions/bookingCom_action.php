<?php
// Start session and include database connection
session_start ();
require_once "db/db.php";

// Verify user authentication and booking ID parameter
if (!isset($_SESSION["userid"]) || !isset($_GET['booking_id'])) {
    header("location: index.php");
    exit();
}

// Sanitize booking ID and user ID
$booking_id = (int)$_GET['booking_id'];
$user_id = $_SESSION["userid"];

// Query booking confirmation details joined with vehicles and payments
$sql = "SELECT b.*, v.name as vehicle_name, v.image, p.transaction_ref, p.amount as deposit_paid 
        FROM bookings b 
        JOIN vehicles v ON b.vehicle_id = v.id 
        LEFT JOIN payments p ON b.id = p.booking_id 
        WHERE b.id = ? AND b.user_id = ?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

// Redirect to dashboard if booking does not exist or is not confirmed
if (!$booking || $booking['status'] !== 'Confirmed') {
    header("location: dashboard.php");
    exit();
}

// Calculate rental balances and formatted reference number
$rental_amount = $booking['total_price'];
$balance_to_pay = $rental_amount;
$booking_ref = "CRB-2026-" . str_pad($booking['id'], 5, '0', STR_PAD_LEFT);
