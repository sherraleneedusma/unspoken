<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unspoken";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create table for letters/messages if not exists
$sql = "CREATE TABLE IF NOT EXISTS letters (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR(255) DEFAULT NULL,
    recipient VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}
?>
