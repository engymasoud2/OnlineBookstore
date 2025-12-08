<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['Username'] ?? '';
    $password = $_POST['Password'] ?? '';

    $stmt = $conn->prepare("SELECT AdminId, Password FROM Administrator WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $stmt->bind_result($adminId, $hashed_password);
        $stmt->fetch();
        $input_hash_sha256 = hash('sha256', $password);


        if ($hashed_password === $input_hash_sha256) {

            $_SESSION['user_id'] = $adminId;
            header("Location: AdminDashboard.php");
            exit();

        } else {
            echo "Incorrect password.";
        }

    } else {
        echo "Username not found.";
    }

    $stmt->close();
}
?>
