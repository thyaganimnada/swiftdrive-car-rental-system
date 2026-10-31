<?php
// Start session, unset all session variables, and destroy active session
session_start();
session_unset();
session_destroy();

// Redirect user back to homepage
header("location: ../index.php");
exit();