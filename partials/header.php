<?php require_once __DIR__ . "/../config/auth.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Online Library System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav>
    <h2>Library System</h2>
    <?php if (isLoggedIn()): ?>
        <span>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>
