<?php
// Check if login form was submitted via POST
if (isset($_POST["submit"])) {
    
    // Retrieve login form inputs
    $email = $_POST["email"];
    $pwd = $_POST["password"];
    
    // Include database connection and helper functions
    require_once 'db/db.php';
    require_once 'functions.php';
    
    // Validate whether any login inputs are empty
    if (emptyInputLogin($email, $pwd) !== false) {
        header('location: ../login.php?error=emptyinput');
        exit();
    }

    // Authenticate user with database credentials
    loginUser($conn, $email, $pwd);
}
else {
    // Redirect unauthorized direct requests to login page
    header('location: ../login.php');
    exit();
}