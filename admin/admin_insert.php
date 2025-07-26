<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    $target_dir = "../uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;

    // Optional: check file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowedTypes)) {
        $message = "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Save image path in database
        $relative_path = "uploads/" . $image_name;
        $stmt = $conn->prepare("INSERT INTO items (name, description, image_url, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $name, $description, $relative_path, $price);
        if ($stmt->execute()) {
            $message = "Item added successfully!";
        } else {
            $message = "Error adding item to database.";
        }
    } else if ($uploadOk) {
        $message = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - NextGen Rentals</title>
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
  </style>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">NextGen Rentals - Admin</div>
      <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="admin_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
    <h3>Add New Equipment</h3>
    <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>

    <form method="POST" action="" enctype="multipart/form-data">
      <label>Item Name:</label>
      <input type="text" name="name" required>

      <label>Description:</label>
      <textarea name="description" rows="3" required></textarea>

      <label>Upload Image:</label>
      <input type="file" name="image" accept="image/*" required>

      <label>Price per Day (₹):</label>
      <input type="number" step="0.01" name="price" required>

      <button type="submit" class="btn-blue">Add Item</button>
    </form>
  </main>
</body>
</html>
