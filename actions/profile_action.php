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

// Handle customer profile details update
if (isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $dl_no = trim($_POST['driving_license_no']);

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, "UPDATE customers SET full_name=?, phone=?, address=?, driving_license_no=? WHERE user_id=?")) {
        mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $phone, $address, $dl_no, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='my_profile.php?msg=profile_updated';</script>";
        exit();
    }
}

// Handle customer account password change
if (isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    $s_stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($s_stmt, "i", $user_id);
    mysqli_stmt_execute($s_stmt);
    $u_data = mysqli_fetch_assoc(mysqli_stmt_get_result($s_stmt));

    if (password_verify($current_pass, $u_data['password'])) {
        if ($new_pass === $confirm_pass) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $u_stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($u_stmt, "si", $hashed, $user_id);
            mysqli_stmt_execute($u_stmt);
            echo "<script>window.location.href='my_profile.php?msg=password_updated';</script>";
            exit();
        } else {
            echo "<script>window.location.href='my_profile.php?error=password_mismatch';</script>";
            exit();
        }
    } else {
        echo "<script>window.location.href='my_profile.php?error=wrong_password';</script>";
        exit();
    }
}

// Retrieve customer profile details from database
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