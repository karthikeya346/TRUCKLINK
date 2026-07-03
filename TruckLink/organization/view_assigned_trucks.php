<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organization') {
    header("Location: ../login.php");
    exit();
}

$orgName = $_SESSION['org_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Trucks - TruckLink</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-box">
                <img src="../assets/images/trucklink-logo2.jpeg" alt="TruckLink Logo">
            </div>
            <h2>TruckLink</h2>
            <p>Smart Logistics Platform</p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="new_booking.php">New Booking</a></li>
            <li><a href="my_bookings.php">My Bookings</a></li>
            <li><a href="view_assigned_trucks.php" class="active">Assigned Trucks</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Assigned Trucks</h1>
                <p>View trucks assigned to your bookings</p>
            </div>
            <div class="user-badge">
                Welcome, <?php echo htmlspecialchars($orgName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Assigned Trucks</h3>
                    <p class="table-subtitle">This section will show truck assignments once linked from admin side.</p>
                </div>
                <a href="my_bookings.php" class="btn btn-primary">View Bookings</a>
            </div>

            <div class="empty-state">
                <h3>No assigned trucks available</h3>
                <p>
                    Either no trucks are assigned yet, or the truck assignment query
                    needs to be connected to your database structure.
                </p>
                <a href="new_booking.php" class="btn btn-primary">Create Booking</a>
            </div>
        </div>
    </main>

</div>

</body>
</html>