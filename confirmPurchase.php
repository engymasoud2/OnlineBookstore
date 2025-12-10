<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['isbn']) || !isset($_POST['price']) || !isset($_POST['title'])) {
    die("Invalid order request.");
}

$userId = $_SESSION['user_id'];
$isbn = $_POST['isbn'];
$price = $_POST['price'];
$title = $_POST['title'];

// Generate unique order ID (e.g., ORD_20250203_xxxx)
$orderID = "ORD_" . date("Ymd_His") . "_" . rand(1000, 9999);

// Insert order record
$stmt = $conn->prepare("
    INSERT INTO CustomerOrder (OrderID, ADID, OrderDate, ShippingStat, Amount, CUID)
    VALUES (?, NULL, NOW(), 'Processing', ?, ?)
");
$stmt->bind_param("sis", $orderID, $price, $userId);
$stmt->execute();
?>

<script>
alert("Order placed successfully!");
window.location.href = "UserOrders.php";
</script>
