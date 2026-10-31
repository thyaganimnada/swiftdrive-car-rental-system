<?php
// Start session and include database connection
session_start();
require_once 'db/db.php';

// Verify user authentication and blog update submission
if (!isset($_SESSION["userid"]) || !isset($_POST['update_blog'])) {
    header("location: ../login.php");
    exit();
}

// Retrieve form input parameters
$user_id = $_SESSION["userid"];
$blog_id = (int)$_POST['blog_id'];
$title = $_POST['title'];
$category = $_POST['category'];
$excerpt = $_POST['excerpt'];
$content = $_POST['content'];

// Verify blog post ownership and retrieve current image URL
$check_sql = "SELECT image_url FROM blogs WHERE id = ? AND user_id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $check_sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $blog_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Terminate request if blog does not belong to logged in user
    if (mysqli_num_rows($result) === 0) {
        die("Unauthorized Access! You cannot edit someone else's blog.");
    }
    
    // Retain existing image URL if no replacement image is uploaded
    $row = mysqli_fetch_assoc($result);
    $image_url = $row['image_url']; 
    mysqli_stmt_close($stmt);
} else {
    die("Database Error!");
}

// Recalculate estimated reading time based on updated content
$word_count = str_word_count(strip_tags($content));
$read_time = ceil($word_count / 200); 
if($read_time < 1) $read_time = 1;

// Handle replacement image file upload if provided
if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] == 0) {
    $target_dir = "../uploads/blogs/";
    
    // Create uploads directory if not already created
    if(!is_dir($target_dir)) { 
        mkdir($target_dir, 0777, true); 
    }
    
    $file_name = time() . "_" . basename($_FILES["blog_image"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["blog_image"]["tmp_name"], $target_file)) {
        
        // Remove old blog image file to conserve storage space
        if ($image_url !== "assets/images/default.jpg" && file_exists("../" . $image_url)) {
            unlink("../" . $image_url);
        }
        
        // Update image URL path for database storage
        $image_url = "uploads/blogs/" . $file_name;
    }
}

// Update blog post in database and reset status to Pending for admin re-approval
$sql = "UPDATE blogs SET title=?, category=?, image_url=?, read_time=?, excerpt=?, content=?, status='Pending' WHERE id=? AND user_id=?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    // Bind all 8 parameters to prepared statement
    mysqli_stmt_bind_param($stmt, "sssissii", $title, $category, $image_url, $read_time, $excerpt, $content, $blog_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        // Redirect back to blog list with update confirmation message
        header("location: ../my_blogs.php?action=list&msg=updated");
        exit();
    } else {
        die("Error Updating Blog: " . mysqli_error($conn));
    }
} else {
    die("Error Preparing Statement: " . mysqli_error($conn));
}
?>