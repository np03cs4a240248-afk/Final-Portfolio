<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

requireLogin();
requireManager();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid book id");

$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
