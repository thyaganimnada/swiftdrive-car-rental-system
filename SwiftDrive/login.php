<?php
// Start session and redirect already logged-in users to home
session_start();
if (isset($_SESSION["userid"])) {
    header("location: index.php?error=your alrady loging");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Document meta configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftDrive - Login</title>
    <!-- Favicon and CSS stylesheets -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="card login-card p-0">
            <div class="text-center pt-5 px-4 px-md-5 login-header">
                <div class="brand-icon-box mx-auto mb-3" style="width: 100px; height: 70px; font-size: 1.5rem;">
                    <img src="assets/logo.png" alt="SwiftDrive Logo" style="height: 40px; width: auto; margin-right: 8px;">
                </div>
                <h1 class="h3 fw-bold text-dark mb-2">Welcome Back</h1>
                <p class="text-muted small mb-0">Sign in to manage your SwiftDrive vehicle reservations.</p>
            </div>
            <?php
            if (isset($_GET['error'])) {

                echo '<div class="alert alert-danger rounded-3 shadow-sm fw-bold text-center mb-4">';

                if ($_GET['error'] == 'suspended') {
                    echo '<i class="fa-solid fa-ban me-2"></i> Your account has been suspended by the Admin. Please contact support.';
                }
                else if ($_GET['error'] == 'wrongpassword') {
                    echo '<i class="fa-solid fa-lock me-2"></i> Incorrect password! Please try again.';
                }
                else if ($_GET['error'] == 'wronglogin') {
                    echo '<i class="fa-solid fa-user-xmark me-2"></i> No account found with this email! Please check again.';
                }
                else if ($_GET['error'] == 'emptyinput') {
                    echo '<i class="fa-solid fa-circle-exclamation me-2"></i> Please fill in both email and password!';
                }
                echo '</div>';
            }
            ?>

            <div class="p-4 p-md-5 pt-4">
                <form action="actions/login_action.php" method="POST" id="loginForm">

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark" for="loginEmail">Email Address</label>
                        <div class="input-icon-wrapper">
                            <input type="email" class="form-control form-control-custom" id="loginEmail" name="email" placeholder="name@example.com" required>
                            <i class="fa-regular fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold text-dark mb-0" for="loginPassword">Password</label>
                        </div>
                        <div class="input-icon-wrapper">
                            <input type="password" class="form-control form-control-custom" id="loginPassword" name="password" placeholder="••••••••" required>
                            <i class="fa-solid fa-lock input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn btn-swift-primary w-100 py-3 mt-2">
                        Log In <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                    </button>
                </form>
            </div>

            <div class="text-center p-4 bg-light border-top small">
                <span class="text-muted">Don't have an account yet?</span>
                <a href="register.php" class="fw-bold text-primary text-decoration-none ms-1">Register Here</a>
            </div>

        </div>
    </div>
</body>

</html>