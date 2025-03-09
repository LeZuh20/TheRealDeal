<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$target_dir = "../uploads/";
$file_name = basename($_FILES["profile_pic"]["name"]);
$target_file = $target_dir . $file_name;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if the file is an image
$check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
if ($check === false) {
    die("File is not an image.");
}

// Allowed file types
$allowed_types = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowed_types)) {
    die("Only JPG, JPEG, PNG, and GIF files are allowed.");
}

// Upload file
if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
    // Save filename to database
    $sql = "UPDATE users SET profile_pic='$file_name' WHERE id='$user_id'";
    $conn->query($sql);

    echo "Profile picture updated!";
    header("Location: ../account.php");
} else {
    echo "Error uploading file.";
}
?>
