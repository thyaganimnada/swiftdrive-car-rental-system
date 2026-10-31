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

// Retrieve user profile information for review attribution
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

// Handle customer review submission for a completed booking
if (isset($_POST['submit_review'])) {
    $booking_id = (int)$_POST['booking_id'];
    $vehicle_id = (int)$_POST['vehicle_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $customer_name = $customer['full_name'];

    // Ensure only one review is submitted per booking
    $check = mysqli_query($conn, "SELECT id FROM reviews WHERE booking_id = $booking_id");
    if (mysqli_num_rows($check) == 0) {
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, "INSERT INTO reviews (user_id, vehicle_id, booking_id, customer_name, rating, comment, status) VALUES (?, ?, ?, ?, ?, ?, 'Approved')")) {
            mysqli_stmt_bind_param($stmt, "iiisis", $user_id, $vehicle_id, $booking_id, $customer_name, $rating, $comment);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>window.location.href='my_reviews.php?msg=reviewed';</script>";
            exit();
        }
    }
}

// Retrieve completed bookings that are eligible for customer review
$pending_reviews_sql = "SELECT b.id as booking_id, b.return_date, v.id as vehicle_id, v.name as vehicle_name, v.image 
                        FROM bookings b 
                        JOIN vehicles v ON b.vehicle_id = v.id 
                        WHERE b.user_id = $user_id AND b.status = 'Completed' 
                        AND b.id NOT IN (SELECT booking_id FROM reviews WHERE user_id = $user_id) 
                        ORDER BY b.return_date DESC";
$pending_reviews = mysqli_query($conn, $pending_reviews_sql);

// Retrieve all customer-submitted reviews joined with vehicle information
$my_reviews_sql = "SELECT r.*, v.name as vehicle_name, v.image 
                   FROM reviews r 
                   JOIN vehicles v ON r.vehicle_id = v.id 
                   WHERE r.user_id = $user_id 
                   ORDER BY r.created_at DESC";
$my_reviews = mysqli_query($conn, $my_reviews_sql);



