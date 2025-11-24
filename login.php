<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form method="POST" action="authenticate.php">
    <label>Email:</label><br>
    <input type="email" name="email" required /><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required /><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>
