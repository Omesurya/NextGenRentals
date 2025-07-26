<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../db.php';

$message = '';
$item = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $imagePath = $_POST['current_image']; // default to current

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes) && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imagePath = "uploads/" . $image_name;
        } else {
            $message = "Image not uploaded. Invalid file type.";
        }
    }

    $stmt = $conn->prepare("UPDATE items SET name=?, description=?, image_url=?, price=? WHERE id=?");
    $stmt->bind_param("sssdi", $name, $description, $imagePath, $price, $id);
    if ($stmt->execute()) {
        $message = "Item updated successfully!";
    } else {
        $message = "Error updating item.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Modify Item - ProGear Rentals</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .dashboard-container {
      max-width: 700px;
      margin: 50px auto;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .btn-blue {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn-blue:hover {
      background-color: #0056b3;
    }
    .success {
      color: green;
      font-weight: bold;
    }
    .item-list {
      margin-bottom: 20px;
    }
    .item-link {
      display: block;
      margin: 5px 0;
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">NextGen Rentals - Admin</div>
      <ul class="nav-links">
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="dashboard-container">
    <h2>Modify Item</h2>

    <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>

    <?php if (!$item): ?>
      <div class="item-list">
        <h3>Select an item to modify:</h3>
        <?php
        $res = $conn->query("SELECT id, name FROM items");
        while ($row = $res->fetch_assoc()):
        ?>
          <a class="item-link" href="?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
        <input type="hidden" name="current_image" value="<?php echo $item['image_url']; ?>">

        <label>Item Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="3" required><?php echo htmlspecialchars($item['description']); ?></textarea>

        <label>Current Image:</label><br>
        <img src="../<?php echo $item['image_url']; ?>" alt="Item Image" width="150"><br><br>

        <label>Replace Image (optional):</label>
        <input type="file" name="image" accept="image/*">

        <label>Price per Day (₹):</label>
        <input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required>

        <button type="submit" name="update" class="btn-blue">Update Item</button>
      </form>
    <?php endif; ?>
  </main>
</body>
</html>
