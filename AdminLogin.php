<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: AdminDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <title>Admin Login</title>
</head>

<body>

<div class="login-container">
    <h2>Admin Login</h2>

    <form method="POST" action="AdminAuthenticate.php">

        <label for="Username">Username</label>
        <input type="text" id="Username" name="Username" required />

        <label for="Password">Password</label>
        <input type="password" id="Password" name="Password" required />

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
