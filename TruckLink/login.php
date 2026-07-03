<?php
session_start();
include("db_connect.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM Users WHERE Username='$username' AND Password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        $_SESSION['username'] = $row['Username'];
        $_SESSION['role'] = $row['Role'];
        $_SESSION['ref_id'] = $row['Ref_ID'];

        if ($row['Role'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit();
        } elseif ($row['Role'] == 'organization') {
            header("Location: organization/dashboard.php");
            exit();
        } elseif ($row['Role'] == 'owner') {
            header("Location: owner/dashboard.php");
            exit();
        } else {
            $error = "Invalid role found.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TruckLink</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-left">
        <div class="brand-box">
            <div class="brand-logo">
                <img src="assets/images/trucklink-logo2.jpeg" alt="TruckLink Logo">
            </div>
            <h1>TruckLink</h1>
            <p>Smart truck booking and logistics management platform</p>
        </div>

        <div class="feature-list">
            <div class="feature-item">Manage truck bookings with ease</div>
            <div class="feature-item">Connect organizations and truck owners</div>
            <div class="feature-item">Track trucks, bookings, and daily logs</div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p class="auth-subtitle">Login to continue to TruckLink</p>

            <?php if ($error != "") { ?>
                <div class="auth-alert error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <form method="post" class="auth-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter Username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter Password" required>
                </div>

                <button type="submit" class="auth-btn">Login</button>
            </form>

            <p class="auth-footer">
                Don’t have an account?
                <a href="index.php">Register here</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>