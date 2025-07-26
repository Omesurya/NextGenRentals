<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_id = intval($_POST['item_id']);
    $rental_days = intval($_POST['rental_days']);
    $payment_mode = $_POST['payment_mode'];

    // Get item price
    $stmt = $conn->prepare("SELECT price FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows != 1) {
        echo "<script>alert('Item not found'); window.location='dashboard.php';</script>";
        exit();
    }
    $item = $result->fetch_assoc();
    $price_per_day = $item['price'];
    $total_amount = $price_per_day * $rental_days;

    // Insert into payments table
    $insert = $conn->prepare("INSERT INTO payments (user_id, item_id, rental_days, amount_paid, payment_mode) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("iiids", $user_id, $item_id, $rental_days, $total_amount, $payment_mode);

    if ($insert->execute()) {
        header("Location: payment_success.php");
        exit();
    } else {
        echo "<script>alert('Payment failed. Please try again.'); window.location='dashboard.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid access'); window.location='dashboard.php';</script>";
    exit();
}
?>
