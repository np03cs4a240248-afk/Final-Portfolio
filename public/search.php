<?php
require __DIR__ . "/../config/db.php";

$q = trim($_GET['q'] ?? '');
$like = "%$q%";

$stmt = $pdo->prepare("
    SELECT
        b.id,
        b.title,
        a.name AS author,
        c.name AS category,
        b.published_year,
        b.isbn,
        b.created_at
    FROM books b
    JOIN authors a ON b.author_id = a.id
    JOIN categories c ON b.category_id = c.id
    WHERE b.title LIKE ?
       OR a.name LIKE ?
       OR c.name LIKE ?
       OR CAST(b.published_year AS CHAR) LIKE ?
       OR b.isbn LIKE ?
    ORDER BY b.created_at DESC
");

$stmt->execute([$like, $like, $like, $like, $like]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
