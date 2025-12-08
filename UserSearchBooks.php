<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = isset($_GET['query']) ? trim($_GET['query']) : "";
$books = [];

if ($query !== "") {
    $sql = "SELECT ISBN, Title, Price, Pub_year, Edition, StockNum
            FROM Book
            WHERE ISBN LIKE ? OR Title LIKE ?";
    
    $stmt = $conn->prepare($sql);
    $search = "%$query%";
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="dashboard-page">
<?php include 'UserHeader.php'; ?>

<div class="dashboard-container" style="max-width:900px; margin:auto; padding:20px;">

    <h2>Search Results for: "<?php echo htmlspecialchars($query); ?>"</h2>

    <button onclick="window.location.href='UserDashboard.php'" 
        style="
            padding:8px 14px; 
            margin-bottom:20px;
            background:#555; 
            color:white; 
            border:none; 
            border-radius:5px; 
            cursor:pointer;">
        ⬅ Back to Dashboard
    </button>

    <?php if (empty($books)): ?>
        <p style="font-size:18px; color:#888;">No books found.</p>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:15px;">
            <?php foreach ($books as $book): ?>
                <div class="book-card" 
                    style="
                        padding:15px; 
                        border:1px solid #ddd; 
                        border-radius:8px; 
                        color:black;
                        background:white;">
                        
                    
                    <h3><?php echo htmlspecialchars($book['Title']); ?></h3>
                    <p><strong>ISBN:</strong> <?php echo $book['ISBN']; ?></p>
                    <p><strong>Price:</strong> $<?php echo $book['Price']; ?></p>
                    <p><strong>Published:</strong> <?php echo $book['Pub_year']; ?></p>
                    <p><strong>Edition:</strong> <?php echo htmlspecialchars($book['Edition']); ?></p>
                    <p><strong>Stock:</strong> <?php echo $book['StockNum']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
