<?php
$host = "localhost"; // Change if necessary
$user = "root"; // Default user in XAMPP/LAMP/MAMP
$password = ""; // Default is empty
$database = "squared";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
