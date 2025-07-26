<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - NextGen Rentals</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
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

    .dashboard-container {
      max-width: 1100px;
      margin: 60px auto;
      background: #ffffff;
      padding: 60px;
      border-radius: 16px;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .dashboard-container h2 {
      margin-bottom: 30px;
      font-size: 2.5rem;
      color: #333;
    }

    .dashboard-container p {
      font-size: 1.2rem;
      color: #555;
      margin-bottom: 40px;
    }

    .item-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 40px;
    }

    .item-card {
      width: 250px;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .item-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .item-card .details {
      padding: 15px;
      text-align: left;
    }

    .item-card h4 {
      margin: 0;
    }

    .item-card p {
      margin: 8px 0;
      font-size: 0.9em;
      color: #555;
    }

    .item-card .btn-blue {
      display: block;
      margin-top: 10px;
      padding: 10px;
      width: 100%;
      background-color: #007bff;
      color: #fff;
      text-align: center;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .item-card .btn-blue:hover {
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

  <!-- Header -->
  <header>
    <nav class="navbar">
      <div class="logo">NextGen Rentals</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <!-- Dashboard Content -->
  <main class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>This is your dashboard. You can explore our available equipment below.</p>

    <h3 style="margin-top: 50px;">Available Equipment for Rent</h3>

    <div class="item-grid">
      <?php
        include 'db.php';
        $sql = "SELECT id, name, description, image_url, price FROM items ORDER BY id DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
      ?>
        <div class="item-card">
          <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Item Image">
          <div class="details">
            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>₹<?php echo number_format($row['price'], 2); ?></strong> / day</p>
            <form method="POST" action="payment.php">
              <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn-blue">Rent Now</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; <?php echo date("Y"); ?> NextGen Rentals. All rights reserved.</p>
  </footer>

</body>
</html>
