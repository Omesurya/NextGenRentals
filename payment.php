<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    
    $stmt = $conn->prepare("SELECT name, price FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows != 1) {
        echo "<script>alert('Item not found'); window.location='dashboard.php';</script>";
        exit();
    }
    $item = $result->fetch_assoc();
} else {
    echo "<script>alert('Invalid access'); window.location='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment - NextGen Rentals</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body { background-color: #f4f4f4; font-family: Arial, sans-serif; }
    .payment-container {
      max-width: 500px; margin: 60px auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    h2 { margin-bottom: 30px; text-align: center; }
    label { display: block; margin-top: 15px; margin-bottom: 5px; }
    input[type="text"], input[type="number"], select {
      width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;
    }
    .btn-submit {
      margin-top: 30px; background-color: #007bff; color: white; padding: 12px; border: none; border-radius: 6px; width: 100%; font-weight: bold; cursor: pointer;
    }
    .btn-submit:hover { background-color: #0056b3; }
  </style>
</head>
<body>
  <div class="payment-container">
    <h2>Payment for <?php echo htmlspecialchars($item['name']); ?></h2>
    <p>Price per day: <strong>₹<?php echo number_format($item['price'], 2); ?></strong></p>

    <form method="POST" action="payment_process.php">
      <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />

      <label for="rental_days">Number of Days to Rent:</label>
      <input type="number" name="rental_days" id="rental_days" min="1" value="1" required />

      <label for="payment_mode">Payment Mode:</label>
      <select name="payment_mode" id="payment_mode" required>
        <option value="Credit Card">Credit Card</option>
        <option value="Debit Card">Debit Card</option>
        <option value="Net Banking">Net Banking</option>
        <option value="UPI">UPI</option>
        <option value="Cash on Delivery">Cash on Delivery</option>
      </select>

      <label for="card_name">Name on Card:</label>
      <input type="text" name="card_name" id="card_name" required />

      <label for="card_number">Card Number:</label>
      <input type="text" name="card_number" id="card_number" required />

      <label for="expiry">Expiry Date (MM/YY):</label>
      <input type="text" name="expiry" id="expiry" required />

      <label for="cvv">CVV:</label>
      <input type="number" name="cvv" id="cvv" required />

      <button type="submit" class="btn-submit">Pay Now</button>
    </form>
  </div>
</body>
</html>
