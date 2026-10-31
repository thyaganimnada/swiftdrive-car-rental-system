<?php
// Start session and redirect logged-in users away from registration
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
    <title>SwiftDrive - Register</title>
    <!-- Favicon and CSS stylesheets -->
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light d-flex align-items-center min-vh-100 py-5">

    <div class="container">
        <div class="card login-card register-card p-0">

            <div class="text-center pt-5 px-4 px-md-5 login-header">
                <div class="brand-icon-box mx-auto mb-3" style="width: 100px; height: 70px; font-size: 1.5rem;">
                    <img src="assets/logo.png" alt="SwiftDrive Logo" style="height: 40px; width: auto; margin-right: 8px;">
                </div>
                <h1 class="h3 fw-bold text-dark mb-2">Create an Account</h1>
            </div>

            <?php
            // URL එකේ error කියලා එකක් තියෙනවද කියලා බලනවා
            if (isset($_GET['error'])) {

                // Error message එක පෙන්නන්න ලස්සන Bootstrap Alert Box එකක් හදනවා
                echo '<div class="alert alert-danger rounded-3 shadow-sm fw-bold text-center mb-4">';

                // එක එක Error එකට ගැලපෙන පණිවිඩය
                if ($_GET['error'] == 'emptyinput') {
                    echo '<i class="fa-solid fa-circle-exclamation me-2"></i> Please fill in all fields!';
                } else if ($_GET['error'] == 'invalidemail') {
                    echo '<i class="fa-solid fa-envelope-circle-xmark me-2"></i> Please enter a valid email address!';
                } else if ($_GET['error'] == 'passwordsdontmatch') {
                    echo '<i class="fa-solid fa-key me-2"></i> Passwords do not match!';
                } else if ($_GET['error'] == 'emailexists') {
                    echo '<i class="fa-solid fa-user-xmark me-2"></i> This email is already registered! Please login.';
                } else if ($_GET['error'] == 'emailtaken') {
                    echo '<i class="fa-solid fa-user-xmark me-2"></i> This email is already registered! Please use another email or login.';
                } else if ($_GET['error'] == 'stmtfailed' || $_GET['error'] == 'systemerror') {
                    echo '<i class="fa-solid fa-triangle-exclamation me-2"></i> Something went wrong in the system. Please try again!';
                }

                echo '</div>';
            }
            ?>

            <div class="p-4 p-md-5 pt-4">
                <form action="actions/register_action.php" method="POST" id="registerForm">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-dark" for="regName">Full Name</label>
                            <div class="input-icon-wrapper">
                                <input type="text" class="form-control form-control-custom" id="regName" name="name" placeholder="e.g. Kasun Jayawardena" required>
                                <i class="fa-regular fa-user input-icon"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark" for="regEmail">Email Address</label>
                            <div class="input-icon-wrapper">
                                <input type="email" class="form-control form-control-custom" id="regEmail" name="email" placeholder="e.g. kasun@swiftdrive.lk" required>
                                <i class="fa-regular fa-envelope input-icon"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark" for="regPhone">Phone Number</label>
                            <div class="input-icon-wrapper">
                                <input type="tel" class="form-control form-control-custom" id="regPhone" name="phone" placeholder="+94 77 123 4567" required>
                                <i class="fa-solid fa-phone input-icon"></i>
                            </div>
                        </div>

                        <div class="col-md-12" id="licenseFieldGroup">
                            <label class="form-label small fw-bold text-dark" for="regLicense">Driving License / NIC</label>
                            <div class="input-icon-wrapper">
                                <input type="text" class="form-control form-control-custom" id="regLicense" name="license_number" placeholder="e.g. B-8819201">
                                <i class="fa-regular fa-id-card input-icon"></i>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-dark" for="regName">Address</label>
                            <div class="input-icon-wrapper">
                                <input type="text" class="form-control form-control-custom" id="address" name="address" placeholder="e.g. 501/1, wellawidiya, Negombo" required>
                                <i class="fa-regular fa-map input-icon"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark" for="regPassword">Account Password</label>
                            <div class="input-icon-wrapper">
                                <input type="password" class="form-control form-control-custom" id="regPassword" name="pwd" placeholder="••••••••" required>
                                <i class="fa-solid fa-lock input-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark" for="regPassword">conform Password</label>
                            <div class="input-icon-wrapper">
                                <input type="password" class="form-control form-control-custom" id="regPassword" name="repwd" placeholder="••••••••" required>
                                <i class="fa-solid fa-lock input-icon"></i>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" name="submit" class="btn btn-swift-primary w-100 py-3 fw-bold text-uppercase">
                                Complete Registration <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <div class="text-center p-4 bg-light border-top small">
                <span class="text-muted">Already have an account?</span>
                <a href="login.php" class="fw-bold text-primary text-decoration-none ms-1">Login Here</a>
            </div>

        </div>
    </div>
</body>

</html>