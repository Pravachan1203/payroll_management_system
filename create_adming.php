<?php
// Include database connection file
include 'dbconnect.php';

// Admin credentials
$username = 'admin@4';
$password = 'admin1234'; // Replace this with your actual password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

// Insert admin user with hashed password
$sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
