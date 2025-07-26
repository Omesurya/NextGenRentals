<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check against the admins table
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $stored_password);
        $stmt->fetch();

        // Plain-text comparison (for demo only)
        if ($password === $stored_password) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $username;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - NextGen Rentals</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .form-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 40px;
      background: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .form-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .btn-blue {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn-blue:hover {
      background-color: #0056b3;
    }
    .error-msg {
      color: red;
      margin-top: 10px;
    }
    .demo-info {
      margin-top: 15px;
      color: #444;
      font-size: 14px;
    }
    code {
      background: #eee;
      padding: 2px 4px;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <main class="form-container">
    <h2>Admin Login</h2>
    <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
    <form method="post" action="">
      <input type="email" name="email" placeholder="Admin Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" class="btn-blue">Login</button>
    </form>

    <!-- ✅ Demo credentials -->
    <div class="demo-info">
      <strong>Demo Credentials:</strong><br>
      Email: <code>admin@gmail.com</code><br>
      Password: <code>admin1</code>
    </div>

    <p><a href="../index.php">Back to Home</a></p>
  </main>
</body>
</html>
