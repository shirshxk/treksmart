<?php
// Database connection settings
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "treksmart";

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for a connection error and terminate if one occurs
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
