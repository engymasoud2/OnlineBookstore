<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: UserLogin.php");
    exit();
}

// Fetch all orders with customer info
$query = "
    SELECT 
        o.OrderID,
        o.OrderDate,
        o.ShippingStat,
        o.Amount,
        c.FName,
        c.Minit,
        c.LName,
        c.Email,
        c.Phone,
        c.HouseNo,
        c.ApartmentNo,
        c.Street,
        c.ZipCode
    FROM CustomerOrder o
    LEFT JOIN Customer c ON o.CUID = c.CustomerID
    ORDER BY o.OrderDate DESC
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        .order-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            padding: 30px 0;
        }

        .order-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
            transition: transform 0.2s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
        }

        .order-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .info-row {
            margin: 8px 0;
            font-size: 15px;
            color: #444;
        }

        .info-label {
            font-weight: bold;
        }
    </style>
</head>
<body class="dashboard-page">

<?php include 'AdminHeader.php'; ?>

<div class="dashboard-container" style="padding:40px; max-width:1100px; margin:0 auto;">
    
    <h2>Reservation List</h2>

    <a href="AdminDashboard.php" class="back-btn" 
       style="display:inline-block; margin-bottom:20px;">
       ← Back to Dashboard
    </a>

    <div class="order-grid">

        <?php while ($order = $result->fetch_assoc()): ?>
            <div class="order-card">

                <div class="order-title">
                    🧾 Order ID: <?php echo htmlspecialchars($order['OrderID']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📅 Date:</span>
                    <?php echo htmlspecialchars($order['OrderDate']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📦 Shipping Status:</span>
                    <?php echo htmlspecialchars($order['ShippingStat']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">💵 Amount:</span>
                    $<?php echo htmlspecialchars($order['Amount']); ?>
                </div>

                <hr style="margin: 12px 0; border: 0; height: 1px; background:#ddd;">

                <div class="info-row">
                    <span class="info-label">👤 Customer:</span>
                    <?php 
                        echo htmlspecialchars(
                            $order['FName'] . " " . 
                            ($order['Minit'] ? $order['Minit'] . ". " : "") . 
                            $order['LName']
                        );
                    ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📧 Email:</span>
                    <?php echo htmlspecialchars($order['Email']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📞 Phone:</span>
                    <?php echo htmlspecialchars($order['Phone']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">🏠 Address:</span>
                    <?php 
                        echo htmlspecialchars($order['HouseNo']) . " " .
                             htmlspecialchars($order['Street']);

                        if (!empty($order['ApartmentNo']))
                            echo ", Apt " . htmlspecialchars($order['ApartmentNo']);
                    ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📮 Zip Code:</span>
                    <?php echo htmlspecialchars($order['ZipCode']); ?>
                </div>

            </div>
        <?php endwhile; ?>

    </div>

</div>

</body>
</html>
