<?php
// Check if contact inquiry form was submitted via POST
if (isset($_POST["submit_inquiry"])) {
    
    // Include database connection
    require_once 'db/db.php'; 

    // Retrieve contact inquiry form inputs
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Validate that all required inquiry fields are filled
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        header("location: ../index.php?error=emptyinput#contact");
        exit();
    }

    // Prepare SQL query to insert inquiry into database
    $sql = "INSERT INTO inquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtfailed#contact");
        exit();
    }

    // Bind parameters and execute prepared statement
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $subject, $message);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect to contact section with success message
    header("location: ../index.php?success=inquirysent#contact");
    exit();

} else {
    // Redirect unauthorized direct requests back to homepage
    header("location: ../index.php");
    exit();
}