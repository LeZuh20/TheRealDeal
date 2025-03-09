<?php
include "db.php";
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header("Location: account.php");
} else {
    echo "Invalid login!";
}
?>