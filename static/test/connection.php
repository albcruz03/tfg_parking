<?php
$servername = "192.168.1.5";
$username = "albert";
$password = "1234@";
$dbname = "app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
