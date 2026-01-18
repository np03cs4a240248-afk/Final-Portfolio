<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "library_management";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed");
}
?>
