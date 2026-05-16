<?php
// Database Connection File
// Using mysqli with prepared statements for security

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ecampus_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
