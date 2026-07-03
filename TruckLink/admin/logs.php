<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminName = $_SESSION['username'] ?? 'Admin';

$sql = "SELECT * FROM Truck_Daily_Log ORDER BY Log_Date DESC, Log_ID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck Daily Logs - TruckLink</title>
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
            <li><a href="manage_bookings.php">View All Bookings</a></li>
            <li><a href="assign_trucks.php">Assign Trucks</a></li>
            <li><a href="manage_locations.php">Manage Locations</a></li>
            <li><a href="logs.php" class="active">View Daily Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Truck Daily Logs</h1>
                <p>Monitor daily truck operations, fuel usage, and driver records</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($adminName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Daily Log Records</h3>
                    <p class="table-subtitle">All submitted operational logs from truck owners</p>
                </div>
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>

            <?php if ($result && $result->num_rows > 0) { ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Truck ID</th>
                                <th>Log Date</th>
                                <th>Distance (km)</th>
                                <th>Fuel Used (L)</th>
                                <th>Fuel Cost</th>
                                <th>Driver Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Log_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Truck_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Log_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Distance']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Fuel_Used']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Fuel_Cost']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Driver_Name']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <h3>No daily logs found</h3>
                    <p>No truck daily log records are available yet.</p>
                    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                </div>
            <?php } ?>
        </div>
    </main>
</div>

</body>
</html>