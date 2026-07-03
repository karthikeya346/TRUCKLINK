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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location_id = trim($_POST['location_id']);
    $city = trim($_POST['city']);
    $area = trim($_POST['area']);
    $state = trim($_POST['state']);
    $pincode = trim($_POST['pincode']);

    $check = "SELECT * FROM Location WHERE Location_ID='$location_id'";
    $check_result = $conn->query($check);

    if ($check_result && $check_result->num_rows > 0) {
        $message = "Location ID already exists.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO Location (Location_ID, City, Area, State, Pincode)
                VALUES ('$location_id', '$city', '$area', '$state', '$pincode')";

        if ($conn->query($sql) === TRUE) {
            $message = "Location added successfully.";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location - TruckLink</title>
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
                <h1>Add New Location</h1>
                <p>Create a new service location for TruckLink</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($adminName); ?>
            </div>
        </div>

        <div class="form-card">
            <div class="section-header">
                <h3>Location Details</h3>
                <p class="form-subtitle">Enter the city, area, state, and pincode details below.</p>
            </div>

            <?php if ($message != "") { ?>
                <div class="alert <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="post" class="truck-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Location ID</label>
                        <input type="number" name="location_id" placeholder="Enter location ID" required>
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Enter city name" required>
                    </div>

                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" name="area" placeholder="Enter area name" required>
                    </div>

                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" placeholder="Enter state name" required>
                    </div>

                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="number" name="pincode" placeholder="Enter pincode" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Location</button>
                    <a href="manage_locations.php" class="btn btn-light">Back to Locations</a>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>