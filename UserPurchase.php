<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['isbn']) || !isset($_GET['price']) || !isset($_GET['title'])) {
    die("Invalid purchase request.");
}

$isbn = $_GET['isbn'];
$price = $_GET['price'];
$title = $_GET['title'];
$userId = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT FName, LName, Email, Phone, Street, HouseNo, ApartmentNo, ZipCode 
                        FROM Customer 
                        WHERE CustomerID = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Purchase</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .purchase-card {
            background:white;
            padding:20px;
            border-radius:10px;
            border:1px solid #ddd;
            margin-bottom:20px;
            color:black;
        }
        .purchase-title {
            font-size:22px;
            margin-bottom:10px;
            font-weight:bold;
        }
        .confirm-btn {
            background:green;
            color:white;
            padding:10px 16px;
            border:none;
            border-radius:6px;
            cursor:pointer;
            font-size:16px;
        }
        .confirm-btn:hover {
            background:#0d7a0d;
        }
        .cancel-btn {
            background:#555;
            color:white;
            padding:10px 16px;
            border:none;
            border-radius:6px;
            cursor:pointer;
            margin-left:10px;
            font-size:16px;
        }
    </style>
</head>

<body class="dashboard-page">

<?php include "UserHeader.php"; ?>

<div class="dashboard-container" style="max-width:900px; margin:auto; padding:20px;">

    <h2>Review Your Purchase</h2>

    <button onclick="window.history.back()"
        style="
            padding:8px 14px; 
            margin-bottom:20px;
            background:#555; 
            color:white; 
            border:none; 
            border-radius:5px; 
            cursor:pointer;">
        ⬅ Back
    </button>

    <!-- USER INFO -->
    <div class="purchase-card">
        <div class="purchase-title">Your Information</div>
        <p><strong>Name:</strong> <?= $user['FName']." ".$user['LName']; ?></p>
        <p><strong>Email:</strong> <?= $user['Email']; ?></p>
        <p><strong>Phone:</strong> <?= $user['Phone']; ?></p>
        <p><strong>Address:</strong> 
            <?= $user['HouseNo']." ".$user['Street']; ?>,
            Apt <?= $user['ApartmentNo']; ?>,
            <?= $user['ZipCode']; ?>
        </p>
    </div>

    <!-- ORDER INFO -->
    <div class="purchase-card">
        <div class="purchase-title">Order Summary</div>
        <p><strong>Book:</strong> <?= htmlspecialchars($title); ?></p>
        <p><strong>ISBN:</strong> <?= $isbn; ?></p>
        <p><strong>Total:</strong> $<?= $price; ?></p>
        <p><strong>Payment Method:</strong> Visa •••• 4421</p>
    </div>

    <!-- FORM THAT POSTS TO CONFIRM PURCHASE -->
    <form action="confirmPurchase.php" method="POST">
        <input type="hidden" name="isbn" value="<?= $isbn ?>">
        <input type="hidden" name="price" value="<?= $price ?>">
        <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">

        <button type="submit" class="confirm-btn">Confirm Purchase</button>
        <button type="button" class="cancel-btn" onclick="window.location.href='UserDashboard.php'">Cancel</button>
    </form>

</div>

</body>
</html>
