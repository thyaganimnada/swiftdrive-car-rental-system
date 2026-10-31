<?php
// Start session to access logged in user data
session_start();

// Check if booking submission form was triggered via POST
if (isset($_POST["submit_booking"])) {
    // Ensure user is authenticated before creating a reservation
    if (!isset($_SESSION["userid"])) {
        header("location: login.php");
        exit();
    }

    // Include database connection
    require_once 'db/db.php';

    // Retrieve and sanitize booking form inputs
    $user_id = $_SESSION["userid"];
    $vehicle_id = (int)$_POST["vehicle_id"];
    $pickup_location = $_POST["pickup_location"];
    $return_location = $_POST["return_location"];
    $pickup_date = $_POST["pickup_date"];
    $return_date = $_POST["return_date"];
    $total_days = (int)$_POST["total_days"];
    $total_price = (float)$_POST["total_price"];
    $special_requests = $_POST["special_requests"];

    // Double-check vehicle availability to prevent conflicting date bookings
    $check_sql = "SELECT id FROM bookings WHERE vehicle_id = ? AND status NOT IN ('Cancelled', 'Completed', 'Returned') AND (pickup_date <= ? AND return_date >= ?)";
    $check_stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($check_stmt, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "iss", $vehicle_id, $return_date, $pickup_date);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    $conflicts = mysqli_stmt_num_rows($check_stmt);
    mysqli_stmt_close($check_stmt);

    // Redirect with error message if date conflict is detected
    if ($conflicts > 0) {
        header("location: ../booking.php?vehicle_id=" . $vehicle_id . "&error=alreadybooked");
        exit();
    }

    // Insert new booking record with initial Pending Payment status
    $insert_sql = "INSERT INTO bookings (user_id, vehicle_id, pickup_location, return_location, pickup_date, return_date, total_days, total_price, special_requests, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending Payment')";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $insert_sql);
    mysqli_stmt_bind_param($stmt, "iissssids", $user_id, $vehicle_id, $pickup_location, $return_location, $pickup_date, $return_date, $total_days, $total_price, $special_requests);
    mysqli_stmt_execute($stmt);
    
    // Retrieve auto-generated booking ID
    $new_booking_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Redirect customer directly to payment gateway page with booking ID
    header("location: ../payment.php?booking_id=" . $new_booking_id);
    exit();
}