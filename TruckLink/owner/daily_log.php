<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header("Location: ../login.php");
    exit();
}

$message = "";
$message_type = "";
$ownerName = $_SESSION['username'] ?? 'Owner';
$owner_id = $_SESSION['ref_id'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $log_id = trim($_POST['log_id']);
    $truck_id = trim($_POST['truck_id']);
    $log_date = trim($_POST['log_date']);
    $distance = trim($_POST['distance']);
    $fuel_used = trim($_POST['fuel_used']);
    $fuel_cost = trim($_POST['fuel_cost']);
    $driver_name = trim($_POST['driver_name']);

    // Check duplicate log ID
    $check_log = "SELECT * FROM Truck_Daily_Log WHERE Log_ID='$log_id'";
    $log_result = $conn->query($check_log);

    // Check if truck belongs to this owner
    $check_truck = "SELECT * FROM Truck WHERE Truck_ID='$truck_id' AND Owner_ID='$owner_id'";
    $truck_result = $conn->query($check_truck);

    if ($log_result && $log_result->num_rows > 0) {
        $message = "Log ID already exists.";
        $message_type = "error";
    } elseif (!$truck_result || $truck_result->num_rows == 0) {
        $message = "Truck ID not found for this owner.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO Truck_Daily_Log
        (Log_ID, Truck_ID, Log_Date, Distance, Fuel_Used, Fuel_Cost, Driver_Name)
        VALUES
        ('$log_id', '$truck_id', '$log_date', '$distance', '$fuel_used', '$fuel_cost', '$driver_name')";

        if ($conn->query($sql) === TRUE) {
            $message = "Log added successfully!";
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
    <title>Daily Log - TruckLink</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-container">

    <!-- Sidebar -->
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
            <li><a href="daily_log.php" class="active">Daily Log</a></li>
            <li><a href="view_logs.php">View Logs</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h1>Truck Daily Log</h1>
                <p>Record daily truck operations</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($ownerName); ?>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="section-header">
                <h3>Add Daily Log</h3>
                <p class="form-subtitle">Enter trip and fuel details for your truck</p>
            </div>

            <!-- Message -->
            <?php if ($message != "") { ?>
                <div class="alert <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <!-- Form -->
            <form method="post" class="truck-form">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Log ID</label>
                        <input type="number" name="log_id" placeholder="Enter log ID" required>
                    </div>

                    <div class="form-group">
                        <label>Truck ID</label>
                        <input type="number" name="truck_id" placeholder="Enter your truck ID" required>
                    </div>

                    <div class="form-group">
                        <label>Log Date</label>
                        <input type="date" name="log_date" required>
                    </div>

                    <div class="form-group">
                        <label>Distance (km)</label>
                        <input type="number" name="distance" placeholder="Enter distance" required>
                    </div>

                    <div class="form-group">
                        <label>Fuel Used (liters)</label>
                        <input type="number" name="fuel_used" placeholder="Enter fuel used" required>
                    </div>

                    <div class="form-group">
                        <label>Fuel Cost</label>
                        <input type="number" name="fuel_cost" placeholder="Enter fuel cost" required>
                    </div>

                    <div class="form-group">
                        <label>Driver Name</label>
                        <input type="text" name="driver_name" placeholder="Enter driver name" required>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Log</button>
                    <a href="view_logs.php" class="btn btn-light">View Logs</a>
                </div>

            </form>
        </div>

    </main>
</div>

</body>
</html>