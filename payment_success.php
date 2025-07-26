<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Successful</title>
  <style>
    body { font-family: Arial; background-color: #f0f2f5; text-align: center; margin-top: 100px; }
    .box {
      max-width: 500px; margin: auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 { color: #28a745; }
    a { display: inline-block; margin-top: 20px; text-decoration: none; color: #007bff; font-weight: bold; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Payment Successful!</h2>
    <p>Your order has been placed and payment has been processed.</p>
    <a href="dashboard.php">Back to Dashboard</a>
  </div>
</body>
</html>
