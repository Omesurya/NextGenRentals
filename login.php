<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            echo "<script>alert('Login successful'); window.location='dashboard.php';</script>";

        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">NextGen Rentals</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="admin/admin_login.php">Admin</a></li>
        <li><a href="login.php">Login/Register</a></li>
        <li><a href="equipment_details.php">Equipment Details</a></li>
      </ul>
    </nav>
  </header>

  <main class="form-container">
    <h2>Login</h2>
    <form method="post" action="login.php">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn-blue">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
  </main>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> NextGen Rentals. All rights reserved.</p>
  </footer>
</body>
</html>
