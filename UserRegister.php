<?php
session_start();
require "db.php"; // your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get POST values
    $fname = trim($_POST['FName'] ?? '');
    $minit = trim($_POST['Minit'] ?? '');
    $lname = trim($_POST['LName'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $password = trim($_POST['Password'] ?? '');
    $confirm_password = trim($_POST['ConfirmPassword'] ?? '');

    // Basic validation
    if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill out all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT CustomerID FROM Customer WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Generate sequential CustomerID (numeric only) safely
            $result = $conn->query("
                SELECT CustomerID 
                FROM Customer 
                WHERE CustomerID REGEXP '^C[0-9]+$' 
                ORDER BY LENGTH(CustomerID) DESC, CustomerID DESC 
                LIMIT 1
            ");
            $lastIdRow = $result->fetch_assoc();
            $lastId = $lastIdRow ? $lastIdRow['CustomerID'] : null;

            if ($lastId) {
                $num = (int) substr($lastId, 1); // numeric part
                $newNum = $num + 1;
            } else {
                $newNum = 1;
            }

            $newCustomerId = "C" . $newNum;

            // Hash password
            $hashed_password = hash('sha256', $password);

            // Insert new user
            $insert = $conn->prepare("
                INSERT INTO Customer (CustomerID, FName, Minit, LName, Email, Password, RegistrationDate)
                VALUES (?, ?, ?, ?, ?, ?, CURDATE())
            ");
            $insert->bind_param("ssssss", $newCustomerId, $fname, $minit, $lname, $email, $hashed_password);

            if (!$insert->execute()) {
                die("Registration failed: (" . $insert->errno . ") " . $insert->error);
            } else {
                // Store CustomerID in session
                $_SESSION['user_id'] = $newCustomerId;
                header("Location: UserDashboard.php");
                exit();
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

    <?php if (!empty($error)) echo "<p style='color:#ff4d4d;'>$error</p>"; ?>

    <form method="POST" action="UserRegister.php">
        <label for="FName">First Name*</label>
        <input type="text" id="FName" name="FName" required />

        <label for="Minit">Middle Initial</label>
        <input type="text" id="Minit" name="Minit" maxlength="1" />

        <label for="LName">Last Name*</label>
        <input type="text" id="LName" name="LName" required />

        <label for="Email">Email*</label>
        <input type="text" id="Email" name="Email" required />

        <label for="Password">Password*</label>
        <input type="password" id="Password" name="Password" required />

        <label for="ConfirmPassword">Confirm Password*</label>
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
