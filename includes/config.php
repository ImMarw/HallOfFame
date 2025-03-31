<?php
$host = "localhost";
$user = "root";
$pass = "root";
$dbname = "hall_of_fame";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
