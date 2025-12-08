<?php
include 'AdminHeader.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Admin Dashboard</title>
</head>
<body class="dashboard-page">

<div class="dashboard-container">
    <h2>Welcome, Admin!</h2>

    <div class="dashboard-cards">
        <a href="user_list.php" class="card">
            <div class="card-icon">👤</div>
            <h3>User List</h3>
            <p>View all registered users</p>
        </a>

        <a href="reservation_list.php" class="card">
            <div class="card-icon">📄</div>
            <h3>Reservation List</h3>
            <p>View all reservations and transactions</p>
        </a>
    </div>
</div>

</body>
</html>
