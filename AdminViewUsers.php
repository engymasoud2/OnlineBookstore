<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: UserLogin.php");
    exit();
}

// Fetch all customers
$query = "SELECT CustomerId, FName, LName, Email, Phone, Street, ZipCode FROM Customer";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers</title>
    <link rel="stylesheet" href="styles.css">

    <style>
            .customer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            padding: 30px 0;
        }

        .customer-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
            transition: transform 0.2s ease;
        }


        .customer-card:hover {
            transform: translateY(-5px);
        }

        .customer-name {
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
            margin-right: 5px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
        }
    </style>

</head>
<body class="dashboard-page">

<?php include 'AdminHeader.php'; ?>

<div class="dashboard-container" style="padding: 40px; max-width: 1100px; margin: 0 auto;">
    <h2>All Customers</h2>
    <a href="AdminDashboard.php" class="back-btn" 
        style="display:inline-block; margin-bottom:10px; margin-top:10px">
        ← Back to Dashboard
    </a>

    <div class="customer-grid">

        <?php while ($cust = $result->fetch_assoc()): ?>
            <div class="customer-card">

                <div class="customer-name">
                    👤 <?php echo htmlspecialchars($cust['FName'] . " " . $cust['LName']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📧 Email:</span>
                    <?php echo htmlspecialchars($cust['Email']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📞 Phone:</span>
                    <?php echo htmlspecialchars($cust['Phone']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">🏠 Address:</span>
                    <?php echo htmlspecialchars($cust['Street']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">📮 Zip Code:</span>
                    <?php echo htmlspecialchars($cust['ZipCode']); ?>
                </div>

            </div>
        <?php endwhile; ?>

    </div>

    <a href="AdminDashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
