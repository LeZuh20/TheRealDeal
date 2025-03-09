<?php
include "php/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to add items to the cart!'); window.location.href='login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Check if product is already in cart
$check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity if item already in cart
    $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $update_stmt->execute();
} else {
    // Insert new item
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insert_stmt->execute();
}

echo "<script>alert('Item added to cart!'); window.location.href='cart.html';</script>";
?>
