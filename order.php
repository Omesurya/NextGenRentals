<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}

include 'db.php';

if (!isset($_POST['item_id'])) {
    echo "<script>alert('Invalid request'); window.location='dashboard.php';</script>";
    exit();
}

$item_id = intval($_POST['item_id']);
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get item details
$sql = "SELECT name, description, image_url, price FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Item not found'); window.location='dashboard.php';</script>";
    exit();
}

$item = $result->fetch_assoc();

// Insert into rentals table (simulate placing the order)
$order_sql = "INSERT INTO rentals (user_id, item_id, rental_date) VALUES (?, ?, NOW())";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("ii", $user_id, $item_id);
$order_stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - NextGen Rentals</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f8;
      margin: 0;
    }

    .navbar {
      background-color: #007bff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 30px;
      color: white;
      font-weight: bold;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 30px;
      margin: 0;
      padding: 0;
    }

    .nav-links li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 18px;
      padding: 10px 16px;
      border-radius: 6px;
      transition: background-color 0.3s;
    }

    .nav-links li a:hover {
      background-color: #0056b3;
    }

    .container {
      max-width: 700px;
      margin: 60px auto;
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .container img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .container h2 {
      color: #007bff;
      margin-bottom: 20px;
    }

    .container p {
      font-size: 1.1rem;
      color: #444;
      margin: 10px 0;
    }

    .btn {
      display: inline-block;
      padding: 14px 28px;
      margin-top: 30px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    footer {
      text-align: center;
      padding: 25px;
      margin-top: 60px;
      background: #007bff;
      color: white;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="logo">NextGen Rentals</div>
    <ul class="nav-links">
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="container">
    <h2>Order Confirmed!</h2>
    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Ordered Item">
    <p><strong>Item:</strong> <?php echo htmlspecialchars($item['name']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
    <p><strong>Price:</strong> ₹<?php echo number_format($item['price'], 2); ?> / day</p>
    <p>Thank you, <?php echo htmlspecialchars($username); ?>! Your order has been placed successfully.</p>

    <a href="dashboard.php" class="btn">Back to Dashboard</a>
    <a href="equipment.php" class="btn" style="margin-left: 15px;">Browse More</a>
  </div>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> NextGen Rentals. All rights reserved.</p>
  </footer>

</body>
</html>

<?php
$stmt->close();
$order_stmt->close();
$conn->close();
?>
