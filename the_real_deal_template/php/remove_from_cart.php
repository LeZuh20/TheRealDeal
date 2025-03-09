<?php
include "php/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in!'); window.location.href='login.html';</script>";
    exit();
}

$cart_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cart_id, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Item removed from cart!'); window.location.href='../cart.html';</script>";
} else {
    echo "<script>alert('Error removing item!'); window.location.href='../cart.html';</script>";
}
?>
