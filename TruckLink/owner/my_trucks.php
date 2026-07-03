<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['ref_id'];
$ownerName = $_SESSION['username'] ?? 'Owner';

$sql = "SELECT * FROM Truck WHERE Owner_ID='$owner_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Trucks - TruckLink</title>
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
            <li><a href="my_trucks.php" class="active">My Trucks</a></li>
            <li><a href="daily_log.php">Daily Log</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>My Trucks</h1>
                <p>View and manage all trucks in your fleet</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($ownerName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Fleet Details</h3>
                    <p class="table-subtitle">All trucks registered under your account</p>
                </div>
                <a href="add_truck.php" class="btn btn-primary">+ Add Truck</a>
            </div>

            <?php if ($result && $result->num_rows > 0) { ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Truck ID</th>
                                <th>Truck Number</th>
                                <th>Truck Type</th>
                                <th>Capacity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Truck_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Truck_Number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Truck_Type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Capacity']); ?></td>
                                    <td>
                                        <?php
                                        $status = strtolower($row['Status']);
                                        $class = "assigned";

                                        if ($status == "available") {
                                            $class = "completed";
                                        } elseif ($status == "booked") {
                                            $class = "assigned";
                                        } elseif ($status == "maintenance") {
                                            $class = "pending";
                                        }
                                        ?>
                                        <span class="status <?php echo $class; ?>">
                                            <?php echo htmlspecialchars($row['Status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <h3>No trucks found</h3>
                    <p>You have not added any trucks yet.</p>
                    <a href="add_truck.php" class="btn btn-primary">Add First Truck</a>
                </div>
            <?php } ?>
        </div>
    </main>

</div>

</body>
</html>