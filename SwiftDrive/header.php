<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/logo.png">
    <title>SwiftDrive</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding-top: 75px;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg swift-navbar fixed-top bg-white shadow-sm" style="z-index: 1050; width: 100%;">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand brand-logo text-decoration-none" href="index.php" id="navLogo">
                    <img src="assets/logo.png" alt="SwiftDrive Logo" style="height: 40px; width: auto; margin-right: 8px;">
                    <span>SWIFTDRIVE</span>
                </a>

                <!-- Mobile Toggle -->
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars fs-3 text-dark"></i>
                </button>

                <!-- Nav Links -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'vehicles.php') ? 'active' : ''; ?>" href="index.php#vehicle">Vehicles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'support.php' && isset($_GET['tab']) && $_GET['tab'] == 'about') ? 'active' : ''; ?>" href="index.php#about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'blog.php' || $current_page == 'blog_view.php') ? 'active' : ''; ?>" href="index.php#blog">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#contact">Contact</a>
                        </li>
                    </ul>

                    <!-- Auth / User Actions -->
                    <div class="auth-btn-group">
                        <?php
                        if (isset($_SESSION["userid"])) { ?>
                            <div class="dropdown">
                                <button class="btn btn-swift-dark dropdown-toggle d-flex align-items-center gap-2 shadow-sm"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-circle-user text-primary fs-5"></i>
                                    <span><?php echo htmlspecialchars($_SESSION["fullname"]); ?></span>
                                    <span class="badge bg-primary text-white text-uppercase small ms-1">
                                        <?php echo htmlspecialchars($_SESSION["role"]); ?>
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-2">
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold" href="<?php
                                                                                        if (isset($_SESSION["role"])) {

                                                                                            if ($_SESSION["role"] == "ADMIN" || $_SESSION["role"] == "STAFF") {
                                                                                                echo "admin/index.php";
                                                                                            } elseif ($_SESSION["role"] == "CUSTOMER") {
                                                                                                echo "dashboard.php";
                                                                                            } else {
                                                                                                echo "login.php";
                                                                                            }
                                                                                        } else {
                                                                                            echo "login.php";
                                                                                        }
                                                                                        ?>">
                                            <i class="fa-solid fa-gauge-high text-dark me-2 w-20px"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="my_bookings.php">
                                            <i class="fa-solid fa-calendar-check text-primary me-2 w-20px"></i>
                                            My Bookings
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?php
                                                                            if (isset($_SESSION["role"])) {

                                                                                if ($_SESSION["role"] == "ADMIN" || $_SESSION["role"] == "STAFF") {
                                                                                    echo "admin/profile.php";
                                                                                } elseif ($_SESSION["role"] == "CUSTOMER") {
                                                                                    echo "my_profile.php";
                                                                                } else {
                                                                                    echo "login.php";
                                                                                }
                                                                            } else {
                                                                                echo "login.php";
                                                                            }
                                                                            ?>">
                                            <i class="fa-regular fa-id-card text-muted me-2 w-20px"></i>
                                            My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="actions/logout.php">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2 w-20px"></i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <a href="login.php" class="btn btn-link text-decoration-none text-secondary fw-semibold">Login</a>
                            <a href="register.php" class="btn btn-primary fw-semibold px-4 rounded-pill shadow-sm">Register</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Execute scroll highlighting script only on homepage routes
        const isHomePage = window.location.pathname.endsWith('index.php') || window.location.pathname.endsWith('/');
        
        if (isHomePage) {
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            window.addEventListener('scroll', () => {
                let current = '';
                document.querySelectorAll('section').forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                        current = section.getAttribute('id');
                    }
                });

                if (current) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href').includes('#' + current)) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }
    });
</script>