<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - ProGear Rentals</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .dashboard-container {
      max-width: 600px;
      margin: 80px auto;
      padding: 40px;
      background: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      text-align: center;
    }
    .dashboard-container h2 {
      margin-bottom: 20px;
    }
    .action-btn {
      display: block;
      margin: 15px auto;
      padding: 12px 20px;
      width: 60%;
      background-color: #007bff;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
      text-decoration: none;
    }
    .action-btn:hover {
      background-color: #0056b3;
    }
    .top-links {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
    }
    .top-links a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <main class="dashboard-container">
    <div class="top-links">
      <a href="../index.php">← Back to Home</a>
      <a href="admin_login.php">Logout</a>
    </div>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
    <p>Select an action below:</p>

    <a href="admin_insert.php" class="action-btn">➕ Insert New Item</a>
    <a href="admin_delete.php" class="action-btn">🗑️ Delete Existing Item</a>
    <a href="admin_modify.php" class="action-btn">✏️ Modify Existing Item</a>
    <a href="admin_payments.php" class="action-btn">💰 View Rental Payments</a>

  </main>
</body>
</html>
