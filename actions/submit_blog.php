<?php
// Start session and verify user authentication and form submission
session_start();
if (!isset($_SESSION["userid"]) || !isset($_POST['submit_blog'])) {
    header("location: ../login.php");
    exit();
}
require_once 'db/db.php';

// Retrieve submitted blog form data
$user_id = $_SESSION["userid"];
$title = $_POST['title'];
$category = $_POST['category'];
$excerpt = $_POST['excerpt'];
$content = $_POST['content'];

// Automatically calculate estimated reading time based on word count
$word_count = str_word_count(strip_tags($content));
$read_time = ceil($word_count / 200); 
if($read_time < 1) $read_time = 1;

// Handle blog featured image upload to server directory
$image_url = "";
if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
    // Target directory path for uploaded blog images
    $target_dir = "../uploads/blogs/";
    
    // Create uploads directory if it does not exist
    if(!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Generate unique image filename using timestamp prefix
    $file_name = time() . "_" . basename($_FILES["blog_image"]["name"]);
    $target_file = $target_dir . $file_name;
    
    // Move uploaded temporary file to destination directory
    if (move_uploaded_file($_FILES["blog_image"]["tmp_name"], $target_file)) {
        // Relative path for database storage
        $image_url = "uploads/blogs/" . $file_name;
    }
}

// Insert new blog post into database with default Pending approval status
$sql = "INSERT INTO blogs (user_id, title, category, image_url, read_time, excerpt, content, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "isssiss", $user_id, $title, $category, $image_url, $read_time, $excerpt, $content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Redirect to customer blogs list with submission confirmation
    header("location: ../my_blogs.php?msg=blog_submitted");
    exit();
} else {
    header("location: ../my_blogs.php?error=failed");
    exit();
}