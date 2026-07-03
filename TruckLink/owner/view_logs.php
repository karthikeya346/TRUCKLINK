<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['ref_id'];
$ownerName = $_SESSION['username'] ?? 'Owner';

$sql = "SELECT l.Log_ID, l.Truck_ID, l.Log_Date, l.Distance, l.Fuel_Used, l.Fuel_Cost, l.Driver_Name
        FROM Truck_Daily_Log l
        JOIN Truck t ON l.Truck_ID = t.Truck_ID
        WHERE t.Owner_ID = '$owner_id'
        ORDER BY l.Log_Date DESC, l.Log_ID DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Logs - TruckLink</title>
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="add_truck.php">Add Truck</a></li>
            <li><a href="my_trucks.php">My Trucks</a></li>
            <li><a href="daily_log.php">Daily Log</a></li>
            <li><a href="view_logs.php" class="active">View Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>My Daily Logs</h1>
                <p>View all daily logs recorded for your trucks</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($ownerName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Log Records</h3>
                    <p class="table-subtitle">Operational logs for trucks owned by you</p>
                </div>
                <a href="daily_log.php" class="btn btn-primary">+ Add New Log</a>
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
                    <h3>No logs found</h3>
                    <p>You have not added any daily logs for your trucks yet.</p>
                    <a href="daily_log.php" class="btn btn-primary">Add First Log</a>
                </div>
            <?php } ?>
        </div>
    </main>
</div>

</body>
</html>