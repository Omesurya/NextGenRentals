<?php
include 'header.php';
include 'db.php';

// Fetch items from database
$sql = "SELECT name, description, image_url, price FROM items ORDER BY id DESC";
$result = $conn->query($sql);
?>

<main class="full-bg">
  <div class="overlay-content">
    <h1>Welcome to NextGen Rentals</h1>
    <p>Rent high-end video cameras, gaming PCs, monitors, and more with ease.</p>
    <a href="equipment.php" class="btn-blue">Browse Equipment</a>
  </div>
</main>

<section style="padding: 40px; max-width: 1200px; margin: auto;">
  <h2 style="text-align:center;">Available Equipment</h2>
  <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div style="width: 250px; border: 1px solid #ddd; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); background: #fff;">
          <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 100%; height: 180px; object-fit: cover;">
          <div style="padding: 15px;">
            <h3 style="margin: 0;"><?php echo htmlspecialchars($row['name']); ?></h3>
            <p style="font-size: 0.9em; color: #555;"><?php echo htmlspecialchars($row['description']); ?></p>
            <p style="font-weight: bold;">₹<?php echo number_format($row['price'], 2); ?> / day</p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align: center;">No items available at the moment.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>
