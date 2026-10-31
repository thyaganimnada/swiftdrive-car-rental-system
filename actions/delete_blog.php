<?php
// Start session and include database connection
session_start();
require_once 'db/db.php';

// Verify that user is authenticated
if (!isset($_SESSION["userid"])) {
    header("location: login.php");
    exit();
}

// Retrieve user ID from session and blog ID from query parameter
$user_id = $_SESSION["userid"];
$blog_id = (int)$_GET['id'];

// Check blog ownership and retrieve image file path before deletion
$check_sql = "SELECT image_url FROM blogs WHERE id = ? AND user_id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $check_sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $blog_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Terminate request if blog does not belong to currently logged in user
    if (mysqli_num_rows($result) === 0) {
        die("Unauthorized Access! You cannot delete someone else's blog.");
    }
    
    // Remove uploaded blog image file from server filesystem
    $row = mysqli_fetch_assoc($result);
    $image_url = "../" . $row['image_url'];
    
    // Delete custom uploaded image file if exists and is not default image
    if ($row['image_url'] !== "assets/images/default.jpg" && file_exists($image_url)) {
        unlink($image_url);
    }
    
    mysqli_stmt_close($stmt);
} else {
    die("Database Error!");
}

// Delete blog post record from database using prepared statement
$delete_sql = "DELETE FROM blogs WHERE id = ? AND user_id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $delete_sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $blog_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        // Redirect back to blog list with deletion success message
        header("location: ../my_blogs.php?action=list&msg=deleted");
        exit();
    } else {
        die("Error Deleting Blog: " . mysqli_error($conn));
    }
} else {
    die("Error Preparing Delete Statement: " . mysqli_error($conn));
}
?>