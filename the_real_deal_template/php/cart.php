<?php
include "php/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to view your cart!'); window.location.href='login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.*, p.name AS product_name, p.price FROM cart c 
        JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>

    <h2>Your Cart</h2>
    <table border="1">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>
        
        <?php
        $total_price = 0;

        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['price'] * $row['quantity'];
            $total_price += $subtotal;

            echo "<tr>
                    <td>{$row['product_name']}</td>
                    <td>\${$row['price']}</td>
                    <td>{$row['quantity']}</td>
                    <td>\${$subtotal}</td>
                    <td><a href='php/remove_from_cart.php?id={$row['id']}'>❌</a></td>
                  </tr>";
        }
        ?>

    </table>

    <h3>Total: $<?php echo number_format($total_price, 2); ?></h3>

    <a href="checkout.html">Proceed to Checkout</a>

</body>
</html>
