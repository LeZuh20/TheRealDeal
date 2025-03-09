<?php
include "php/db.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$user_id'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        $profile_pic = $user['profile_pic'] ? "uploads/" . $user['profile_pic'] : "uploads/default.png";
        ?>

        <h2>Welcome, <?php echo $user['username']; ?>!</h2>
        <p>Email: <?php echo $user['email']; ?></p>

        <!-- Profile Picture Display -->
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" width="150">

        <!-- Upload Profile Picture Form -->
        <form action="php/upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_pic" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>

        <h3>Order History</h3>
        <ul>
            <?php
            $orders_sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
            $orders_stmt = $conn->prepare($orders_sql);
            $orders_stmt->bind_param("i", $user_id);
            $orders_stmt->execute();
            $orders_result = $orders_stmt->get_result();
        
            if ($orders_result->num_rows > 0) {
                while ($order = $orders_result->fetch_assoc()) {
                    echo "<li>📦 <strong>" . $order['product_name'] . "</strong> - $" . $order['price'] . " (x" . $order['quantity'] . ") <br>🕒 " . $order['order_date'] . "</li>";
                }
            } else {
                echo "<li>No orders yet.</li>";
            }
            ?>
        </ul>


        <a href="logout.php">Logout</a>

    <?php else: ?>
        <h2>You are not logged in.</h2>
        <a href="login.html">Login</a> | <a href="signup.html">Sign Up</a>
    <?php endif; ?>

</body>
</html>
