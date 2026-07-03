<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminName = $_SESSION['username'] ?? 'Admin';

/* Dynamic counts */
$totalBookings = 0;
$pendingAssignments = 0;
$registeredTrucks = 0;
$locationsAdded = 0;
$totalLogs = 0;
$totalOwners = 0;
$totalOrganizations = 0;

/* Total bookings */
$result = $conn->query("SELECT COUNT(*) AS total FROM Booking");
if ($result && $row = $result->fetch_assoc()) {
    $totalBookings = $row['total'];
}

/* Pending assignments = bookings not present in Assigns */
$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM Booking b
    LEFT JOIN Assigns a ON b.Booking_ID = a.Booking_ID
    WHERE a.Booking_ID IS NULL
");
if ($result && $row = $result->fetch_assoc()) {
    $pendingAssignments = $row['total'];
}

/* Registered trucks */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck");
if ($result && $row = $result->fetch_assoc()) {
    $registeredTrucks = $row['total'];
}

/* Locations added */
$result = $conn->query("SELECT COUNT(*) AS total FROM Location");
if ($result && $row = $result->fetch_assoc()) {
    $locationsAdded = $row['total'];
}

/* Daily logs count */
$result = $conn->query("SELECT COUNT(*) AS total FROM Truck_Daily_Log");
if ($result && $row = $result->fetch_assoc()) {
    $totalLogs = $row['total'];
}

/* Optional: total owners */
$result = $conn->query("SELECT COUNT(*) AS total FROM TruckOwner");
if ($result && $row = $result->fetch_assoc()) {
    $totalOwners = $row['total'];
}

/* Optional: total organizations */
$result = $conn->query("SELECT COUNT(*) AS total FROM Organization");
if ($result && $row = $result->fetch_assoc()) {
    $totalOrganizations = $row['total'];
}

/* Recent bookings */
$recentBookings = $conn->query("
    SELECT Booking_ID, Org_ID, Location_ID, Booking_Date, No_Of_Trucks
    FROM Booking
    ORDER BY Booking_Date DESC, Booking_ID DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TruckLink</title>
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
            <p>Admin Control Panel</p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="manage_bookings.php">View All Bookings</a></li>
            <li><a href="assign_trucks.php">Assign Trucks</a></li>
            <li><a href="manage_locations.php">Manage Locations</a></li>
            <li><a href="logs.php">View Daily Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Admin Dashboard</h1>
                <p>Manage bookings, trucks, locations, and system operations</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($adminName); ?>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-text">
                <h2>Welcome back, <?php echo htmlspecialchars($adminName); ?> 👋</h2>
                <p>
                    Oversee the complete TruckLink workflow by monitoring bookings,
                    assigning trucks, managing locations, and reviewing owner daily logs.
                </p>

                <div class="hero-buttons">
                    <a href="manage_bookings.php" class="btn btn-primary">View Bookings</a>
                    <a href="assign_trucks.php" class="btn btn-light">Assign Trucks</a>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <p>Total Bookings</p>
                <h3><?php echo $totalBookings; ?></h3>
                <span>Across all organizations</span>
            </div>

            <div class="stat-card">
                <p>Pending Assignments</p>
                <h3><?php echo $pendingAssignments; ?></h3>
                <span>Need truck allocation</span>
            </div>

            <div class="stat-card">
                <p>Registered Trucks</p>
                <h3><?php echo $registeredTrucks; ?></h3>
                <span>Available in system</span>
            </div>

            <div class="stat-card">
                <p>Locations Added</p>
                <h3><?php echo $locationsAdded; ?></h3>
                <span>Managed by admin</span>
            </div>
        </div>

        <div class="content-grid">
            <div class="table-card">
                <div class="section-header section-header-row">
                    <div>
                        <h3>Recent Bookings</h3>
                        <p class="table-subtitle">Latest booking requests in the system</p>
                    </div>
                    <a href="manage_bookings.php" class="btn btn-primary">View All</a>
                </div>

                <?php if ($recentBookings && $recentBookings->num_rows > 0) { ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Org ID</th>
                                    <th>Location ID</th>
                                    <th>Booking Date</th>
                                    <th>No. of Trucks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recentBookings->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['Booking_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Org_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Location_ID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Booking_Date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['No_Of_Trucks']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="empty-state">
                        <h3>No bookings found</h3>
                        <p>No recent bookings are available in the system.</p>
                    </div>
                <?php } ?>
            </div>

            <div class="quick-card">
                <div class="section-header">
                    <h3>System Summary</h3>
                    <p class="table-subtitle">Live admin overview</p>
                </div>

                <div class="quick-links">
                    <a href="manage_bookings.php">Total Bookings: <?php echo $totalBookings; ?></a>
                    <a href="assign_trucks.php">Pending Assignments: <?php echo $pendingAssignments; ?></a>
                    <a href="logs.php">Trips Logged: <?php echo $totalLogs; ?></a>
                    <a href="manage_locations.php">Locations: <?php echo $locationsAdded; ?></a>
                    <a href="#">Owners Registered: <?php echo $totalOwners; ?></a>
                    <a href="#">Organizations Registered: <?php echo $totalOrganizations; ?></a>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>