<?php
include "php/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to checkout!'); window.location.href='login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.*, p.name AS product_name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders_inserted = false;

while ($row = $result->fetch_assoc()) {
    $product_name = $row['product_name'];
    $price = $row['price'];
    $quantity = $row['quantity'];

    $order_sql = "INSERT INTO orders (user_id, product_name, price, quantity) VALUES (?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("isdi", $user_id, $product_name, $price, $quantity);

    if ($order_stmt->execute()) {
        $orders_inserted = true;
    }
}

// Clear cart after checkout
$delete_cart_sql = "DELETE FROM cart WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_cart_sql);
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();

if ($orders_inserted) {
    echo "<script>alert('Order placed successfully!'); window.location.href='account.php';</script>";
} else {
    echo "<script>alert('Error placing order!'); window.location.href='cart.html';</script>";
}
?>
