<?php

// Check if any required signup input fields are empty
function emptyInputSignup($name, $email, $phone, $address, $pwd, $repwd) {
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($pwd) || empty($repwd)) {
        return true;
    } else {
        return false;
    }
}

// Validate if email format is valid
function invalidEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// Check if password and confirmation password match
function PwdMatch($pwd, $repwd) {
    if ($pwd !== $repwd) {
        return true;
    } else {
        return false;
    }
}

// Check if email already exists in database for customer or staff
function emailExists($conn, $email) {
    $sql = "SELECT users.*, COALESCE(customers.full_name, staff.full_name) AS full_name 
            FROM users 
            LEFT JOIN customers ON users.user_id = customers.user_id 
            LEFT JOIN staff ON users.user_id = staff.user_id 
            WHERE users.email = ?;";
            
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../login.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($stmt);
        return $row;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

// Create new customer account with secure password hash and database transaction
function createCustomer($conn, $name, $email, $phone, $license_number, $address, $pwd) {
    
    // Hash the password using default bcrypt algorithm
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    
    // Set default user role and account status
    $role = "CUSTOMER";
    $status = "ACTIVE";

    // Begin database transaction for dual table insertion
    mysqli_begin_transaction($conn);

    try {
        // Insert login credentials into users table
        $sqlUser = "INSERT INTO users (email, password, role, account_status) VALUES (?, ?, ?, ?);";
        $stmtUser = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmtUser, $sqlUser)) {
            throw new Exception("stmtfailed");
        }
        
        mysqli_stmt_bind_param($stmtUser, "ssss", $email, $hashedPwd, $role, $status);
        mysqli_stmt_execute($stmtUser);
        
        // Get the newly generated user ID
        $newUserId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmtUser);

        // Insert customer profile details into customers table
        $sqlCustomer = "INSERT INTO customers (user_id, full_name, phone, driving_license_no, address) VALUES (?, ?, ?, ?, ?);";
        $stmtCustomer = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmtCustomer, $sqlCustomer)) {
            throw new Exception("stmtfailed");
        }
        
        mysqli_stmt_bind_param($stmtCustomer, "issss", $newUserId, $name, $phone, $license_number, $address);
        mysqli_stmt_execute($stmtCustomer);
        mysqli_stmt_close($stmtCustomer);

        // Commit transaction if both insertions succeed
        mysqli_commit($conn);

        // Redirect to login page on successful registration
        header("location: ../login.php?signup=success");
        exit();

    } catch (Exception $e) {
        // Rollback all changes if any error occurs
        mysqli_rollback($conn);
        header("location: ../register.php?error=systemerror");
        exit();
    }
}

// Check if any login input fields are empty
function emptyInputLogin($email, $pwd) {
    if (empty($email) || empty($pwd)) {
        return true;
    } else {
        return false;
    }
}

// Authenticate user credentials and start session
function loginUser($conn, $email, $pwd) {
    // Retrieve user record from database by email
    $userExists = emailExists($conn, $email);

    // Check if user exists in database
    if ($userExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    // Check if user account is suspended by admin
    if (isset($userExists["account_status"]) && $userExists["account_status"] === 'SUSPENDED') {
        header("location: ../login.php?error=suspended");
        exit();
    }

    // Verify input password against hashed password in database
    $pwdHashed = $userExists["password"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../login.php?error=wrongpassword");
        exit();
    } 
    else if ($checkPwd === true) {
        // Initialize user session variables upon successful authentication
        session_start();
        
        $_SESSION["userid"] = $userExists["user_id"];
        $_SESSION["email"] = $userExists["email"];
        $_SESSION["role"] = $userExists["role"]; 
        $_SESSION["fullname"] = $userExists["full_name"]; 

        header("location: ../index.php"); 
        exit();
    }
}