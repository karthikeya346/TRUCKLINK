<?php
include("../db_connect.php");

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_id = trim($_POST['owner_id']);
    $owner_name = trim($_POST['owner_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $num_trucks = trim($_POST['num_trucks']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $check_user = "SELECT * FROM Users WHERE Username='$username'";
    $user_result = $conn->query($check_user);

    if ($user_result && $user_result->num_rows > 0) {
        $message = "Username already exists.";
        $message_type = "error";
    } else {
        $sql1 = "INSERT INTO TruckOwner (Owner_ID, Owner_Name, Phone_No, Email, Address, Number_of_Trucks)
                 VALUES ('$owner_id', '$owner_name', '$phone', '$email', '$address', '$num_trucks')";

        if ($conn->query($sql1) === TRUE) {
            $sql2 = "INSERT INTO Users (Username, Password, Role, Ref_ID)
                     VALUES ('$username', '$password', 'owner', '$owner_id')";

            if ($conn->query($sql2) === TRUE) {
                $message = "Truck Owner registered successfully.";
                $message_type = "success";
            } else {
                $message = "User insert failed: " . $conn->error;
                $message_type = "error";
            }
        } else {
            $message = "Owner insert failed: " . $conn->error;
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
    <title>Truck Owner Register - TruckLink</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-left">
        <div class="brand-box">
            <div class="brand-logo">
                <img src="../assets/images/trucklink-logo2.jpeg" alt="TruckLink Logo">
            </div>
            <h1>TruckLink</h1>
            <p>Join the platform as a truck owner and manage your fleet professionally.</p>
        </div>

        <div class="feature-list">
            <div class="feature-item">Register your trucks and grow your business</div>
            <div class="feature-item">Receive booking opportunities from organizations</div>
            <div class="feature-item">Track trucks and maintain daily logs easily</div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Owner Registration</h2>
            <p class="auth-subtitle">Create your truck owner account in TruckLink</p>

            <?php if ($message != "") { ?>
                <div class="auth-alert <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="post" class="auth-form">
                <div class="form-group">
                    <label>Owner ID</label>
                    <input type="number" name="owner_id" placeholder="Enter Owner ID" required>
                </div>

                <div class="form-group">
                    <label>Owner Name</label>
                    <input type="text" name="owner_name" placeholder="Enter Owner Name" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter Phone Number" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter Email">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Enter Address">
                </div>

                <div class="form-group">
                    <label>Number of Trucks</label>
                    <input type="number" name="num_trucks" placeholder="Enter Number of Trucks" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Create Username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create Password" required>
                </div>

                <button type="submit" class="auth-btn">Register</button>
            </form>

            <p class="auth-footer">
                Already have an account?
                <a href="../login.php">Go to Login</a>
            </p>
        </div>
    </div>

</div>

</body>
</html>