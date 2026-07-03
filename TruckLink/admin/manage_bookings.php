<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminName = $_SESSION['username'] ?? 'Admin';

$sql = "SELECT * FROM Booking ORDER BY Booking_Date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - TruckLink</title>
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_bookings.php" class="active">View All Bookings</a></li>
            <li><a href="assign_trucks.php">Assign Trucks</a></li>
            <li><a href="manage_locations.php">Manage Locations</a></li>
            <li><a href="logs.php">View Daily Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>All Bookings</h1>
                <p>View and monitor every booking request in the system</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($adminName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Booking Records</h3>
                    <p class="table-subtitle">All bookings submitted by organizations</p>
                </div>
                <a href="assign_trucks.php" class="btn btn-primary">Assign Trucks</a>
            </div>

            <?php if ($result && $result->num_rows > 0) { ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Org ID</th>
                                <th>Location ID</th>
                                <th>Booking Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>No. of Trucks</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Booking_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Org_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Location_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Booking_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Start_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['End_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['No_Of_Trucks']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Duration']); ?> day(s)</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <h3>No bookings found</h3>
                    <p>There are currently no booking requests in the system.</p>
                    <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                </div>
            <?php } ?>
        </div>
    </main>
</div>

</body>
</html>