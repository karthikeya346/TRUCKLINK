<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header("Location: ../login.php");
    exit();
}

$ownerName = $_SESSION['username'] ?? 'Owner';
$owner_id = $_SESSION['ref_id'] ?? 0;

$totalTrucks = 0;
$availableTrucks = 0;
$bookedTrucks = 0;
$maintenanceTrucks = 0;

$miniTrucks = 0;
$containerTrucks = 0;
$heavyTrucks = 0;
$pickupTrucks = 0;

/* Total Trucks */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id'");
if ($result && $row = $result->fetch_assoc()) {
    $totalTrucks = $row['total'];
}

/* Available Trucks */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Status='Available'");
if ($result && $row = $result->fetch_assoc()) {
    $availableTrucks = $row['total'];
}

/* Booked Trucks */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Status='Booked'");
if ($result && $row = $result->fetch_assoc()) {
    $bookedTrucks = $row['total'];
}

/* Maintenance Trucks */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Status='Maintenance'");
if ($result && $row = $result->fetch_assoc()) {
    $maintenanceTrucks = $row['total'];
}

/* Fleet summary by type */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Truck_Type='Mini Truck'");
if ($result && $row = $result->fetch_assoc()) {
    $miniTrucks = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Truck_Type='Container'");
if ($result && $row = $result->fetch_assoc()) {
    $containerTrucks = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Truck_Type='Heavy Truck'");
if ($result && $row = $result->fetch_assoc()) {
    $heavyTrucks = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM Truck WHERE Owner_ID='$owner_id' AND Truck_Type='Pickup'");
if ($result && $row = $result->fetch_assoc()) {
    $pickupTrucks = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - TruckLink</title>
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
            <p>Owner Management Panel</p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="add_truck.php">Add Truck</a></li>
            <li><a href="my_trucks.php">My Trucks</a></li>
            <li><a href="daily_log.php">Daily Log</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Owner Dashboard</h1>
                <p>Manage your trucks and daily operations</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($ownerName); ?>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-text">
                <h2>Welcome back, <?php echo htmlspecialchars($ownerName); ?> 👋</h2>
                <p>
                    Add new trucks, track your available fleet, and maintain daily logs
                    from one professional dashboard.
                </p>

                <div class="hero-buttons">
                    <a href="add_truck.php" class="btn btn-primary">Add Truck</a>
                    <a href="my_trucks.php" class="btn btn-light">View My Trucks</a>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <p>Total Trucks</p>
                <h3><?php echo $totalTrucks; ?></h3>
                <span>Your registered fleet</span>
            </div>

            <div class="stat-card">
                <p>Available Trucks</p>
                <h3><?php echo $availableTrucks; ?></h3>
                <span>Ready for bookings</span>
            </div>

            <div class="stat-card">
                <p>Booked Trucks</p>
                <h3><?php echo $bookedTrucks; ?></h3>
                <span>Currently in service</span>
            </div>

            <div class="stat-card">
                <p>Maintenance</p>
                <h3><?php echo $maintenanceTrucks; ?></h3>
                <span>Requires attention</span>
            </div>
        </div>

        <div class="content-grid">
            <div class="table-card">
                <div class="section-header">
                    <h3>Owner Actions</h3>
                    <p class="table-subtitle">Quick access to important owner tasks</p>
                </div>

                <div class="quick-links">
                    <a href="add_truck.php">+ Add a New Truck</a>
                    <a href="my_trucks.php">View My Trucks</a>
                    <a href="daily_log.php">Add Daily Log</a>
                    <a href="view_logs.php">View Logs</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>

            <div class="quick-card">
                <div class="section-header">
                    <h3>Fleet Summary</h3>
                    <p class="table-subtitle">Current owner-side overview</p>
                </div>

                <div class="quick-links">
                    <a href="#">Mini Trucks: <?php echo $miniTrucks; ?></a>
                    <a href="#">Containers: <?php echo $containerTrucks; ?></a>
                    <a href="#">Heavy Trucks: <?php echo $heavyTrucks; ?></a>
                    <a href="#">Pickups: <?php echo $pickupTrucks; ?></a>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>