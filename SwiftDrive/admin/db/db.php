<?php
// Database server configuration settings
$servername = "localhost";
$dbUsername = "root";       
$dbPassword = "";           
$dbName = "swiftdrive";     

// Establish MySQL database connection
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

// Halt execution with error message if connection fails
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}