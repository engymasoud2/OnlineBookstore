<?php
session_start();
require "db.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT FName, LName FROM Customer WHERE CustomerId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($firstName, $lastName);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dashboard-page">

<?php include 'UserHeader.php'; ?>

<div class="dashboard-container" style="display:flex; gap:20px; align-items:flex-start;">

    <!-- Sidebar: Recommended Books -->
    <div class="sidebar" style="flex:1; max-width:250px;">
        <h3>Recommended Books</h3>
        <div class="book-card">
            <h4>The Great Gatsby</h4>
            <h7>"Mindblowing read. Highly recommended!!"</h7>
            <p>⭐⭐⭐⭐☆</p>
        </div>
        <div class="book-card">
            <h4>1984</h4>
            <h7>"the five stars say it all"</h7>
            <p>⭐⭐⭐⭐⭐</p>
        </div>
        <div class="book-card">
            <h4>To Kill a Mockingbird</h4>
            <h7>"my type of read"</h7>
            <p>⭐⭐⭐⭐☆</p>
        </div>
        <div class="book-card">
            <h4>Pride and Prejudice</h4>
            <h7>"Best words put on paper."</h7>
            <p>⭐⭐⭐⭐⭐</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="flex:3;">
        <h2>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h2>

    <form action="UserSearchBooks.php" method="GET" style="display:flex; gap:10px; margin:20px 0;">
        <input type="text" name="query" placeholder="Search books by title or ISBN..." required />
        <button type="submit">Search Books</button>
    </form>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards" style="display:flex; gap:20px; flex-wrap:wrap;">
            <a href="UserAccountInfo.php" class="card">
                <div class="card-icon">👤</div>
                <h3>Account Information</h3>
                <p>View and edit your account details</p>
            </a>

            <a href="UserOrders.php" class="card">
                <div class="card-icon">📦</div>
                <h3>My Orders</h3>
                <p>View your past orders</p>
            </a>
        </div>
    </div>

</div>

</body>
</html>
