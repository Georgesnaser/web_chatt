<?php
// Database connection (adjust as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_chat";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>