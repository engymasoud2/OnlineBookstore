<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user orders
$stmt = $conn->prepare("SELECT OrderID, OrderDate, ShippingStat, Amount FROM CustomerOrder WHERE CUID = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Horizontal Card Styling */
        .order-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
            border-radius: 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            flex-wrap: wrap;
            gap: 20px;
        }

        .order-info, .tracking-info {
            flex: 1;
            min-width: 250px;
        }

        .tracking-info {
            text-align: right;
            border-left: 1px solid rgba(255,255,255,0.3);
            padding-left: 20px;
        }

        .order-card h3, .tracking-info h4 {
            margin-bottom: 10px;
        }

        .order-card p {
            margin: 4px 0;
        }

        .back-btn {
            display: inline-block;
            text-decoration: none;
            background-color: #6c5ce7;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            transition: 0.2s;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background-color: #5a4bd2;
            transform: scale(1.03);
        }
    </style>
</head>
<body class="dashboard-page">

<?php include 'UserHeader.php'; ?>

<div class="dashboard-container" style="max-width:1000px; margin:auto; padding:20px;">

    <h2>My Orders</h2>
    <a href="UserDashboard.php" class="back-btn">⬅ Back to Dashboard</a>

    <?php if (empty($orders)): ?>
        <p style="margin-top:20px; font-size:18px; color:#ddd;">You have no orders yet.</p>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:20px; margin-top:20px;">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                  <!-- Order Info -->
                  <div class="order-info">
                      <h3>Order ID: <?php echo htmlspecialchars($order['OrderID']); ?></h3>
                      <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['OrderDate']); ?></p>
                      <p><strong>Amount:</strong> $<?php echo htmlspecialchars($order['Amount']); ?></p>
                      <p>
                          <strong>Status:</strong> 
                          <span class="status-badge <?php echo strtolower($order['ShippingStat']); ?>">
                              <?php echo htmlspecialchars($order['ShippingStat']); ?>
                          </span>
                      </p>
                  </div>


                    <!-- Fake Tracking Info -->
                    <div class="tracking-info">
                        <h4>Tracking Info</h4>
                        <p>Carrier: FastShip</p>
                        <p>ETA: 3-5 days</p>
                        <p>Last Location: City Hub</p>
                        <p>Tracking #: <?php echo substr($order['OrderID'],0,6).'XXXX'; ?></p>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
