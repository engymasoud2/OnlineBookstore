<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: UserLogin.php");
    exit();
}

// Fetch user information
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT FName, LName, Email, Phone, Street, ZipCode FROM Customer WHERE CustomerId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Information</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dashboard-page">

<?php include 'UserHeader.php'; ?>

<div class="dashboard-container" style="padding:40px; max-width:900px; margin:0 auto;">
    <h2>Account Information</h2>

    <div class="account-card">
        <div class="account-row">
            <div class="account-label">👤 Name:</div>
            <div class="account-value"><?php echo htmlspecialchars($user['FName'] . ' ' . $user['LName']); ?></div>
        </div>
        <div class="account-row">
            <div class="account-label">📧 Email:</div>
            <div class="account-value"><?php echo htmlspecialchars($user['Email']); ?></div>
        </div>
        <div class="account-row">
            <div class="account-label">📞 Phone:</div>
            <div class="account-value"><?php echo htmlspecialchars($user['Phone']); ?></div>
        </div>
        <div class="account-row">
            <div class="account-label">🏠 Address:</div>
            <div class="account-value"><?php echo htmlspecialchars($user['Street']); ?></div>
        </div>
        <div class="account-row">
            <div class="account-label">📮 Zip Code:</div>
            <div class="account-value"><?php echo htmlspecialchars($user['ZipCode']); ?></div>
        </div>
    </div>

    <div style="margin-top:20px;">
        <a href="AdminDashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
