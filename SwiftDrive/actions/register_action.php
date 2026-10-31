<?php
// Check if registration form was submitted via POST
if (isset($_POST["submit"])) {

    // Retrieve input values from registration form
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $license_number = empty($_POST["license_number"]) ? NULL : $_POST["license_number"];
    $address = $_POST["address"];
    $pwd = $_POST["pwd"];
    $repwd = $_POST["repwd"];

    // Include database connection and helper functions
    require_once 'db/db.php';
    require_once 'functions.php';

    // Perform validation checks for registration inputs
    $emptyInput = emptyInputSignup($name, $email, $phone, $address, $pwd, $repwd);
    $invalidEmail = invalidEmail($email);
    $PwdMatch = PwdMatch($pwd, $repwd);
    $emailExists = emailExists($conn, $email);

    // Redirect if required fields are missing
    if ($emptyInput !== false) {
        header("location: ../register.php?error=emptyinputs");
        exit();
    }

    // Redirect if email format is invalid
    if ($invalidEmail !== false) {
        header("location: ../register.php?error=invalidemail");
        exit();
    }

    // Redirect if passwords do not match
    if ($PwdMatch !== false) {
        header("location: ../register.php?error=passwordnotmatch");
        exit();
    }

    // Redirect if email is already registered
    if ($emailExists !== false) {
        header("location: ../register.php?error=emailtaken");
        exit();
    }

    // Create new customer record in database
    createCustomer($conn, $name, $email, $phone, $license_number, $address, $pwd);

} else {
    // Redirect unauthorized direct URL access back to registration page
    header('location: ../register.php');
    exit();
}