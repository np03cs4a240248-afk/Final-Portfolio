<?php
$host = "localhost";
$db   = "np03cs4a240248";
$user = "np03cs4a240248";
$pass = "cENW6U17Bf";
$charset = "utf8mb4";

// $host = "localhost";
// $db   = "library_management";
// $user = "root";
// $pass = "";
// $charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed");
}
