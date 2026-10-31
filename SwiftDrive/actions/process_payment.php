<?php
// Start session to manage user state
session_start();

// Handle deposit payment submission via POST
if (isset($_POST["pay_deposit"])) {
    
    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        header("location: ../login.php");
        exit();
    }

    // Include database connection
    require_once 'db/db.php';

    // Retrieve payment details from POST request
    $booking_id = (int)$_POST["booking_id"];
    $amount = (float)$_POST["amount"];
    $user_id = $_SESSION["userid"];

    // Generate unique transaction reference code
    $transaction_ref = "TXN-" . strtoupper(substr(uniqid(), -6));

    // Insert payment record into payments table
    $payment_sql = "INSERT INTO payments (booking_id, payment_type, amount, payment_method, transaction_ref, payment_status) VALUES (?, 'Security Deposit', ?, 'Credit Card', ?, 'Completed')";
    $stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($stmt, $payment_sql)) {
        mysqli_stmt_bind_param($stmt, "ids", $booking_id, $amount, $transaction_ref);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        header("location: ../payment.php?booking_id=$booking_id&error=paymentfailed");
        exit();
    }

    // Update booking status to Confirmed after successful deposit payment
    $update_sql = "UPDATE bookings SET status = 'Confirmed' WHERE id = ? AND user_id = ?";
    $stmt2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt2, $update_sql)) {
        mysqli_stmt_bind_param($stmt2, "ii", $booking_id, $user_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    }

    // Redirect to booking confirmation screen
    header("location: ../booking-confirmation.php?booking_id=" . $booking_id);
    exit();

} else {
    // Redirect unauthorized direct requests to homepage
    header("location: ../index.php");
    exit();
}