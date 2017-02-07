<?php
$servername = "host";
$username = "username";
$password = "password.";
$conn = new mysqli($servername, $username, $password,"database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>