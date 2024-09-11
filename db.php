<?php
// Database credentials
$servername = "localhost";  // Typically localhost if hosted locally
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "entrance_exam_db"; // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: To ensure the connection is working
// echo "Connected successfully";
?>
