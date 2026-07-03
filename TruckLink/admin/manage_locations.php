<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminName = $_SESSION['username'] ?? 'Admin';

$sql = "SELECT * FROM Location ORDER BY Location_ID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations - TruckLink</title>
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
            <li><a href="manage_locations.php" class="active">Manage Locations</a></li>
            <li><a href="logs.php">View Daily Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Manage Locations</h1>
                <p>View and manage all available service locations</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($adminName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Location Records</h3>
                    <p class="table-subtitle">All cities, areas, and pincodes available in the system</p>
                </div>
                <a href="add_location.php" class="btn btn-primary">+ Add New Location</a>
            </div>

            <?php if ($result && $result->num_rows > 0) { ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Location ID</th>
                                <th>City</th>
                                <th>Area</th>
                                <th>State</th>
                                <th>Pincode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Location_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['City']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Area']); ?></td>
                                    <td><?php echo htmlspecialchars($row['State']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Pincode']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <h3>No locations found</h3>
                    <p>No service locations have been added yet.</p>
                    <a href="add_location.php" class="btn btn-primary">Add First Location</a>
                </div>
            <?php } ?>
        </div>
    </main>
</div>

</body>
</html>