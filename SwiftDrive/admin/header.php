<?php
session_start();
if ($_SESSION["role"] !== "ADMIN" && $_SESSION["role"] !== "STAFF") {
    header("location: ../index.php?error=you are not  admin");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SwiftDrive</title>
    <link rel="icon" type="../image/png" href="../assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

   <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar d-flex flex-column" id="sidebar" style="height: 100vh;">
        
        <div class="sidebar-brand">
            <div><img src="../assets/logo.png" alt="SwiftDrive Logo" style="height: 40px; width: auto; margin-right: 8px;"> SwiftDrive</div>
            <button class="btn btn-sm text-white d-lg-none" id="closeSidebarBtn"><i class="fa-solid fa-xmark fs-4"></i></button>
        </div>

        <div class="nav-menu flex-grow-1" style="overflow-y: auto; padding-bottom: 20px;">
            <a href="../index.php" target="_blank" class="nav-item-custom mb-3" style="background-color: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);">
                <i class="fa-solid fa-globe"></i> <span>Public Site</span> <i class="fa-solid fa-arrow-up-right-from-square ms-auto" style="font-size:0.8rem; width:auto;"></i>
            </a>
            
            <a href="index.php" class="nav-item-custom"><i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span></a>
            <a href="vehicles.php" class="nav-item-custom"><i class="fa-solid fa-car"></i> <span>Vehicles</span></a>
            <a href="customers.php" class="nav-item-custom"><i class="fa-solid fa-users"></i> <span>Customers</span></a>
            <a href="bookings.php" class="nav-item-custom"><i class="fa-solid fa-calendar-check"></i> <span>Bookings</span></a>
            <a href="payments.php" class="nav-item-custom"><i class="fa-solid fa-credit-card"></i> <span>Payments</span></a>
            <a href="inquiries.php" class="nav-item-custom"><i class="fa-solid fa-envelope"></i> <span>Inquiries</span></a>
            <a href="reviews.php" class="nav-item-custom"><i class="fa-solid fa-star"></i> <span>Customer Reviews</span></a>
            <a href="blog.php" class="nav-item-custom"><i class="fa-solid fa-blog"></i> <span>Blogs</span></a>

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
            <a href="reports.php" class="nav-item-custom"><i class="fa-solid fa-chart-line"></i> <span>Reports</span></a>
            <div class="mt-4 mb-2 px-3 text-uppercase" style="font-size:0.75rem; color:#475569; font-weight:700;">Administration</div>
            <a href="admin_users.php" class="nav-item-custom">
                <i class="fa-solid fa-user-shield"></i> <span>Admin Users</span>
            </a>
            <?php endif; ?>
        </div> 

        <div class="mt-auto p-3 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
            <a href="profile.php" class="nav-item-custom" style="padding: 10px 15px; margin-bottom: 5px;">
                <i class="fa-solid fa-user"></i> <span>My Profile</span>
            </a>
            <a href="settings.php" class="nav-item-custom" style="padding: 10px 15px; margin-bottom: 5px;">
                <i class="fa-solid fa-gear"></i> <span>Settings</span>
            </a>
            <a href="../actions/logout.php" class="nav-item-custom text-danger" style="padding: 10px 15px; margin-bottom: 0;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Logout</span>
            </a>
        </div>

    </nav>

    <div class="main-content">
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggleBtn"><i class="fa-solid fa-bars fs-5"></i></button>
                <h4 class="mb-0 fw-bold text-dark fs-5">System Administration</h4>
            </div>
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:40px; height:40px;">AD</div>
        </header>