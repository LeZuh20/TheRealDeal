<?php
include "php/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Check if email already exists
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Error: Email already registered!'); window.location.href='signup.html';</script>";
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Please log in.'); window.location.href='login.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Could not create account. Try again!'); window.location.href='signup.html';</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
