<?php
include "db.php";

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO support_tickets (name, email, message) VALUES ('$name', '$email', '$message')";
$conn->query($sql);

echo "Support request submitted!";
?>
