<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organization') {
    header("Location: ../login.php");
    exit();
}

$org_id = $_SESSION['ref_id'];
$orgName = $_SESSION['org_name'] ?? 'User';

$sql = "SELECT b.Booking_ID, b.Booking_Date, b.Start_Date, b.End_Date, 
               b.No_Of_Trucks, b.Duration,
               l.City, l.Area, l.State
        FROM Booking b
        JOIN Location l ON b.Location_ID = l.Location_ID
        WHERE b.Org_ID = '$org_id'
        ORDER BY b.Booking_Date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - TruckLink</title>
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
            <li><a href="my_bookings.php" class="active">My Bookings</a></li>
            <li><a href="view_assigned_trucks.php">Assigned Trucks</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>My Bookings</h1>
                <p>View all your truck booking requests</p>
            </div>
            <div class="user-badge">
                Welcome, <?php echo htmlspecialchars($orgName); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="section-header section-header-row">
                <div>
                    <h3>Booking History</h3>
                    <p class="table-subtitle">All bookings created by your organization</p>
                </div>
                <a href="new_booking.php" class="btn btn-primary">+ New Booking</a>
            </div>

            <?php if ($result && $result->num_rows > 0) { ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Location</th>
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
                                    <td>
                                        <?php 
                                        echo htmlspecialchars($row['City']) . " - " . 
                                             htmlspecialchars($row['Area']) . " - " . 
                                             htmlspecialchars($row['State']); 
                                        ?>
                                    </td>
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
                    <p>You have not created any bookings yet.</p>
                    <a href="new_booking.php" class="btn btn-primary">Create First Booking</a>
                </div>
            <?php } ?>
        </div>
    </main>

</div>

</body>
</html>