<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch rented items
$sql = "SELECT rentals.item_id, rentals.rental_date, items.name, items.description, items.image_url, items.price
        FROM rentals
        JOIN items ON rentals.item_id = items.id
        WHERE rentals.user_id = ?
        ORDER BY rentals.rental_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Rentals - NextGen Rentals</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
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
      max-width: 1100px;
      margin: 60px auto;
      background: #ffffff;
      padding: 60px;
      border-radius: 16px;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
    }

    .container h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 2.5rem;
      color: #333;
    }

    .rental-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .rental-card {
      width: 250px;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      background: #fff;
    }

    .rental-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .rental-card .details {
      padding: 15px;
    }

    .rental-card h4 {
      margin: 0;
    }

    .rental-card p {
      margin: 6px 0;
      font-size: 0.9em;
      color: #555;
    }

    .order-button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s ease;
    }

    .order-button:hover {
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
    <div class="logo">ProGear Rentals</div>
    <ul class="nav-links">
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="container">
    <h2><?php echo htmlspecialchars($username); ?>'s Rentals</h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="rental-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="rental-card">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Item Image">
            <div class="details">
              <h4><?php echo htmlspecialchars($row['name']); ?></h4>
              <p><?php echo htmlspecialchars($row['description']); ?></p>
              <p><strong>₹<?php echo number_format($row['price'], 2); ?></strong> / day</p>
              <p>Rented on: <?php echo date('d M Y, h:i A', strtotime($row['rental_date'])); ?></p>
              <form method="POST" action="order.php">
                <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                <button type="submit" class="order-button">Order</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;">You haven't rented any items yet.</p>
    <?php endif; ?>
  </div>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> ProGear Rentals. All rights reserved.</p>
  </footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
