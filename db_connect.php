<?php
$host = "sql5.freesqldatabase.com";
$user = "sql5834746";
$password = "bZdf8gRurh";
$database = "sql5834746";
$port = 3306;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>