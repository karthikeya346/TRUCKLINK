<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";
$message_type = "";
$adminName = $_SESSION['username'] ?? 'Admin';

/* Fetch pending bookings only */
$booking_sql = "
    SELECT b.Booking_ID, b.Org_ID, b.Booking_Date, b.No_Of_Trucks
    FROM Booking b
    LEFT JOIN Assigns a ON b.Booking_ID = a.Booking_ID
    WHERE a.Booking_ID IS NULL
    ORDER BY b.Booking_Date DESC, b.Booking_ID DESC
";
$booking_result = $conn->query($booking_sql);

/* Fetch only available trucks */
$truck_sql = "
    SELECT Truck_ID, Truck_Number, Truck_Type, Capacity, Status
    FROM Truck
    WHERE Status = 'Available'
    ORDER BY Truck_ID DESC
";
$truck_result = $conn->query($truck_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = trim($_POST['booking_id']);
    $truck_id = trim($_POST['truck_id']);

    $check_booking = "SELECT * FROM Booking WHERE Booking_ID='$booking_id'";
    $booking_check_result = $conn->query($check_booking);

    $check_truck = "SELECT * FROM Truck WHERE Truck_ID='$truck_id' AND Status='Available'";
    $truck_check_result = $conn->query($check_truck);

    if (!$booking_check_result || $booking_check_result->num_rows == 0) {
        $message = "Selected booking not found.";
        $message_type = "error";
    } elseif (!$truck_check_result || $truck_check_result->num_rows == 0) {
        $message = "Selected truck is not available.";
        $message_type = "error";
    } else {
        $check_assign = "SELECT * FROM Assigns WHERE Booking_ID='$booking_id'";
        $assign_result = $conn->query($check_assign);

        if ($assign_result && $assign_result->num_rows > 0) {
            $message = "This booking is already assigned.";
            $message_type = "error";
        } else {
            $insert_sql = "INSERT INTO Assigns (Booking_ID, Truck_ID) VALUES ('$booking_id', '$truck_id')";

            if ($conn->query($insert_sql) === TRUE) {
                $update_sql = "UPDATE Truck SET Status='Booked' WHERE Truck_ID='$truck_id'";

                if ($conn->query($update_sql) === TRUE) {
                    $message = "Truck assigned successfully and truck status updated to Booked.";
                    $message_type = "success";
                } else {
                    $message = "Assignment saved, but truck status update failed: " . $conn->error;
                    $message_type = "error";
                }
            } else {
                $message = "Error: " . $conn->error;
                $message_type = "error";
            }
        }
    }

    /* Refresh dropdown data after submit */
    $booking_result = $conn->query($booking_sql);
    $truck_result = $conn->query($truck_sql);
}

/* Recent assignments */
$recent_assignments_sql = "
    SELECT a.Booking_ID, a.Truck_ID, b.Org_ID, b.Booking_Date,
           t.Truck_Number, t.Truck_Type
    FROM Assigns a
    JOIN Booking b ON a.Booking_ID = b.Booking_ID
    JOIN Truck t ON a.Truck_ID = t.Truck_ID
    ORDER BY b.Booking_Date DESC, a.Booking_ID DESC
    LIMIT 5
";
$recent_assignments = $conn->query($recent_assignments_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Trucks - TruckLink</title>
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
            <li><a href="assign_trucks.php" class="active">Assign Trucks</a></li>
            <li><a href="manage_locations.php">Manage Locations</a></li>
            <li><a href="logs.php">View Daily Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Assign Truck to Booking</h1>
                <p>Select pending bookings and available trucks</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($adminName); ?>
            </div>
        </div>

        <div class="form-card">
            <div class="section-header">
                <h3>Smart Assignment</h3>
                <p class="form-subtitle">Choose a pending booking and an available truck.</p>
            </div>

            <?php if ($message != "") { ?>
                <div class="alert <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="post" class="truck-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Select Booking</label>
                        <select name="booking_id" required>
                            <option value="">Choose Pending Booking</option>
                            <?php
                            if ($booking_result && $booking_result->num_rows > 0) {
                                while ($booking = $booking_result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($booking['Booking_ID']) . "'>
                                            Booking #" . htmlspecialchars($booking['Booking_ID']) .
                                            " | Org: " . htmlspecialchars($booking['Org_ID']) .
                                            " | Date: " . htmlspecialchars($booking['Booking_Date']) .
                                            " | Trucks: " . htmlspecialchars($booking['No_Of_Trucks']) .
                                         "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Select Available Truck</label>
                        <select name="truck_id" required>
                            <option value="">Choose Available Truck</option>
                            <?php
                            if ($truck_result && $truck_result->num_rows > 0) {
                                while ($truck = $truck_result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($truck['Truck_ID']) . "'>
                                            Truck #" . htmlspecialchars($truck['Truck_ID']) .
                                            " | " . htmlspecialchars($truck['Truck_Number']) .
                                            " | " . htmlspecialchars($truck['Truck_Type']) .
                                            " | " . htmlspecialchars($truck['Capacity']) .
                                         "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Assign Truck</button>
                    <a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>
                </div>
            </form>
        </div>

        <div class="table-card" style="margin-top: 24px;">
            <div class="section-header section-header-row">
                <div>
                    <h3>Recent Assignments</h3>
                    <p class="table-subtitle">Latest truck assignments made by admin</p>
                </div>
            </div>

            <?php if ($recent_assignments && $recent_assignments->num_rows > 0) { ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Org ID</th>
                                <th>Truck ID</th>
                                <th>Truck Number</th>
                                <th>Truck Type</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $recent_assignments->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Booking_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Org_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Truck_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Truck_Number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Truck_Type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Booking_Date']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <h3>No assignments yet</h3>
                    <p>No truck assignments have been made so far.</p>
                </div>
            <?php } ?>
        </div>
    </main>
</div>

</body>
</html>