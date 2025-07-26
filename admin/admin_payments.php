<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rental Payments - Admin</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px 15px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Rental Payment Records</h2>

    <table>
      <thead>
        <tr>
          <th>Username</th>
          <th>Item Name</th>
          <th>Days Rented</th>
          <th>Amount Paid (₹)</th>
          <th>Payment Mode</th>
          <th>Payment Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "
          SELECT u.username, i.name AS item_name, p.rental_days, p.amount_paid, p.payment_mode, p.payment_date
          FROM payments p
          JOIN users u ON p.user_id = u.id
          JOIN items i ON p.item_id = i.id
          ORDER BY p.payment_date DESC
        ";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['item_name']) . "</td>
                        <td>" . $row['rental_days'] . "</td>
                        <td>" . number_format($row['amount_paid'], 2) . "</td>
                        <td>" . htmlspecialchars($row['payment_mode']) . "</td>
                        <td>" . $row['payment_date'] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No payments found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <a class="back-link" href="admin_dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>
