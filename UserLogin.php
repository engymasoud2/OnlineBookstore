<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: UserDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <title>User Login</title>
</head>

<body>

<div class="login-container">
    <h2>User Login</h2>

    <form method="POST" action="UserAuthenticate.php">

        <label for="Username">Email</label>
        <input type="text" id="Username" name="Email" required />

        <label for="Password">Password</label>
        <input type="password" id="Password" name="Password" required />

        <button type="submit">Login</button>
    </form>
    <p class="signup-text">
        Don't have an account? 
        <a href="UserRegister.php">Sign Up</a>
    </p>

</div>

</body>
</html>
