<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organization') {
    header("Location: ../login.php");
    exit();
}

$orgName = $_SESSION['org_name'] ?? 'User';
$org_id = $_SESSION['ref_id'] ?? 0;

$totalBookings = 0;
$pendingRequests = 0;
$assignedTrucks = 0;
$completedTrips = 0;

/* Total bookings by this organization */
$result = $conn->query("SELECT COUNT(*) AS total FROM Booking WHERE Org_ID='$org_id'");
if ($result && $row = $result->fetch_assoc()) {
    $totalBookings = $row['total'];
}

/* Pending requests = bookings not yet assigned */
$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM Booking b
    LEFT JOIN Assigns a ON b.Booking_ID = a.Booking_ID
    WHERE b.Org_ID='$org_id' AND a.Booking_ID IS NULL
");
if ($result && $row = $result->fetch_assoc()) {
    $pendingRequests = $row['total'];
}

/* Assigned trucks = total assignments for this organization's bookings */
$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM Booking b
    JOIN Assigns a ON b.Booking_ID = a.Booking_ID
    WHERE b.Org_ID='$org_id'
");
if ($result && $row = $result->fetch_assoc()) {
    $assignedTrucks = $row['total'];
}

/* For now, completed trips = assigned bookings count */
$completedTrips = $assignedTrucks;

/* Recent bookings */
$recentBookings = $conn->query("
    SELECT b.Booking_ID, b.Booking_Date, b.Start_Date, b.End_Date,
           b.No_Of_Trucks, l.City, l.Area, l.State,
           CASE 
               WHEN a.Booking_ID IS NULL THEN 'Pending'
               ELSE 'Assigned'
           END AS booking_status
    FROM Booking b
    JOIN Location l ON b.Location_ID = l.Location_ID
    LEFT JOIN Assigns a ON b.Booking_ID = a.Booking_ID
    WHERE b.Org_ID='$org_id'
    ORDER BY b.Booking_Date DESC, b.Booking_ID DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TruckLink Dashboard</title>
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
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="new_booking.php">New Booking</a></li>
            <li><a href="my_bookings.php">My Bookings</a></li>
            <li><a href="view_assigned_trucks.php">Assigned Trucks</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Organization Dashboard</h1>
                <p>Manage bookings and truck assignments easily</p>
            </div>
            <div class="user-badge">
                Welcome, <?php echo htmlspecialchars($orgName); ?>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-text">
                <h2>Welcome back, <?php echo htmlspecialchars($orgName); ?> 👋</h2>
                <p>
                    Track bookings, view assigned trucks, and manage your logistics operations
                    from one clean dashboard.
                </p>

                <div class="hero-buttons">
                    <a href="new_booking.php" class="btn btn-primary">Create Booking</a>
                    <a href="my_bookings.php" class="btn btn-light">View Bookings</a>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <p>Total Bookings</p>
                <h3><?php echo $totalBookings; ?></h3>
                <span>Your booking requests</span>
            </div>

            <div class="stat-card">
                <p>Pending Requests</p>
                <h3><?php echo $pendingRequests; ?></h3>
                <span>Awaiting truck assignment</span>
            </div>

            <div class="stat-card">
                <p>Assigned Trucks</p>
                <h3><?php echo $assignedTrucks; ?></h3>
                <span>Currently linked to bookings</span>
            </div>

            <div class="stat-card">
                <p>Completed Trips</p>
                <h3><?php echo $completedTrips; ?></h3>
                <span>Based on assigned records</span>
            </div>
        </div>

        <div class="content-grid">
            <div class="table-card">
                <div class="section-header section-header-row">
                    <div>
                        <h3>Recent Bookings</h3>
                        <p class="table-subtitle">Latest bookings from your organization</p>
                    </div>
                    <a href="my_bookings.php" class="btn btn-primary">View All</a>
                </div>

                <?php if ($recentBookings && $recentBookings->num_rows > 0) { ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>No. of Trucks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recentBookings->fetch_assoc()) { ?>
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
                                        <td><?php echo htmlspecialchars($row['No_Of_Trucks']); ?></td>
                                        <td>
                                            <?php
                                            $status = strtolower($row['booking_status']);
                                            $class = "pending";

                                            if ($status == "assigned") {
                                                $class = "assigned";
                                            } elseif ($status == "completed") {
                                                $class = "completed";
                                            }
                                            ?>
                                            <span class="status <?php echo $class; ?>">
                                                <?php echo htmlspecialchars($row['booking_status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="empty-state">
                        <h3>No bookings found</h3>
                        <p>You have not created any bookings yet.</p>
                        <a href="new_booking.php" class="btn btn-primary">Create Booking</a>
                    </div>
                <?php } ?>
            </div>

            <div class="quick-card">
                <div class="section-header">
                    <h3>Quick Actions</h3>
                </div>

                <div class="quick-links">
                    <a href="new_booking.php">+ Create New Booking</a>
                    <a href="my_bookings.php">View My Bookings</a>
                    <a href="view_assigned_trucks.php">Check Assigned Trucks</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </main>

</div>

</body>
</html>