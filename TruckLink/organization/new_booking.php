<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'organization') {
    header("Location: ../login.php");
    exit();
}

$message = "";
$message_type = "";
$org_id = $_SESSION['ref_id'];
$orgName = $_SESSION['org_name'] ?? 'User';

$location_sql = "SELECT * FROM Location";
$location_result = $conn->query($location_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $location_id = $_POST['location_id'];
    $booking_date = $_POST['booking_date'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $no_trucks = $_POST['no_trucks'];

    $duration = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);

    $sql = "INSERT INTO Booking
    (Booking_ID, Org_ID, Location_ID, Booking_Date, Start_Date, End_Date, No_Of_Trucks, Duration)
    VALUES
    ('$booking_id', '$org_id', '$location_id', '$booking_date', '$start_date', '$end_date', '$no_trucks', '$duration')";

    if ($conn->query($sql) === TRUE) {
        $message = "Booking created successfully!";
        $message_type = "success";
    } else {
        $message = "Error: " . $conn->error;
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking - TruckLink</title>
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
            <li><a href="new_booking.php" class="active">New Booking</a></li>
            <li><a href="my_bookings.php">My Bookings</a></li>
            <li><a href="view_assigned_trucks.php">Assigned Trucks</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Create Booking</h1>
                <p>Submit a new truck booking request</p>
            </div>
            <div class="user-badge">
                Welcome, <?php echo htmlspecialchars($orgName); ?>
            </div>
        </div>

        <div class="form-card">
            <div class="section-header">
                <h3>Booking Details</h3>
                <p class="form-subtitle">Enter the booking information below.</p>
            </div>

            <?php if ($message != "") { ?>
                <div class="alert <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="post" class="truck-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Booking ID</label>
                        <input type="number" name="booking_id" placeholder="Enter booking ID" required>
                    </div>

                    <div class="form-group">
                        <label>Select Location</label>
                        <select name="location_id" required>
                            <option value="">Select Location</option>
                            <?php
                            if ($location_result->num_rows > 0) {
                                while ($loc = $location_result->fetch_assoc()) {
                                    echo "<option value='{$loc['Location_ID']}'>
                                            {$loc['City']} - {$loc['Area']} - {$loc['State']}
                                          </option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Booking Date</label>
                        <input type="date" name="booking_date" required>
                    </div>

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label>Number of Trucks</label>
                        <input type="number" name="no_trucks" placeholder="Enter number of trucks" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Book Trucks</button>
                    <a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </main>

</div>

</body>
</html>