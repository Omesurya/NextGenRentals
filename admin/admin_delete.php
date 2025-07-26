<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../db.php';

$message = '';

// Delete logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    // First delete image file from server
    $stmt = $conn->prepare("SELECT image_url FROM items WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($image_url);
    if ($stmt->fetch()) {
        $image_path = "../" . $image_url;
        if (file_exists($image_path)) {
            unlink($image_path); // Delete image from filesystem
        }
    }
    $stmt->close();

    // Then delete from DB
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Item deleted successfully!";
    } else {
        $message = "Failed to delete item.";
    }
}

// Fetch all items
$items = [];
$result = $conn->query("SELECT id, name, description, image_url, price FROM items ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Item - Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .dashboard-container {
      max-width: 800px;
      margin: 50px auto;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .item {
      display: flex;
      align-items: center;
      margin: 20px 0;
      padding: 15px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .item img {
      width: 100px;
      height: auto;
      border-radius: 6px;
      margin-right: 20px;
    }
    .item-info {
      flex-grow: 1;
    }
    .item-info h4 {
      margin: 0 0 5px 0;
    }
    .delete-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
    }
    .delete-btn:hover {
      background-color: #b02a37;
    }
    .success {
      color: green;
      font-weight: bold;
      text-align: center;
    }
    .top-links {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .top-links a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <main class="dashboard-container">
    <div class="top-links">
      <a href="admin_dashboard.php">← Back to Dashboard</a>
      <a href="admin_logout.php">Logout</a>
    </div>

    <h2>Delete Existing Item</h2>
    <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>

    <?php if (count($items) > 0): ?>
      <?php foreach ($items as $item): ?>
        <div class="item">
          <img src="../<?php echo htmlspecialchars($item['image_url']); ?>" alt="Item Image">
          <div class="item-info">
            <h4><?php echo htmlspecialchars($item['name']); ?> (₹<?php echo $item['price']; ?>/day)</h4>
            <p><?php echo htmlspecialchars($item['description']); ?></p>
          </div>
          <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
            <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No items found to delete.</p>
    <?php endif; ?>
  </main>
</body>
</html>
