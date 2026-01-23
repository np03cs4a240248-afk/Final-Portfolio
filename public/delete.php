<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
requireLogin();
if (!isManager()) die("Access denied");

$stmt = $conn->prepare("DELETE FROM books WHERE id=?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

header("Location: index.php");
