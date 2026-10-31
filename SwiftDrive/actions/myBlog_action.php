<?php
// Start session and include database connection
session_start();
include_once "db/db.php";

// Verify user authentication
if (!isset($_SESSION["userid"])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION["userid"];

// Retrieve customer profile details for sidebar widget
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

// Determine requested view action: list, add, or edit
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$current_page = 'my_blogs.php';

// Fetch single blog post details if in edit mode
$edit_blog = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $blog_id = (int)$_GET['id'];
    $sql = "SELECT * FROM blogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $blog_id, $user_id);
        mysqli_stmt_execute($stmt);
        $edit_blog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }
    // Fallback to list view if blog post is not found or unauthorized
    if(!$edit_blog) { $action = 'list'; }
}

// Retrieve all customer blog posts for list view
if ($action == 'list') {
    $sql = "SELECT * FROM blogs WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $blogs = mysqli_stmt_get_result($stmt);
    } else {
        die("Error in Blogs Query: " . mysqli_error($conn));
    }
}