<?php
session_start();
require "db.php"; // your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['Username'] ?? '');
    $password = trim($_POST['Password'] ?? '');
    $confirm_password = trim($_POST['ConfirmPassword'] ?? '');

    // Basic validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT CustomerId FROM Customer WHERE LOWER(Email) = LOWER(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
           
            $hashed_password = hash('sha256', $password);

            // Insert new user
            $insert = $conn->prepare("INSERT INTO Customer (Email, Password) VALUES (?, ?)");
            $insert->bind_param("ss", $email, $hashed_password);
            if ($insert->execute()) {
                $_SESSION['user_id'] = $insert->insert_id;
                header("Location: UserDashboard.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>User Registration</title>
</head>
<body>

<div class="login-container">
    <h2>User Registration</h2>

    <?php if (!empty($error)) echo "<p style='color:#ffcccb;'>$error</p>"; ?>

    <form method="POST" action="UserRegister.php">
        <label for="Email">Email</label>
        <input type="text" id="Email" name="Email" required />

        <label for="Password">Password</label>
        <input type="password" id="Password" name="Password" required />

        <label for="ConfirmPassword">Confirm Password</label>
        <input type="password" id="ConfirmPassword" name="ConfirmPassword" required />

        <button type="submit">Register</button>
    </form>

    <p class="signup-text">
        Already have an account? 
        <a href="UserLogin.php">Login</a>
    </p>
</div>

</body>
</html>
