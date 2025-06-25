<?php
$host = "localhost";
$username = "root";
$password = ""; // default password for root in XAMPP is empty
$database = "ClubCascade";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Connected successfully to the database!";
?>
