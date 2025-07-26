<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure hash

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: Email already exists.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">NextGen Rentals</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="admin/dashboard.php">Admin</a></li>
        <li><a href="login.php">Login/Register</a></li>
        <li><a href="equipment_details.php">Equipment Details</a></li>
      </ul>
    </nav>
  </header>

  <main class="form-container">
    <h2>Create Account</h2>
    <form method="post" action="register.php">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn-blue">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
  </main>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> NextGen Rentals. All rights reserved.</p>
  </footer>
</body>
</html>
