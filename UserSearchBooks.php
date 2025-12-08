<?php
session_start();
require "db.php";

// Search logic
$results = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $isbn = trim($_POST['isbn']);
  $title = trim($_POST['title']);

  if ($isbn === "" && $title === "") {
    $error = "Please enter an ISBN or Title.";
  } else {
    if ($isbn !== "") {
      $stmt = $conn->prepare("SELECT * FROM Book WHERE ISBN = ?");
      $stmt->bind_param("s", $isbn);
    } else {
      $searchTitle = "%$title%";
      $stmt = $conn->prepare("SELECT * FROM Book WHERE Title LIKE ?");
      $stmt->bind_param("s", $searchTitle);
    }

    $stmt->execute();
    $query = $stmt->get_result();
    $results = $query->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Search Books</title>
  <link rel="stylesheet" href="styles.css">

  <style>
    body {
      background: linear-gradient(135deg, #8e44ad, #6c5ce7);
      font-family: "Poppins", sans-serif;
      color: white;
      padding-top: 80px;
    }

    .search-wrapper {
      max-width: 700px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.15);
      padding: 30px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
      text-align: center;
    }

    .search-wrapper h2 {
      margin-bottom: 20px;
      font-size: 26px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      margin-bottom: 18px;
      border-radius: 8px;
      border: none;
      font-size: 16px;
    }

    .search-btn,
    .back-btn {
      background-color: #6c5ce7;
      border: none;
      color: white;
      padding: 12px;
      width: 100%;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.2s;
    }

    .search-btn:hover {
      background-color: #5a4bd2;
      transform: scale(1.03);
    }

    .back-btn {
      margin-top: 15px;
      background-color: #e74c3c;
    }

    .back-btn:hover {
      background-color: #c0392b;
    }

    table {
      width: 90%;
      margin: 35px auto;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(6px);
      border-radius: 12px;
      overflow: hidden;
    }

    th,
    td {
      padding: 14px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      text-align: center;
      color: white;
    }

    th {
      background: rgba(0, 0, 0, 0.2);
    }

    tr:last-child td {
      border-bottom: none;
    }

    .no-results {
      text-align: center;
      margin-top: 25px;
      font-size: 18px;
      opacity: 0.8;
    }

    .error {
      color: #ffcccc;
      margin-bottom: 15px;
    }
  </style>
</head>

<body>

  <!-- Top Header (Matches User Dashboard UI) -->
  <div class="header">
    <div class="logo">Book Search</div>

    <nav class="nav-buttons">
      <form action="UserDashboard.php" method="post">
        <button type="submit">Back</button>
      </form>
    </nav>
  </div>

  <!-- Search Card -->
  <div class="search-wrapper">
    <h2>Find a Book</h2>

    <?php if ($error): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">

      <label>Search by ISBN</label>
      <input type="text" name="isbn" placeholder="Enter ISBN">

      <label>OR Search by Title</label>
      <input type="text" name="title" placeholder="Enter Book Title">

      <button class="search-btn" type="submit">Search</button>

    </form>

    <form action="UserDashboard.php">
      <button class="back-btn">← Back to Dashboard</button>
    </form>
  </div>

  <!-- Results Section -->
  <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>

    <?php if (count($results) > 0): ?>
      <table>
        <tr>
          <th>ISBN</th>
          <th>Title</th>
          <th>Edition</th>
          <th>Price</th>
          <th>Year</th>
          <th>Stock</th>
        </tr>

        <?php foreach ($results as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['ISBN']); ?></td>
            <td><?= htmlspecialchars($row['Title']); ?></td>
            <td><?= htmlspecialchars($row['Edition']); ?></td>
            <td>$<?= htmlspecialchars($row['Price']); ?></td>
            <td><?= htmlspecialchars($row['Pub_year']); ?></td>
            <td><?= htmlspecialchars($row['StockNum']); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>

    <?php else: ?>
      <p class="no-results">No books found.</p>
    <?php endif; ?>

  <?php endif; ?>

</body>

</html>