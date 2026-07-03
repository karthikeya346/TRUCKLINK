<?php
session_start();
include("../db_connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header("Location: ../login.php");
    exit();
}

$message = "";
$message_type = "";
$owner_id = $_SESSION['ref_id'];
$ownerName = $_SESSION['username'] ?? 'Owner';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $truck_id = trim($_POST['truck_id']);
    $truck_number = trim($_POST['truck_number']);
    $truck_type = trim($_POST['truck_type']);
    $capacity = trim($_POST['capacity']);
    $status = trim($_POST['status']);

    $check = "SELECT * FROM Truck WHERE Truck_ID='$truck_id'";
    $check_result = $conn->query($check);

    if ($check_result && $check_result->num_rows > 0) {
        $message = "Truck ID already exists.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO Truck (Truck_ID, Owner_ID, Truck_Number, Truck_Type, Capacity, Status)
                VALUES ('$truck_id', '$owner_id', '$truck_number', '$truck_type', '$capacity', '$status')";

        if ($conn->query($sql) === TRUE) {
            $message = "Truck added successfully.";
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
    <title>Add Truck - TruckLink</title>
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
            <li><a href="add_truck.php" class="active">Add Truck</a></li>
            <li><a href="my_trucks.php">My Trucks</a></li>
            <li><a href="daily_log.php">Daily Log</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Add Truck</h1>
                <p>Register a new truck into your fleet</p>
            </div>
            <div class="user-badge">
                Hello, <?php echo htmlspecialchars($ownerName); ?>
            </div>
        </div>

        <div class="form-card">
            <div class="section-header">
                <h3>Truck Details</h3>
                <p class="form-subtitle">Enter all required truck information below.</p>
            </div>

            <?php if ($message != "") { ?>
                <div class="alert <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="post" class="truck-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Truck ID</label>
                        <input type="number" name="truck_id" placeholder="Enter truck ID" required>
                    </div>

                    <div class="form-group">
                        <label>Truck Number</label>
                        <input type="text" name="truck_number" placeholder="Enter truck number" required>
                    </div>

                    <div class="form-group">
                        <label>Truck Type</label>
                        <input type="text" name="truck_type" placeholder="Enter truck type" required>
                    </div>

                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="text" name="capacity" placeholder="Enter capacity" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="">Select Status</option>
                            <option value="Available">Available</option>
                            <option value="Booked">Booked</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Truck</button>
                    <a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>