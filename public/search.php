<?php
require_once __DIR__ . "/../config/db.php";

/* GET SEARCH QUERY */
$query = trim($_GET['q'] ?? '');

if ($query === '') {
    exit;
}

/* PREPARED STATEMENT (SQL INJECTION SAFE) */
$stmt = $pdo->prepare("
    SELECT title 
    FROM books 
    WHERE title LIKE :search
    ORDER BY title
    LIMIT 5
");

$stmt->execute([
    ':search' => "%{$query}%"
]);

$books = $stmt->fetchAll();

/* OUTPUT SAFELY (XSS PROTECTION) */
foreach ($books as $book) {
    echo "<p>" . htmlspecialchars($book['title']) . "</p>";
}
